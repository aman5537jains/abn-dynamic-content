<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDynamicContentDataAddSlugDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `dynamic_content_data` ADD `slug` VARCHAR(255) NOT NULL AFTER `id`");
        \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD `is_default` ENUM('0','1') NOT NULL AFTER `config`");
        \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD `view_type` ENUM('LIST','SINGLE') NULL AFTER `config`");
        \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD `view_name` VARCHAR(255)  NULL AFTER `config`");

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
