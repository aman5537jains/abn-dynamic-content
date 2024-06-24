<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;


use Aman5537jains\AbnCmsCRUD\ViewComponent;

abstract class LayoutViewComponent extends ViewComponent{
    public $query;
    public $results;
    public $content;

    
    function setQuery($query){
        $this->query =$query;
        return $this;
    }
    function execute(){
        $this->results = $this->query->limit(20)->get();
        return $this;
    }
    function getResults(){
        return $this->results;
    }
    function setContent($content){
        $this->content = $content;
        return $this;
    }
    function view(){
        
        return $this->content ;
    }

}
