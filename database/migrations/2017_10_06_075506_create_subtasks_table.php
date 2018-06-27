<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubtasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subtasks', function (Blueprint $table) {
            $table->increments('id');

            /* Relations Table Field */
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->integer('task_id')->unsigned();
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');

            $table->string('name');
            $table->text('description');
            $table->integer('percentDone');
            $table->date('duedate');
            $table->string('reporter');
            $table->string('follower');
            $table->string('tags');
            $table->enum('priority', ['Blocker','Minor','Major','Trivial','Critical']);
            $table->enum('Workflow', ['Todo','In Progress','In QA','Completed']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subtasks');
    }
}
