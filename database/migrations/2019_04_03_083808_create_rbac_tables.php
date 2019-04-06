<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('route_name')->nullable();
            $table->boolean('nav_show')->default(true);
            $table->integer('parent_id')->default(0);
            $table->integer('left_key')->default(0);
            $table->integer('right_key')->default(0);
            $table->integer('depth')->default(0);
            $table->string('icon')->default('navicon');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('admin_rbac_roles', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id')->default(0);
            $table->integer('left_key')->default(0);
            $table->integer('right_key')->default(0);
            $table->integer('depth')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('admin_rbac_role_permissions', function (Blueprint $table){
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->timestamp('created_at');
        });

        Schema::create('admin_rbac_user_roles', function (Blueprint $table){
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('role_id');
            $table->timestamp('created_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_rbac_permissions');
        Schema::dropIfExists('admin_rbac_roles');
        Schema::dropIfExists('admin_rbac_role_permissions');
        Schema::dropIfExists('admin_rbac_user_roles');
    }
}
