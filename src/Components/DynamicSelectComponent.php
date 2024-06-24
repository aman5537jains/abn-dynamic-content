<?php

namespace Aman5537jains\AbnDynamicContentPlugin\Components;;

use Aman5537jains\AbnCmsCRUD\Components\SelectComponent;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentData;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentType;

class DynamicSelectComponent extends SelectComponent{


    function init()
    {
        $dbConfig = $this->getConfig("db_config");

        if(isset($dbConfig->field_config) && !empty($dbConfig->field_config)){
            $config = json_decode($dbConfig->field_config);
            if(isset($config->type)){
                $contentID = DynamicContentType::where("slug",$config->type)->select("id")->first();
                $title = "title";
                $id = "id";
                if(isset($config->title_column)){
                    $title = $config->title_column;
                }
                 $options  = DynamicContentData::where("dynamic_content_type_id",$contentID->id)->pluck($title,$id);

                 $this->setConfig("options",$options);
            }
        }


    }
}
