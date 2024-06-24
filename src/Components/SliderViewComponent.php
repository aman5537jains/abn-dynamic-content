<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\Theme\ScriptLoader;
use Aman5537jains\AbnCmsCRUD\ViewComponent;

class SliderViewComponent extends LayoutViewComponent{
    public $query;
    public $results;
    public $content;
    function registerJsComponent(){
        return "(component,config)=>{
            new Swiper($(component).find('.blog-slider'), {    
                slidesPerView: 1,
                spaceBetween: 30,
                speed: 600,
                loop: true,
                breakpoints: {
                    1199: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    991: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    767: {
                        slidesPerView: 1,
                        spaceBetween: 15,
                    }
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                autoplay: {
                    delay: 8000,
                },
            });
        }";
    }

    function js(){
        return (new ScriptLoader(asset('public/vendor/swiper/swiper-bundle.min.js')))->raw(true)->render();
    }
    function execute(){
        $this->results = $this->query->limit($this->getConfig("limit",10))->get();
        return $this;
    }
    

    function view(){
        
        return view("AbnDynamicContent::components.slider-component",[
            "contents"=>$this->content,
            'total'=>$this->results->count()
           ]
        ); ;
    }

}
