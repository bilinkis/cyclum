<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id')->unsigned();
            $table->string('url');
            $table->integer('views')->unsigned();
            $table->integer('averageTime')->unsigned();
            $table->timestamps();
            
            $table->integer('group_id')->unsigned();
            
        });
        
        Schema::table('pages', function($table)
        {
            $table->foreign('group_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
