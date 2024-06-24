<?php
namespace Aman5537jains\AbnDynamicContentPlugin;

use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnCms\Lib\Permission;
use Aman5537jains\AbnCms\Lib\Plugin;
use Aman5537jains\AbnCms\Lib\Sidebar\Sidebar;
use Aman5537jains\AbnCms\Lib\Sidebar\SidebarItem;
use Aman5537jains\AbnCms\Lib\Theme\ScriptLoader;
use Aman5537jains\AbnCms\Lib\Theme\StylesheetLoader;


class AbnDynamicContentPlugin extends Plugin{



    public function getName()
    {
         return "Dynamic Content";
    }
    public static function getKey()
    {
         return "AbnDynamicContent";
    }

    public function install()
    {

    }

    public function unInstall()
    {

    }

    // public static function permissions(){
    //     return new Permission(self::getKey());
    // }

    // public static function sidebar(){

    //     return new Sidebar("Dynamic Content",[
    //         new SidebarItem("Dynamic Content",route("dynamic-content"),"",function($permissions){
    //             return isset($permissions["admin-settings__view"]);
    //         }),
    //         // new SidebarItem("Colors","#1","",function($permissions){
    //         //     return isset($permissions["admin-settings__view"]);
    //         // }),
    //         // new SidebarItem("Logo","#2","",function($permissions){
    //         //     return isset($permissions["admin-settings__view"]);
    //         // })

    //     ]);
    // }

    public function onActivate()
    {

        // $menu  = AbnCms::createAdminMenu("Dynamic Contents");
        AbnCms::createAdminMenu("Dynamic Contents","dynamic-content-types.index","",0,[["module"=>"dynamic-content-types","action"=>"view"]]);

        AbnCms::addPermissions(["dynamic-content-types"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete","contents"=>"contents","views"=>"views"]]);
        AbnCms::addPermissions(["dynamic-content"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
        AbnCms::addModule("dynamicContentView",\Aman5537jains\AbnDynamicContentPlugin\Shortcodes\DynamicContentViewShortCode::class,"SHORTCODE");

        return true;
    }
    public function onInActivate()
    {
        AbnCms::removeAdminMenu("Dynamic Contents");
        AbnCms::removePermissions(["dynamic-content-types"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
        AbnCms::removePermissions(["dynamic-content"=>["view"=>"view","add"=>"add","edit"=>"edit","delete"=>"delete"]]);
        AbnCms::removeModule(\Aman5537jains\AbnDynamicContentPlugin\Shortcodes\DynamicContentViewShortCode::class);
        return true;

    }

    public function render(){




    }


}
