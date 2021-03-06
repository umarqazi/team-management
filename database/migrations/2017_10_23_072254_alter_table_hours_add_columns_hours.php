<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHoursAddColumnsHours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->integer('estimated_hours');
            $table->integer('internal_hours');
            $table->integer('consumed_hours');
            /* Now Fields for Task And Subtask Tables Starts */
            /* Check whether nullable() works fine for foreign key on Tasks and Subtasks OR Not */
            $table->integer('task_id')->unsigned()->nullable();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');

            $table->integer('subtask_id')->unsigned()->nullable();
            $table->foreign('subtask_id')
                ->references('id')
                ->on('subtasks')
                ->onDelete('cascade');
            /*Now Fields for Task And Subtask Tables Ends*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hours', function (Blueprint $table) {
            $table->dropColumn('estimated_hours');
            $table->dropColumn('internal_hours');
            $table->dropColumn('consumed_hours');

            $table->dropForeign('hours_task_id_foreign');
            $table->dropColumn('task_id');

            $table->dropForeign('hours_subtask_id_foreign');
            $table->dropColumn('subtask_id');
        });
    }
}
