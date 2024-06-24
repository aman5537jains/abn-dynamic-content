<?php

namespace Aman5537jains\AbnDynamicContentPlugin;
use Aman5537jains\AbnDynamicContentPlugin\Models\DynamicContentTypeView;

class AbnDynamicContentService
{
    function getContentByView($DynamicContentTypeView)
    {
        $views = config("dynamic_content.views");

        $component = $views[$DynamicContentTypeView->view_type][$DynamicContentTypeView->view_name];
        $config = [];
        if ($DynamicContentTypeView->config != ""){
            $config = json_decode($DynamicContentTypeView->config, true);
        }
        $config["view"] = $DynamicContentTypeView;
        return new $component['class'](array_merge($component['config'], $config));
    }
    function getContentDeatailByView($DynamicContentTypeView, $slug)
    {
        $views = config("dynamic_content.views");
        $config = ["view" => $DynamicContentTypeView, "slug" => $slug];

        if ($DynamicContentTypeView->config != "")
            $config = array_merge($config, json_decode($DynamicContentTypeView->config, true));

        $component = $views[$DynamicContentTypeView->view_type][$DynamicContentTypeView->view_name];
        $cls = new $component['class'](array_merge($component['config'], $config));
        $cls->setQuery($cls->getQuery()->where("slug", $slug));
        return $cls;
    }
    function getContentByType($slug)
    {

        // $views = config("dynamic_content.views");

        // $component = $views[$DynamicContentTypeView->view_type][$DynamicContentTypeView->view_name];
        // return new $component(["content_type"=>$slug]);


    }
    function getContentView($slug)
    {


        // return implode("",$data);


    }

    static function submitForm()
    {
        $views = config("dynamic_content.views.FORM");
        if (request()->isMethod("POST") ||  request()->isMethod("PATCH")) {

            $DynamicContentTypeView =   DynamicContentTypeView::find(request()->get("view_id"));

            $component = $views[$DynamicContentTypeView->view_name];
            $config = [];
            if ($DynamicContentTypeView->config != "")
                $config = json_decode($DynamicContentTypeView->config, true);

            $config["view"] = $DynamicContentTypeView;
            $form = new $component['class'](array_merge($component['config'], $config));
            return $form->submit();
        }
    }
}
