<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTasksAddEnumColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            /*Task ENUM Fields are being added in this migration which were previously removed*/
            $table->enum('types', ['New Feature','Bug','Improvement','Task']);
            $table->enum('priority', ['Blocker','Minor','Major','Trivial','Critical']);
            $table->enum('workflow', ['Todo','In Progress','In QA','Completed']);
            $table->enum('component', ['Web','Android','IOS']);
            /*Task ENUM Fields Starts*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn(['types','priority','workflow','component']);
        });
    }
}
