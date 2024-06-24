<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDynamicContentTypeViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      \DB::statement("CREATE TABLE `dynamic_content_type_views` (`id` INT(11) NOT NULL AUTO_INCREMENT , `dynamic_content_type_id` INT(11) NOT NULL,`title` VARCHAR(255) NOT NULL , `template` TEXT NOT NULL , `config` TEXT NULL , `updated_at` DATETIME NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
        \DB::statement("ALTER TABLE `dynamic_content_type_views` ADD CONSTRAINT `dynamic_content_type_views_to_dynamic_content_types` FOREIGN KEY (`dynamic_content_type_id`) REFERENCES `dynamic_content_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dynamic_content_type_views');
    }
}
