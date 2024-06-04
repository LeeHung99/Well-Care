<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id_post'); // PK, NOT NULL, tự động tăng
            $table->integer('id_user')->unsigned(); // FK, NOT NULL
            $table->integer('id_article_category')->unsigned(); // FK, NOT NULL
            $table->string('title', 250); // NOT NULL
            $table->string('short_des', 500)->nullable(); // NULL
            $table->string('description', 1000)->nullable(); // NULL
            $table->string('avatar', 250)->nullable(); // NULL
            $table->timestamp('created_at')->useCurrent(); // Current_timestamp ()
            $table->timestamp('updated_at')->nullable(); // NULL

            // Add foreign key constraints
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_article_category')->references('id_article_category')->on('article_categories');

          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
