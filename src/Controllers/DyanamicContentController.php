<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Controllers;

use AbnCms\RolesPermission\PermissionService;
use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\ActionComponent;
use Aman5537jains\AbnCmsCRUD\Layouts\MultiFormBuilder;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
class DyanamicContentController extends AbnCmsBackendController
{
    public $uniqueKey="id";
    public static $module="dynamic-content";
    public static $moduleTitle="Dyanamic Content";
    public  $content;

    function __construct()
    {
        // AbnCms::addPermissions(["dynamic-content-aman"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
        $id = request("dynamic_content_type_id",0);
        $this->content = DynamicContentType::find($id);

        self::$moduleTitle=$this->content->name;
        parent::__construct();

    }
    public function hasPermission($action,$module="",$redirect=true){

        $module = self::$module."-".$this->content->slug;
        if(!PermissionService::has(($module==''?self::$module:$module),$action)){
            if($redirect){
                return abort(403);
            }
            else{
                return false;
            }
        }
        return true;

    }

    function getModel()
    {
        return DynamicContentData::class;
    }

    // static function getRoutes($service=null){
    //     $service->get("dynamic-content/{type}","dynamic-content.type");
    // }


    function search($model, $q)
    {
        return parent::search($model, $q)->where("dynamic_content_type_id",request("dynamic_content_type_id",0));
    }
    function action($name, $params = [], $module = null)
    {
        $url = parent::action($name,$params,$module);

        if(strpos($url,'?') !== false) {
            $url .= '&dynamic_content_type_id='.request("dynamic_content_type_id",0);
         } else {
            $url .= '?dynamic_content_type_id='.request("dynamic_content_type_id",0);
         }
        return $url;
    }

    function viewBuilder($model)
    {
        $id = request("dynamic_content_type_id",0);
        $content = DynamicContentType::find($id);

        $view = parent::viewBuilder($model);

        $configs = json_decode($content->configuration);
        $fields=[];
        $components = config("dynamic_content.fields");
        $defaultComponent = config("dynamic_content.default");

        foreach($configs as $config){
            if($config->field!='description' && $config->is_attribute=='0'){
                $component =    isset($components[$config->field]) ? $components[$config->field]['view'] : $defaultComponent['view'];
                $component['config'] = isset($component['config']) ?$component['config']:[];
                $component['config']=$component['config']+["name"=>$config->field,"label"=>$config->label,"db_config"=>$configs];
                $view->setField($config->field,$component);
                $view->getField($config->field)->setConfig("label",$config->label);


                $fields []=$config->field;
            }


        }

        $view->onlyFields(array_merge($fields,["actions"]));

        return $view;
    }

    function formBuilder($model = null)
    {


        $id = request("dynamic_content_type_id",0);
        $content = DynamicContentType::find($id);


        $form = parent::formBuilder($model);
        $configs = json_decode($content->configuration);
        $fields=[];
        $components = config("dynamic_content.fields");
        $attributes = config("dynamic_content.components");
        $allCmp = config("dynamic_content.all_components.form");
        $defaultComponent = config("dynamic_content.default");
        $dynamicContentDataAttributeForm =new MultiFormBuilder(["name"=>"dynamicContentDataAttribute"]);



        foreach($configs as $config){
                if($config->is_attribute=='1'){
                    if(isset($config->form_component) && !empty($config->form_component) && isset($allCmp[$config->form_component])){
                        $component =   $allCmp[$config->form_component];
                        $component['config'] = isset($config->form_config)  ?json_decode($config->form_config,true):[];

                    }
                    else{
                        $component =    isset($attributes[$config->attribute]) ? $attributes[$config->attribute]['form'] : $defaultComponent['form'];
                        $component['config'] = isset($component['config']) ?$component['config']:[];
                    }

                    $component['config']=$component['config']+["name"=>$config->field_name,"label"=>$config->label,"db_config"=>$config];

                    $dynamicContentDataAttributeForm->addField($config->field_name,$component);
                    if($config->required=='1'  ){
                        $dynamicContentDataAttributeForm->getField($config->field_name)->setConfig("edit_mode",$model->exists)->validator()->add(["required"]);
                    }
                }
                else{
                    if(isset($config->form_component) && !empty($config->form_component) && isset($allCmp[$config->form_component])){
                        $component =   $allCmp[$config->form_component];
                        $component['config'] = isset($config->form_config)  ?json_decode($config->form_config,true):[];

                    }
                    else{
                        $component =    isset($components[$config->field]) ? $components[$config->field]['form'] : $defaultComponent['form'];
                        $component['config'] = isset($component['config']) ?$component['config']:[];
                    }
                    $component['config']=$component['config']+["name"=>$config->field,"label"=>$config->label,"db_config"=>$config];

                    $form->setField($config->field,$component);

                    if($config->required=='1' ){

                        $form->getField($config->field)->setConfig("edit_mode",$model->exists)->validator()->add(["required"]);

                    }
                    $fields []=$config->field;
                }


        }


        if(count($dynamicContentDataAttributeForm->getFields())>0){
            $fields []='dynamicContentDataAttribute';
            $form->addField("dynamicContentDataAttribute",$dynamicContentDataAttributeForm);
        }
        $form->onlyFields(array_merge($fields,["submit"]));
        if($content->use_template=='1'){
            $form->setTemplate(function($flds,$rows,$cmp) use($content){
// dd($flds->dynamicContentDataAttribute->fields["first_name"]);
                return htmlspecialchars_decode(Blade::render(htmlspecialchars_decode($content->template), ['flds' => $flds]));

            });
        }
        if($model->exists && request()->isMethod("GET") ){
            $attributes = DynamicContentDataAttribute::where("dynamic_content_data_id",$model->id)->get();
            $all=[];
            foreach($attributes as $k=>$attribute){

                $all[$attribute->name] =$attribute->value;
            }
            $form->setConfig("beforeRender",function($formComp)use ($all){
                if($formComp->hasField("dynamicContentDataAttribute"))
                    $formComp->getField("dynamicContentDataAttribute")->setValue([$all]);

            });

        }
         if(request()->isMethod("POST") ||  request()->isMethod("PATCH")  ){

            $dynamicForm ="";
            $form->setConfig("beforeSave",function($form,$model)use($id,&$dynamicForm){

                if(isset($model->dynamicContentDataAttribute)){
                    $dynamicForm  =$model->dynamicContentDataAttribute;
                    unset($model->dynamicContentDataAttribute);
                }

                    $model->dynamic_content_type_id =$id;
                return $model;
            });

            $form->setConfig("afterSave",function($form,$model)use(&$dynamicForm){

              if(!empty($dynamicForm))  {
                $forms = json_decode($dynamicForm);

                        foreach($forms as $inputs){
                            foreach($inputs as $name=>$value){

                                    DynamicContentDataAttribute::where("dynamic_content_data_id",$model->id)
                                    ->where("name",$name)
                                    ->delete();
                                    $dynamicContentDataAttribute = new DynamicContentDataAttribute;
                                    $dynamicContentDataAttribute->dynamic_content_data_id =$model->id;
                                    $dynamicContentDataAttribute->name =$name;
                                    $dynamicContentDataAttribute->value =$value;
                                    $dynamicContentDataAttribute->save();

                            }
                        }

                }

                return $model;
            });
        }

        return $form;
    }



}
