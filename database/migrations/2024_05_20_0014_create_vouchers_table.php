<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id_voucher'); // PK, NOT NULL, tự động tăng
            $table->string('name', 250); // NOT NULL, Tên mã giảm giá
            $table->string('code', 250)->unique(); // NOT NULL, Mã giảm giá (unique)
            $table->integer('number'); // NOT NULL, Số giảm giá
            $table->boolean('status')->default(0); // BOOLEAN, Default = 0
            $table->integer('count_voucher')->unsigned()->default(0); // NOT NULL, Số lượt dùng, default = 0
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
        Schema::dropIfExists('vouchers');
    }
}
