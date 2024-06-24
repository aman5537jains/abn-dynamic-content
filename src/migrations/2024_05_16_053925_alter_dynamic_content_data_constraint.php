<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDynamicContentDataConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE `dynamic_content_data` ADD CONSTRAINT `dynamic_content_data_to_dynamic_content_types` FOREIGN KEY (`dynamic_content_type_id`) REFERENCES `dynamic_content_types`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
        \DB::statement("ALTER TABLE `dynamic_content_data_attributes` ADD CONSTRAINT `dynamic_content_data_attributes_to_dynamic_content_data` FOREIGN KEY (`dynamic_content_data_id`) REFERENCES `dynamic_content_data`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;");
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
