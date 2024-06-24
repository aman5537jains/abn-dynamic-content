<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;


use Aman5537jains\AbnCmsCRUD\ViewComponent;

class PaginationViewComponent extends LayoutViewComponent{
    public $query;
    public $results;
    public $content;

     
    function execute(){
        $this->results = $this->query->paginate($this->getConfig("per_page",10));
        return $this;
    }
    function view(){
        
        return view("AbnDynamicContent::components.pagination-component",[
            "content"=>implode("",$this->content),
            'total'=>$this->results->total(),
            'pagination'=>$this->results->appends($_GET)->links()]
        ); ;
    }

}
