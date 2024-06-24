<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;


use Aman5537jains\AbnCmsCRUD\ViewComponent;

class PaginateViewComponent extends ViewComponent{



    function view(){
        $height = $this->getConfig("height",100);
        $width = $this->getConfig("width",100);
        if($this->getValue()==''){
            return "No image";
        }
        // return $height;
        return  "<img src='".url($this->getValue())."' height='$height' width='$width'  />";
    }

}
