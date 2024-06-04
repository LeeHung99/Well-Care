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
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id_comment'); // PK, NOT NULL, tự động tăng
            $table->integer('id_user')->unsigned(); // FK, NOT NULL
            $table->integer('id_product')->unsigned(); // FK, NOT NULL
            $table->integer('id_post')->unsigned(); // FK, NOT NULL
            $table->string('content', 500); // NOT NULL
            $table->timestamp('created_at')->useCurrent(); // Current_timestamp ()
            $table->timestamp('updated_at')->nullable(); // NULL

            // Add foreign key constraints
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_product')->references('id_product')->on('products');
            $table->foreign('id_post')->references('id_post')->on('posts');

        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
