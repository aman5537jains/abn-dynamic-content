<?php

use Aman5537jains\AbnCms\Editor\Editor;
use Aman5537jains\AbnCms\Lib\AbnCms;
use Aman5537jains\AbnDynamicContentPlugin\AbnDynamicContentService;
use Aman5537jains\AbnDynamicContentPlugin\Controllers\DyanamicContentController;
 use Aman5537jains\AbnDynamicContentPlugin\Controllers\DyanamicContentTypeController;
use Aman5537jains\AbnDynamicContentPlugin\Controllers\DyanamicContentTypeViewController;
use \Illuminate\Support\Facades\Route;
Route::group(["middleware"=>["web"]],function(){
    Route::any("content/{dynamic_type}",[DyanamicContentTypeController::class,"renderContent"])->name("renderContent");;
    Route::any("content/{dynamic_type}/{slug}",[DyanamicContentTypeController::class,"renderContentSlug"])->name("renderContentSlug");
    Route::post("submit-content-form",function(){
        return AbnDynamicContentService::submitForm();
    })->name("submit-content-form");
   
});
Route::group(["middleware"=>["web","auth"],"prefix"=>"cpadmin"],function(){

    // Route::get("dynamic-content-types",function(){
    //     return "test 123";
    // });
     DyanamicContentTypeController::resource();

     DyanamicContentController::resource();
     DyanamicContentTypeViewController::resource();


});
Route::any('/content/{content_type}', function (Request $request){
    $content=(new Editor())->getPage(request("content_type",""))->render();
   
    return AbnCms::getActiveTheme()->setPageContent($content)->render();
});
Route::any('/content/{content_type}/{slug}', function (Request $request){
    $content=(new Editor())->getPage(request("content_type",""))->render();
   
    return AbnCms::getActiveTheme()->setPageContent($content)->render();
});
