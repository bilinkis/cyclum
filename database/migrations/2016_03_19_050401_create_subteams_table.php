<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubteamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subteams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->nullable()->unsigned();
            $table->string('name');
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
        });
        
        Schema::table('subteams', function ($table) {
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subteams');
    }
}
