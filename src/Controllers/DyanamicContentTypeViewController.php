<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Controllers;

use Aman5537jains\AbnCms\Editor\GrapejsComponent;
use Aman5537jains\AbnCmsCRUD\AbnCmsBackendController;
use Aman5537jains\AbnCmsCRUD\Components\ActionComponent;
use Aman5537jains\AbnCmsCRUD\Components\ComponentSelector;
use Aman5537jains\AbnCmsCRUD\Components\ConfigBuilderComponent;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;
use Aman5537jains\AbnCmsCRUD\Layouts\MultiFormBuilder;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentType;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
class DyanamicContentTypeViewController extends AbnCmsBackendController
{
    public $uniqueKey="id";
    public static $module="dynamic-content-type-view";
    public static $moduleTitle="Dyanamic Content Type Views";
    public  $content;
    function hasPermission($action, $module = "", $redirect = true)
    {
        return true;
    }
    function __construct()
    {
        $id = request("dynamic_content_type_id",0);
        $this->content = DynamicContentType::find($id);
        self::$moduleTitle=$this->content->name;
        parent::__construct();

    }
    function getModel()
    {
        return DynamicContentTypeView::class;
    }

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



        $view = parent::viewBuilder($model);
        $view->addField("shortcode",new TextComponent(["name"=>'shortcode',"beforeRender"=>function($cmp){
            $row = $cmp->getData("row");
            $row->slug;
            $cmp->setValue('[shortcode name="dynamicContentView" content="'.$row->slug.'"]');

        }]));
        $components = $view->getField("actions")->getConfig("components");
        foreach($components as $component){
            if($component->getConfig("name")=='edit'){
                $component->setConfig("beforeRender",function($component){
                    $data = $component->getData();

                    $component->setConfig("link",$this->action("edit",[$data["row"]->{$this->uniqueKey},"view_type"=>$data["row"]->view_type]));
                 } );
            }
        }


        $view->onlyFields(["title","shortcode","is_default","view_type","actions"]);

        return $view;
    }

    function formBuilder($model = null)
    {

        $id = request("dynamic_content_type_id",0);
        $content = DynamicContentType::find($id);

        $form = parent::formBuilder($model);

        $form->getField("dynamic_content_type_id")->setValue($id)->setConfig("visible",false);

        $configs = json_decode($content->configuration);
        $modelRow=[];
        foreach($configs as $config){
            if($config->is_attribute=='1'){
                $field_name = $config->field_name;

                $modelRow["attributes_$field_name"] = 1;
            }
            else{
                $field_name = $config->field;

                $modelRow[$field_name] = 1;
            }
        }
        $modelRow["content_link"] = 1;
        $modelRow["slug"] = 1;
        $modelRow["id"] = 1;

        $names= "Available variables : ";
         foreach($modelRow as $name=>$val){
            if($name!="attributes"){
                $names.='{{$row["'.$name.'"]}}  ';
            }
            else{
                $names.='{{$row["attributes_'.$name.'"]}}  ';
            }
         }
         $views = config("dynamic_content.views");
         $options = [];
         $type = request("view_type","LIST");
         $form->getField("view_type")->setValue($type)->setConfig("type","hidden")
            ->setConfig("showLabel",false)->setConfig("parentClass","");
        if($type=="LIST"){

            foreach($views["LIST"] as $key=>$v){
                $options[$key]=$v;
            }
        }
        if($type=="VIEW"){

         foreach($views["VIEW"] as $key=>$v){
            $options[$key]=$v;
         }
        }
        if($type=="FORM"){

         foreach($views["FORM"] as $key=>$v){
            $options[$key]=$v;
         }
        }
          //,$views["FORM"]

        $form->addField("variables",new TextComponent(["value"=>"$names "]));
        $form->setField("view_name",new ComponentSelector(["name"=>"view_name","options"=>$options ]));
        // $form->setField("config",new ConfigBuilderComponent(["fields"=>["success_message"=>"Success","type"=>new InputComponent(["name"=>"type","type"=>"select","options"=>["Yes","No"]])]]));
        $fields=["title",'dynamic_content_type_id',"is_default","view_name","variables","template","layout","submit"];

        if($type=="LIST"){
            $fields=["title",'dynamic_content_type_id',"is_default",'view_type',"view_name","variables","template","layout","submit"];
            $form->setField("layout",new GrapejsComponent(["name"=>"layout"]));
        }
        if($type=="VIEW" || $type=="FORM"){
            $fields=["title",'dynamic_content_type_id',"is_default",'view_type',"view_name","variables","template","submit"];
            $form->removeField("layout");
            $form->setField("template",new GrapejsComponent(["name"=>"template"]));
        }



        $form->onlyFields( $fields);
        $form->setConfig("beforeSave",function($form,$model){

            if($model->is_default=='1'){

                    $update = $this->getModelObject()
                    ->where("dynamic_content_type_id",$model->dynamic_content_type_id)
                    ->where("view_type",$model->view_type);
                    if($model->exists){
                        $update= $update->where("id","!=",$model->id);
                    }

                    $update->update(["is_default"=>"0"]);
            }
            return $model;

        });
        // dd(request()->all());
        return $form;
    }
    function renderContentSlug(){

    }
    public function index(Request $request,$slug=""){
        $this->theme="AbnDynamicContent::";
        $this->view="";
        $this->hasPermission("view");
        $TableLayout =$this->viewBuilder($this->getModelObject());
        return  $this->view("dynamic-view",["table"=>$TableLayout]);
}

}
