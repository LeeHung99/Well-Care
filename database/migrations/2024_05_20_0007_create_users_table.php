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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id_user'); // PK, NOT NULL, tự động tăng
            $table->string('name', 250); // NOT NULL
            $table->string('pass', 250)->nullable(); // NULL
            $table->integer('phone'); // NOT NULL
            $table->string('email', 250)->nullable(); // NULL
            $table->boolean('gender')->default(0); // BOOLEAN, Default = 0
            $table->date('date')->nullable(); // NULL
            $table->boolean('role')->default(0); // BOOLEAN, Default = 0 (0 = khách hàng, 1 = Admin, 2 = Nhân viên, 3 = Người viết bài)
            $table->timestamp('created_at')->useCurrent(); // Current_timestamp ()
            $table->timestamp('updated_at')->nullable(); // NULL

        
        });
    }

    /**
     * Reverse the migrations.
     *
     * 
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
