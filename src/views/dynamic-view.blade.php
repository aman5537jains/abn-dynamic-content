


<div class="dashHead">
    <div class="dashHead-left">
       <h4 class="dashTitle">{{$module_title}}</h4>
    </div>
    <div class="dashHead-right">
       <div class="dashHead-action">
        @if($controller->hasPermission("add",$module,false) &&  $canAdd)
        <a class="buttons dbtn-secondary" href="{{$controller->action("create",["view_type"=>"VIEW"])}}"><i class="fas fa-plus-circle"></i>{{'Add '.\Illuminate\Support\Str::singular($module_title)." View"}}</a>
      @endif
          @if($controller->hasPermission("add",$module,false) &&  $canAdd)
            <a class="buttons dbtn-secondary" href="{{$controller->action("create",["view_type"=>"LIST"])}}"><i class="fas fa-plus-circle"></i>{{'Add '.\Illuminate\Support\Str::singular($module_title)." List"}}</a>
          @endif
          @if($controller->hasPermission("add",$module,false) &&  $canAdd)
          <a class="buttons dbtn-secondary" href="{{$controller->action("create",["view_type"=>"FORM"])}}"><i class="fas fa-plus-circle"></i>{{'Add '.\Illuminate\Support\Str::singular($module_title)." Form"}}</a>
        @endif
       </div>
    </div>
 </div>
 @if(isset($search))
 {!! $search->render() !!}
 @endif
 {!! $table->render() !!}




<?php
// echo \App\Lib\CRUD\CrudService::js();
?>

