<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Controllers;

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\Theme\ScriptLoader;
use Aman5537jains\AbnCms\Models\DynamicContent;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\AddMoreComponent;
use Aman5537jains\AbnCmsCRUD\Components\ComponentSelector;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use Aman5537jains\AbnCmsCRUD\Components\MultiComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;
use Aman5537jains\AbnCmsCRUD\CrudService;
use Aman5537jains\AbnCmsCRUD\Layouts\MultiFormBuilder;
use Aman5537jains\AbnDynamicContentPlugin\AbnDynamicContentService;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentType;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DyanamicContentTypeController extends AbnCmsBackendController
{
    public $uniqueKey="id";
    public static $module="dynamic-content-types";
    public static $moduleTitle="Dyanamic Content";

    function getModel()
    {
        return DynamicContentType::class;
    }

    function viewBuilder($model)
    {
        $view = parent::viewBuilder($model);

        $newcomponents  = new LinkComponent(["name"=>"Contents","beforeRender"=>function($cmp){
            $row = $cmp->getData("row");
            $cmp->setConfig("link",route("dynamic-content.index",["dynamic_content_type_id"=>$row->id]));
        }]);
        $newcomponents2  = new LinkComponent(["name"=>"Views","beforeRender"=>function($cmp){
            $row = $cmp->getData("row");
            $cmp->setConfig("link",route("dynamic-content-type-view.index",["dynamic_content_type_id"=>$row->id]));
        }]);
        if($view->hasField("actions")){
            $components = $view->getField("actions")->getConfig("components");
            $components[] =$newcomponents;
            $components[] =$newcomponents2;
        }
        else{
              $view->addField("actions",["class"=>MultiComponent::class,"config"=>["components"=> []]]);
              $components[] =$newcomponents;
              $components[] =$newcomponents2;
        }


        $view->getField("actions")->setConfig("components",$components);
        $view->addFieldBefore("name","slug",new TextComponent(["name"=>'slug',"beforeRender"=>function($cmp){
            $row = $cmp->getData("row");
            $row->slug;
            $cmp->setValue($row->slug);

        }]));
        $view->removeField("configuration");
        return $view;
    }
    function formBuilder($model = null)
    {

        // dd(request()->all());
        $buider =  parent::formBuilder($model);

        $columns = Schema::getColumnListing((new DynamicContentData())->getTable()); // users table
        $requiredColumns = array_diff( $columns, ["id","updated_at","created_at",'dynamic_content_type_id'] );
        $attributes = config("dynamic_content.components");


        $finalArr  =[];
        foreach($requiredColumns as $col){
            $finalArr[$col] = ucfirst($col);
        }
        foreach($attributes as $name =>$col){
            $finalArr["attribute-$name"] = "Attribute - ".ucfirst($name);
        }
        $addMore = new AddMoreComponent(["name"=>'configuration' ]);
        $addMore->addField("field",new InputComponent(["name"=>"field","type"=>"select","options"=>$finalArr]));
        $addMore->addField("field_name",new InputComponent(["name"=>"field_name"]));
        $addMore->addField("field_config",new InputComponent(["name"=>"field_config"]));
        $addMore->addField("label",new InputComponent(["name"=>"label"]));
        $addMore->addField("is_attribute",new InputComponent(["name"=>"is_attribute",'visible'=>"false"]));
        $addMore->addField("attribute",new InputComponent(["name"=>"attribute",'visible'=>"false"]));
        $addMore->addField("required",new InputComponent(["name"=>"required","type"=>"select","options"=>["1"=>"YES","0"=>"NO"]]));
        $forms = config("dynamic_content.all_components.form");



           foreach($forms as $key=>$v){
               $options[$key]=$v;
           }
        $views = config("dynamic_content.all_components.view");
        $optionViews=[];


        foreach($views as $key=>$v){
            $optionViews[$key]=$v;
        }

        $addMore->addField("form_component",new ComponentSelector(["name"=>"form_component","options"=>$options,"config_field_name"=>"form_config" ]));
        $addMore->addField("view_component",new ComponentSelector(["name"=>"view_component","options"=>$optionViews ,'config_field_name'=>"view_config"]));

        $buider->addFieldBefore("configuration","Heading",new TextComponent(["name"=>"Setup","value"=>"Setup Fields"]));
        $buider->addField("configuration",$addMore);
        $buider->addField("template",new GrapejsComponent(["name"=>"template"]));
        $buider->getField("use_template")->setConfig("options",["1"=>"Yes","0"=>"No"])->setValue("0");
        if(request()->isMethod("POST") ||  request()->isMethod("PATCH")  ){
            $configurations = request()->get('configuration');
            $finalConfig=[];
            $names=[];
            foreach($configurations as $k=>$config){


                if(substr( $config['field'], 0, 10 ) === "attribute-"){
                    if(empty($config['field_name'])){
                        $config['field_name'] =  str_replace("attribute-","",$config['field']);

                    }
                    $config['is_attribute'] =  '1';
                    $config['attribute'] =  str_replace("attribute-","",$config['field']);
                    if(empty($config['label'])){
                        $config['label']=  ucfirst(str_replace("attribute-","",$config['field']));
                    }
                }
                else{
                    if(empty($config['field_name'])){
                        $config['field_name']=  $config['field'];
                    }
                    $config['is_attribute'] =  '0';
                    if(empty($config['label'])){
                        $config['label']=  ucfirst($config['field_name']);
                    }
                }

                if(empty($config['required'])){
                    $config['required']= '0';
                }
                if(!isset($names[$config['field_name']])){
                    $names[$config['field_name']]=1;
                }
                else{
                     $buider->addError("fields","Multiple fields cannot have same name");
                }
                $finalConfig[$k]=$config;
            }
            if(!isset($names["title"]) || !isset($names["description"])){
                $buider->addError("fields","Title and Description fields are required to have in form");
            }

            request()->merge([
                'configuration' => $finalConfig,
            ]);


        }
        if(request()->isMethod("GET")    ){
            $buider->setConfig("beforeRender",function($c)use($model){
                // dd($model->configuration);
                $c->getField("configuration")->setValue(json_decode($model->configuration,true));
            });
            if(!$model->exists){
                $buider->setConfig("beforeRender",function($c)use($model){
                    // dd($model->configuration);
                    $c->getField("configuration")->setValue(json_decode('[{"field":"title","field_name":"title","field_config":"","label":"Title","is_attribute":"0","attribute":"","required":"1"},{"field":"description","field_name":"description","field_config":"","label":"Description","is_attribute":"0","attribute":"","required":"0"}]',true));
                });
            }
            // $addMore->setValue(json_decode($model->configuration,true));
            // if($model->ex)
            // $buider->getField("configuration")->setValue(json_decode($model->configuration));
        }

        $addMore->setTemplate(function($flds,$rows,$cmp){
            return "<div class='row'>
            <div class='col-3'>$flds->field </div>
            <div class='col-3'>$flds->field_name </div>
            <div class='col-3'>$flds->field_config </div>
            <div class='col-3'>$flds->label </div>
            <div class='col-3'>$flds->required </div>
            <div class='col-3'>$flds->form_component </div>
            <div class='col-3'>$flds->view_component </div>

        </div>";
        });
        $buider->setConfig("afterSave",function($form,$model){

            // if(!$model->exists){
                AbnCms::createAdminMenu(ucfirst($model->name),"dynamic-content.index","",0,
                        [["module"=>"dynamic-content-".$model->slug,"action"=>"view"]],
                        "Harimayco\Menu\DefaultMenuHandler",
                        "ROUTE_NAME",
                        ["params"=>["dynamic_content_type_id"=>$model->id]]
                );

                AbnCms::addPermissions(["dynamic-content-".$model->slug=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
            // }
        });
        return $buider;

    }

    public function renderContent(Request $request,$slug){
            if($slug!=""){
                $service = new AbnDynamicContentService;

                $DynamicContentType = DynamicContentType::where("slug",$slug)->first();

                $DynamicContentTypeView =   DynamicContentTypeView::where("is_default",'1')
                    ->where("dynamic_content_type_id",$DynamicContentType->id)->where("view_type","LIST")->first();


                if(!$DynamicContentTypeView){
                    return "default view not set";
                    $DynamicContentType= DynamicContentType::where("slug",$slug)->first();

                    $DynamicContentTypeView = (object)["dynamic_content_type_id"=>$DynamicContentType->id ,"view_type"=>"LIST","view_name"=>"Pagination","template"=>""];
                }

                if(empty($DynamicContentTypeView->layout)){
                    $DynamicContentTypeView->layout = '[shortcode name="dynamicContentView" content="'.$DynamicContentTypeView->slug.'"]';
                }
                $editor= new \Aman5537jains\AbnCms\Editor\Editor();
                $rendered = $editor->setPage((object)["description"=>$DynamicContentTypeView->layout])
                            ->render();

                // dd($DynamicContentTypeView->dynamicContentType);
                return AbnCms::getActiveTheme("ACTIVE_THEME")
                ->setPageTitle($DynamicContentTypeView->dynamicContentType->name)

                ->setLayout(config("dynamic_content.layout"),["title"=>$DynamicContentTypeView->dynamicContentType->name])
                ->setPageContent($rendered)
                ->render(["title"=>$DynamicContentTypeView->dynamicContentType->name]);


            }
    }
    function renderContentSlug(Request $request,$content,$slug){
        $DynamicContentType = DynamicContentType::where("slug",$content)->first();

            $DynamicContentTypeView =   DynamicContentTypeView::where("is_default",'1')
             ->where("dynamic_content_type_id",$DynamicContentType->id)

            ->where("view_type","VIEW")->first();
            if(!$DynamicContentTypeView){
                return "default view not set";
            }
            if(empty($DynamicContentTypeView->layout)){
                $DynamicContentTypeView->layout = '[shortcode name="dynamicContentView" content="'.$DynamicContentTypeView->slug.'" slug="'.$slug.'"]';
            }
            else{
                $DynamicContentTypeView->layout=  str_replace("[[slug]]",$slug,$DynamicContentTypeView->layout);
            }
                $content = DynamicContentData::where("slug",$slug)->first();

                if($content){

                    AbnCms::getActiveTheme()->getSeo()->setTitle($content->title);
                }




            $editor= new \Aman5537jains\AbnCms\Editor\Editor();
                $rendered = $editor->setPage((object)["description"=>$DynamicContentTypeView->layout])
                            ->render();

                // dd($DynamicContentTypeView->dynamicContentType);
                return AbnCms::getActiveTheme("ACTIVE_THEME")



                ->setLayout(config("dynamic_content.layout"),["title"=>$DynamicContentTypeView->dynamicContentType->name])
                ->setPageContent($rendered)
                ->render(["title"=>$DynamicContentTypeView->dynamicContentType->name]);
    }

    function onDelete(Request $request, $slug)
    {
        $model = $this->getModel()::where("id",$slug)->first();

        if(parent::onDelete($request, $slug))
        {
            AbnCms::removeAdminMenu(ucfirst($model->name));
            AbnCms::removePermissions(["dynamic-content-".$model->slug=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
        }
        return true;
    }

}
