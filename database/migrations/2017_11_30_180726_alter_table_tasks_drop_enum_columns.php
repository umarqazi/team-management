<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTasksDropEnumColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function($table) {
            $table->dropColumn(['types','priority','workflow','component']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function($table) {
            $table->enum('types', ['New Feature','Bug','Improvement','Task']);
            $table->enum('priority', ['Blocker','Minor','Major','Trivial','Critical']);
            $table->enum('workflow', ['Todo','In Progress','In QA','Completed']);
            $table->enum('component', ['Web','Android','IOS']);
        });
    }
}
