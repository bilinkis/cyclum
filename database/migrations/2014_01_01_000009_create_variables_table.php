<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->integer('value');
            $table->timestamps();
            
            $table->integer('group_id')->unsigned();
            
        });
        
        Schema::table('variables', function($table)
        {
            $table->foreign('group_id')
                  ->references('id')
                  ->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('variables');
    }
}
