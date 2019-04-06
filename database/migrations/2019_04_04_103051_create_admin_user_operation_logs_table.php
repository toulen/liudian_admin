<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUserOperationLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_user_operation_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('admin_user_id');
            $table->string('operation_name')->default('创建');
            $table->string('target_class')->nullable();
            $table->bigInteger('target_id');
            $table->string('operation_intro')->nullable();
            $table->text('operation_data')->nullable();
            $table->timestamp('operation_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_user_operation_logs');
    }
}
