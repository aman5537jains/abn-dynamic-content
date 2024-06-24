<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDynamicContentDataSlug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // \DB::statement("ALTER TABLE `dynamic_content_types` ADD `slug` VARCHAR(255) NOT NULL AFTER `id`;");
        // \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD `slug` VARCHAR(255) NOT NULL AFTER `id`;");


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
