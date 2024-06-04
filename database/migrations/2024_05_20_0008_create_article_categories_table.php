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
        Schema::create('article_categories', function (Blueprint $table) {
            $table->increments('id_article_category'); // PK, NOT NULL, tự động tăng
            $table->string('name', 250); // NOT NULL
            $table->boolean('hide')->default(0); // BOOLEAN, Default = 0 (0 = Ẩn, 1 = Hiện)

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
        Schema::dropIfExists('article_categories');
    }
};
