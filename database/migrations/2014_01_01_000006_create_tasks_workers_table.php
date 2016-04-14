<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks_workers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->integer('task_id')->unsigned();
            $table->integer('worker_id')->unsigned();
            
            $table->timestamps();
        });
        
        Schema::table('tasks_workers', function($table)
        {
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->foreign('worker_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks_workers');
    }
}
