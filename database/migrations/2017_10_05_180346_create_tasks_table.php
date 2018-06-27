<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            /* Relations Table Field Starts */
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->onDelete('cascade');

            /* Relations Table Field Ends */

            $table->string('name');
            $table->string('key');
            $table->text('description');

            $table->integer('percentDone');
            $table->date('duedate');
            $table->integer('reporter');
            $table->integer('follower');
            $table->string('tags');

            /*Task ENUM Fields Starts*/
            $table->enum('types', ['New Feature','Bug','Improvement','Task']);
            $table->enum('priority', ['Blocker','Minor','Major','Trivial','Critical']);
            $table->enum('workflow', ['Todo','In Progress','In QA','Completed']);
            $table->enum('component', ['Web','Android','IOS']);
            /*Task ENUM Fields Starts*/

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
        Schema::drop('tasks');
    }
}
