<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('bill_details', function (Blueprint $table) {
            $table->increments('id_bill_detail'); // PK, NOT NULL, tự động tăng
            $table->integer('id_bill')->unsigned(); // FK, NOT NULL, Mã đơn hàng
            $table->integer('id_product')->unsigned(); // FK, NOT NULL, Mã sản phẩm
            $table->integer('quantity')->unsigned(); // NOT NULL, Số lượng sản phẩm
            $table->integer('total_amount'); // NOT NULL, Tổng tiền
            $table->timestamp('created_at')->useCurrent(); // Ngày tạo tài khoản
            $table->timestamp('updated_at')->nullable(); // Ngày cập nhật, có thể là null

            // Add foreign key constraint
            $table->foreign('id_bill')->references('id_bill')->on('bills');
            $table->foreign('id_product')->references('id_product')->on('products');
      
        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('bill_details');
    }
}
