<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Shortcodes;

use Aman5537jains\AbnCms\Lib\Shortcode;
use Aman5537jains\AbnCms\Models\DynamicContentType;
use Aman5537jains\AbnDynamicContentPlugin\AbnDynamicContentService;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentDataAttribute;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;
use Illuminate\Support\Facades\Blade;
class DynamicContentViewShortCode extends Shortcode{



function render(){
    try{
    $name = $this->shortcode['attributes']['name'];

    if(isset($this->shortcode['attributes']['content'])){
       $service = new AbnDynamicContentService;
       $DynamicContentTypeView = DynamicContentTypeView::where("slug",$this->shortcode['attributes']['content'])->first();
       if($DynamicContentTypeView && !isset($this->shortcode['attributes']['slug'])){
            $content= $service->getContentByView($DynamicContentTypeView);
            return $content;
       }

       if($DynamicContentTypeView && isset($this->shortcode['attributes']['slug'])){
        $content= $service->getContentDeatailByView($DynamicContentTypeView,$this->shortcode['attributes']['slug']);

        return $content;
        }





    }
    return "";
    }
    catch(\Exception $e){
        dd($e);
    }
}

}
