<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHoursRenameHoursColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function ($table) {
            $table->renameColumn('actual_hours', 'consumed_hours');
            $table->renameColumn('productive_hours', 'estimated_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hours', function ($table) {
            $table->renameColumn('consumed_hours', 'actual_hours');
            $table->renameColumn('estimated_hours', 'productive_hours');
        });
    }
}
