<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDynamicContentTypeViewsDefaultViewName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD `view_type` ENUM('LIST','VIEW','FORM') NOT NULL AFTER `dynamic_content_type_id`");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
