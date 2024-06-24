<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;


 class FilterListComponent extends DynamicViewComponent{

    function execute(){

           $is_featured= $this->getConfig("attribute_column","is_featured");
           $value =  $this->getConfig("attribute_column_value","1");
           $method =  $this->getConfig("fetch_method","paginate");

            $this->results =  $this->query->whereHas("dynamicContentDataAttribute",function($query)use($is_featured,$value){
                $query->where("name", $is_featured)->where("value",$value);
            })->$method(10);



       return $this;
    }


}
