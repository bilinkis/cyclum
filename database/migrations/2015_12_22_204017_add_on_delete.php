<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnDelete extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks_workers', function($table)
        {
            $table->dropForeign('tasks_workers_task_id_foreign');
            $table->dropForeign('tasks_workers_worker_id_foreign');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks_workers', function($table)
        {
            $table->dropForeign('tasks_workers_task_id_foreign');
            $table->dropForeign('tasks_workers_worker_id_foreign');
        });
    }
}
