<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProjectDropColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['teamlead', 'developer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (! Schema::hasColumn('projects', 'teamlead') && ! Schema::hasColumn('projects', 'developer'))
        {
            Schema::table('projects', function (Blueprint $table)
            {
                $table->string('teamlead');
                $table->string('developer');
            });
        }
    }
}
