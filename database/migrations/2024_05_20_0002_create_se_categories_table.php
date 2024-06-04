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
        Schema::create('se_categories', function (Blueprint $table) {
            $table->increments('id_se_category'); // PK, NOT NULL, Tự tăng
            $table->integer('id_category')->unsigned(); // FK, NOT NULL
            $table->string('name', 250); // NOT NULL
            $table->string('avatar', 250); // NOT NULL
            $table->boolean('hide')->default(0); // BOOLEAN, Default = 0

            // Add foreign key constraint if necessary
            $table->foreign('id_category')->references('id_category')->on('category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('se_categories');
    }
};
