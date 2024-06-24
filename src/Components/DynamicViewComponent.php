<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Models\DynamicContentType;
use Aman5537jains\AbnCmsCRUD\Components\CounterAnimationComponent;
use Aman5537jains\AbnCmsCRUD\Components\HtmlComponent;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use Aman5537jains\AbnCmsCRUD\ViewComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;
use Aman5537jains\AbnCmsCRUD\Layout;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Support\Facades\Blade;

 class DynamicViewComponent extends Layout{
    public $query;
    public $results;
     public $processedResults=[];

    function init(){
        parent::init();
        $this->setQuery(new DynamicContentData);
    }
    function defaultConfig(){
        return [
            "fetch_method"=>"paginate",
            "limit"=>10,

        ];
    }

    function configComponents()
    {
        return [
            "fetch_method"=>new InputComponent(["name"=>"fetch_method","value"=>"paginate","type"=>"select","options"=>["paginate"=>"Paginate","get"=>"Get"]]),
            "limit"=>(new InputComponent(["name"=>"limit","value"=>"200","type"=>"select","options"=>["10"=>"10","50"=>"50","100"=>"20"]]))->setValue('50'),
        ];
    }
    function execute(){


        $method =$this->getConfig("fetch_method","paginate");

        if($method=="paginate")
            $this->results=  $this->query->paginate($this->getConfig("limit",10));
        if($method=="get")
            $this->results=  $this->query->get();
       return $this;
    }
    function setQuery($query){
        $this->query=$query;
        $this->setModel($this->query);
        return $this;
    }

    function getQuery(){
        return $this->query ;
    }


    function processData(){
        $view = $this->getConfig("view","");
        $data =[];
        $type = DynamicContentType::find($view->dynamic_content_type_id);
        $configs = json_decode($type->configuration);
        $attributes = config("dynamic_content.components");
        $components = config("dynamic_content.fields");
        $allCmp = config("dynamic_content.all_components.form");
        $defaultComponent = config("dynamic_content.default");
        $banners =  $this->setQuery($this->getQuery()->where("dynamic_content_type_id",$type->id))->execute()->getResults();
        foreach($banners as $banner){
            $modelRow=[];
            $bannerAttributes = DynamicContentDataAttribute::where("dynamic_content_data_id",$banner->id)->pluck("value","name");


            $banner->content_attributes =$bannerAttributes;
            foreach($configs as $config){
                if($config->is_attribute=='1'){
                    $field_name = $config->field_name;
                    if(isset($config->view_component) && !empty($config->view_component) && isset($allCmp[$config->view_component])){
                        $component =   $allCmp[$config->view_component];
                        $component['config'] = isset($config->view_config)  ?json_decode($config->view_config,true):[];

                    }
                    else{
                        $component =    isset($attributes[$config->attribute]) ? $attributes[$config->attribute]['view'] : $defaultComponent['view'];
                        $component['config'] = isset($component['config']) ?$component['config']:[];
                    }
                    $component['config']=$component['config']+["name"=>$config->field_name,"label"=>$config->label,"db_config"=>$config];
                    $componentObject  =(new $component['class']($component['config']));;
                    $componentObject->setData(["row"=>$banner]);
                    $componentObject->setValue($bannerAttributes[$config->field_name]);
                    $modelRow["attributes_$field_name"] = $componentObject;
                }
                else{
                    $field_name = $config->field;
                    if(isset($config->view_component) && !empty($config->view_component) && isset($allCmp[$config->view_component])){
                        $component =   $allCmp[$config->view_component];
                        $component['config'] = isset($config->view_config)  ?json_decode($config->view_config,true):[];

                    }
                    else{
                        $component =    isset($components[$config->field]) ? $components[$config->field]['view'] : $defaultComponent['view'];
                        $component['config'] = isset($component['config']) ?$component['config']:[];
                    }
                    $component['config']=$component['config']+["name"=>$config->field_name,"label"=>$config->label,"db_config"=>$config];
                    $componentObject  =(new $component['class']($component['config']));;
                    $componentObject->setData(["row"=>$banner]);
                    $componentObject->setValue($banner->{$field_name});
                    $modelRow[$field_name] = $componentObject;
                }




            }
            $modelRow["id"] = new TextComponent(["name"=>"id","value"=>$banner->id]);
            $modelRow["slug"] = new TextComponent(["name"=>"slug","value"=>$banner->slug]);
            $modelRow["content_link"] = new LinkComponent(["name"=>"content_link","link"=>route("renderContentSlug",[$type->slug,$banner->slug])]);
             $modelRow["parent_link"] = new LinkComponent(["name"=>"content_link","link"=>route("renderContent",[$type->slug])]);

            $RowProvider = new RowProvider($modelRow);

            $html =  new HtmlComponent(["value"=>['row' => $RowProvider->row]]);

            $this->processedResults[]=  $html->setView( html_entity_decode(Blade::render(htmlspecialchars_decode($view->template), ['row' => $RowProvider->row]),3));


        }

        return $this->processedResults;
    }

    function view(){

            $data = $this->processData();

        $rows = $this->getResults();

        if($this->getConfig("component",false))
        {
            $component =  $this->getConfig("component");
            $componentObject = new $component["class"]([]);
            $componentObject->processedResults= ($this->processedResults);
             // dump($componentObject);
        }
        else{
            $componentObject =implode("",$data);
        }

        return view("AbnDynamicContent::paginate-view-component",
                                                                [
                                                                "contents"=>$componentObject,
                                                                "pagination"=>$this->getConfig("fetch_method","paginate")=="paginate"?$rows->appends($_GET)->links():''
                                                                ]
                                                            );
    }

}


class RowProvider{
    public $row=[];
    function __construct($row)
    {
        $this->row = $row;
    }

}
