<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('product_session', function (Blueprint $table) {
            $table->string('phone_number')->nullable()->after('price'); 
            $table->string('payment_status')->nullable()->after('phone_number');
            $table->string('address')->nullable()->after('payment_status');
            $table->string('voucher')->nullable()->after('address');
            $table->text('ghichu')->nullable()->after('voucher');
            $table->decimal('total_amount', 10, 2)->nullable()->after('ghichu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_session', function (Blueprint $table) {
            //
        });
    }
};
