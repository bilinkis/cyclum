<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubteamIdToTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function ($table) {
            $table->integer('subteam_id')->nullable()->unsigned();
            $table->foreign('subteam_id')
                  ->references('id')
                  ->on('subteams')
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
        Schema::table('tasks', function ($table) {
            $table->dropForeign('tasks_subteam_id_foreign');
            $table->dropColumn('subteam_id');
        });
    }
}
