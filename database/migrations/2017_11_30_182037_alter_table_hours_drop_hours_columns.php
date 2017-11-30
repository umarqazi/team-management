<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableHoursDropHoursColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hours', function($table) {
            $table->dropColumn(['estimated_hours','consumed_hours']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hours', function($table) {
            $table->integer('estimated_hours');
            $table->integer('consumed_hours');
        });
    }
}
