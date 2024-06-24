<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicContentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE TABLE  `dynamic_content_types` (`id` INT(11) NOT NULL AUTO_INCREMENT , `slug` VARCHAR(255) NOT NULL , `name` VARCHAR(255) NOT NULL , `configuration` TEXT NOT NULL , `status` ENUM('1','0') NOT NULL , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_content_types');
    }
}
