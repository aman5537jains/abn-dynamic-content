<?php
namespace Aman5537jains\AbnDynamicContentPlugin\Shortcodes;

use Aman5537jains\AbnCms\Lib\ShortCode;
use Aman5537jains\AbnCms\Models\DynamicContentType;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;

class DynamicContentShortCode extends ShortCode{



function render(){
    $name = $this->shortcode['attributes']['name'];

    if(isset($this->shortcode['attributes']['content'])){
        $type= DynamicContentType::where("name",$this->shortcode['attributes']['content'])->first();
        $banners =  DynamicContentData::where("dynamic_content_type_id",$type->id)->get();
        $data ="";
        foreach($banners as $banner){
            $data.=$banner->description;

        }

        return $data;
    }
    return "";
}

}
