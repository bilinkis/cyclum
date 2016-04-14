<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id')->unsigned();
            $table->integer('task_id')->unsigned()->nullable();
            $table->integer('project_id')->unsigned()->nullable();
            $table->timestamps();
        });
        
        Schema::table('groups', function(Blueprint $table)
        {
            $table->foreign('task_id')->references('id')
            ->on('tasks')->onDelete('cascade');
                  
            $table->foreign('project_id')->references('id')
            ->on('users')->onDelete('cascade');
        });      
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groups');
    }
}
