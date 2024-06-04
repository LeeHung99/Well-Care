<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
    
     */
    public function up()
    {
        Schema::create('image_products', function (Blueprint $table) {
            $table->increments('id_image_product'); // PK, NOT NULL, tự động tăng
            $table->string('image_1', 250)->nullable(); // NULL
            $table->string('image_2', 250)->nullable(); // NULL
            $table->string('image_3', 250)->nullable(); // NULL
            $table->string('image_4', 250)->nullable(); // NULL

            // Add timestamps (created_at and updated_at columns)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('image_products');
    }
};
