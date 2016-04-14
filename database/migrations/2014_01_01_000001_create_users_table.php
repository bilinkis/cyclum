<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id')->unsigned()->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('teamName');
            $table->boolean('mail_validated');
            $table->string('rank');//leader o worker
            $table->integer('leader_id')->unsigned()->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('leader_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
