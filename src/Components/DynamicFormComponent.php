<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Models\DynamicContentType;
use Aman5537jains\AbnCmsCRUD\Components\InputComponent;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use Aman5537jains\AbnCmsCRUD\Layouts\FormBuilder;
use Aman5537jains\AbnCmsCRUD\Layouts\MultiFormBuilder;
use Aman5537jains\AbnCmsCRUD\ViewComponent;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Support\Facades\Blade;

 class DynamicFormComponent extends FormBuilder{

    function defaultConfig(){
        return [
            "success_message"=>"Success!",
            "contact_mail"=>"aman@yopmail.com",

        ];
    }

    // function configComponents()
    // {
    //     return [
    //         "success_message"=>new InputComponent(["name"=>"success_message"]),
    //         "contact_mail"=>new InputComponent(["name"=>"contact_mail","type"=>"email"]),
    //     ];
    // }
    function init(){
        parent::init();

        $view = $this->getConfig("view",(object)["id"=>0,"dynamic_content_type_id"=>0]);
        if($view->id==0){
            return 1;
        }
        $this->setConfig("action",route("submit-content-form",["view_id"=>$view->id]));
        $content = DynamicContentType::find($view->dynamic_content_type_id);
        $id = $view->dynamic_content_type_id;
        if(request("content","")==""){
            $this->setModel(new DynamicContentData);
        }
        else{
            $contentSlug = request("content","");
            $this->setModel(DynamicContentData::where("dynamic_content_type_id",$view->dynamic_content_type_id)
            ->where("slug",$contentSlug)->first());
        }
        $model = $this->getModel();

        $form = $this;
        $configs = json_decode($content->configuration);
        $fields=[];
        $components = config("dynamic_content.fields");
        $attributes = config("dynamic_content.components");
        $defaultComponent = config("dynamic_content.default");
        $dynamicContentDataAttributeForm =new MultiFormBuilder(["name"=>"dynamicContentDataAttribute"]);

        foreach($configs as $config){
                if($config->is_attribute=='1'){

                    $component =    isset($attributes[$config->attribute]) ? $attributes[$config->attribute]['form'] : $defaultComponent['form'];
                    $component['config'] = isset($component['config']) ?$component['config']:[];
                    $component['config']=$component['config']+["name"=>$config->field_name,"label"=>$config->label,"db_config"=>$config];

                    $dynamicContentDataAttributeForm->addField($config->field_name,$component);
                    if($config->required=='1'  ){
                        $dynamicContentDataAttributeForm->getField($config->field_name)->setConfig("edit_mode",$model->exists)->validator()->add(["required"]);
                    }

                }
                else{
                    $component =    isset($components[$config->field]) ? $components[$config->field]['form'] : $defaultComponent['form'];
                    $component['config'] = isset($component['config']) ?$component['config']:[];
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
        if($view->template!=''){
            $form->setTemplate(function($flds,$rows,$cmp) use($view){

                    return htmlspecialchars_decode(Blade::render(htmlspecialchars_decode($view->template), ['row' => (array)$flds]));

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
                                if($value!=""){
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

                }

                return $model;
            });
        }


        return $form;
    }
    function submit(){
        $response=$this->validateAndSave(request()->all());
            if($response->status){

                AbnCms::flash($this->getConfig("success_message","Record Added Successfully"));

                return  redirect()->back();
            }
            else{
                AbnCms::flash($response->data->first(),"danger");
                return redirect()->back()->with(["errors"=>$response->data])->withInput()->send();;
            }
    }




}
