<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 20);
            $table->string('password');
            $table->string('nickname');
            $table->string('head_img')->nullable();
            $table->string('email')->nullable();
            $table->string('phone', 11)->nullable();
            $table->string('remember_token')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('supper_admin')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
