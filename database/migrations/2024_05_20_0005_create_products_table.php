<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_product'); // PK, NOT NULL, tự động tăng
            $table->integer('id_third_category')->unsigned(); // FK, NOT NULL
            $table->integer('id_image_product')->unsigned(); // FK, NOT NULL
            $table->string('name', 250); // NOT NULL
            $table->string('avatar'); // NOT NULL
            $table->integer('price'); // NOT NULL
            $table->string('short_des', 500)->nullable(); // NULL
            $table->string('description', 1000)->nullable(); // NULL
            $table->integer('in_stock'); // NOT NULL
            $table->boolean('hot')->default(0); // BOOLEAN, Default = 0
            $table->integer('sold'); // NOT NULL
            $table->integer('sale')->default(0); // Default = 0
            $table->string('sick', 250)->nullable(); // NULL
            $table->string('symptom', 250)->nullable(); // NULL
            $table->boolean('hide')->default(0); // BOOLEAN, Default = 0

            // Add foreign key constraints
            $table->foreign('id_third_category')->references('id_third_category')->on('third_categories');
            $table->foreign('id_image_product')->references('id_image_product')->on('image_products');

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
        Schema::dropIfExists('products');
    }
};
