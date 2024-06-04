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
        Schema::create('product_details', function (Blueprint $table) {
            $table->increments('id_product_detail'); // PK, NOT NULL, tự động tăng
            $table->integer('id_product')->unsigned(); // FK, NOT NULL
            $table->string('unit', 50); // NOT NULL
            $table->string('origin', 50); // NOT NULL
            $table->string('country_produce', 50)->nullable(); // NULL

            $table->foreign('id_product')->references('id_product')->on('products');

        
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
        Schema::dropIfExists('product_details');
    }
};
