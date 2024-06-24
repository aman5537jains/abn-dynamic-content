<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicFormData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("CREATE TABLE  `dynamic_content_data` (`id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(255)  NULL, `email` VARCHAR(255)  NULL, `phone_no` VARCHAR(255)  NULL, `address` VARCHAR(255)  NULL, `icon` VARCHAR(255)  NULL,`title` VARCHAR(255)  NULL , `description` TEXT  NULL , `image` VARCHAR(255)  NULL , `url` VARCHAR(255)  NULL , `start_date_time` DATETIME  NULL , `end_date_time` DATETIME  NULL , `dynamic_content_type_id` INT(11) NOT NULL , `status` ENUM('1','0') NOT NULL , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_content_data');
    }
}
