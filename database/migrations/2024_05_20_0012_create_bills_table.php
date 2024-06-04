<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id_bill'); // PK, NOT NULL, tự động tăng
            $table->integer('id_user')->unsigned(); // FK, NOT NULL, Mã khách hàng
            $table->integer('id_customer')->unsigned(); // FK, NOT NULL, Mã khách hàng
           
            $table->boolean('transport_status')->default(0); // BOOLEAN, Default = 0
            $table->boolean('payment_status')->default(0); // BOOLEAN, Default = 0
            $table->string('address', 250); // NOT NULL, Địa chỉ giao hàng
            $table->string('voucher', 250)->nullable(); // NULL, Voucher sử dụng
            $table->timestamp('created_at')->useCurrent(); // Ngày tạo tài khoản
            $table->timestamp('updated_at')->nullable(); // Ngày cập nhật, có thể là null

            // Add foreign key constraints
            $table->foreign('id_user')->references('id_user')->on('users');
            $table->foreign('id_customer')->references('id_customer')->on('customers');
          

        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('bills');
    }
}
