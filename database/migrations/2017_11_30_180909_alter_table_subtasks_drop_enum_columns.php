<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSubtasksDropEnumColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subtasks', function($table) {
            $table->dropColumn(['priority','Workflow']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subtasks', function($table) {
            $table->enum('priority', ['Blocker','Minor','Major','Trivial','Critical']);
            $table->enum('Workflow', ['Todo','In Progress','In QA','Completed']);
        });
    }
}
