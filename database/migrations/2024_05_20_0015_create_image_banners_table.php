<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('image_banners', function (Blueprint $table) {
            $table->increments('id_image_banner'); // PK, NOT NULL, tự động tăng
            $table->string('image', 250); // NOT NULL, Hình ảnh banner
            $table->boolean('position')->default(0); // BOOLEAN, Default = 0

        });
    }

    /**
     * Reverse the migrations.
     *
     *
     */
    public function down()
    {
        Schema::dropIfExists('image_banners');
    }
}
