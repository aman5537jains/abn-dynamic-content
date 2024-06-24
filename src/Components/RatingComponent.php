<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;


use Aman5537jains\AbnCmsCRUD\ViewComponent;

class RatingComponent extends ViewComponent{

    function view(){
       
        $stars='';
        for($i=1;$i<=$this->getValue();$i++)
         {
            // $stars .= ' <li><img src="http://localhost/ebyoon_web/public/assets-low/img/star.svg"></li>';
            $stars .= ' <li><img src="'.asset("public/assets-low/img/star.svg").'"></li>';
        }
        // for($i=1;$i<=5-$this->getValue();$i++)
        //  {
        //     $stars .= ' <li><img src="http://localhost/ebyoon_web/public/assets-low/img/star.svg"></li>';
        // }
        
        return  "<ul>$stars</ul>";
    }

}
