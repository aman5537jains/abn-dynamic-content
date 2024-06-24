<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDynamicContentDataAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       \DB::statement("CREATE TABLE `dynamic_content_data_attributes` (`id` INT(11) NOT NULL AUTO_INCREMENT , `dynamic_content_data_id` INT(11) NOT NULL , `name` VARCHAR(255) NULL , `value` TEXT NULL , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_content_data_attributes');
    }
}
