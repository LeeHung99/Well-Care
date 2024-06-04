<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id_customer'); // PK, NOT NULL, tự động tăng
            $table->string('name', 250); // NOT NULL
            $table->string('phone', 11); // NOT NULL
            $table->string('email', 250); // NOT NULL
            $table->string('address', 500); // NOT NULL
            $table->timestamp('created_at')->useCurrent(); // Ngày tạo tài khoản
            $table->timestamp('updated_at')->nullable(); // Ngày cập nhật, có thể là null

        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
