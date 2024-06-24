<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;

use Aman5537jains\AbnCms\Models\DynamicContentType;
use Aman5537jains\AbnCmsCRUD\Components\LinkComponent;
use Aman5537jains\AbnCmsCRUD\ViewComponent;
use Aman5537jains\AbnCmsCRUD\Components\TextComponent;


use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Support\Facades\Blade;

 class AtrributeFilterListComponent extends DynamicViewComponent{

    function execute(){

           $is_featured= $this->getConfig("attribute_column","is_featured");
           $value =  $this->getConfig("attribute_column_value","1");
           $method =  $this->getConfig("fetch_method","paginate");

            $this->results =  $this->query->whereHas("dynamicContentDataAttribute",function($query)use($is_featured,$value){
                $query->where("name", $is_featured)->where("value",$value);
            })->$method(10);



       return $this;
    }
    function defaultConfig(){
        return [
            "fetch_method"=>"paginate",
            "limit"=>10,
            "attribute_column"=>"is_featured",
            "attribute_column_value"=>"1"
        ];
    }


}
