<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('hours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('actual_hours');
            $table->string('productive_hours');
            $table->integer('project_id')->unsigned();
            $table->foreign('project_id')
             ->references('id')
             ->on('projects')
             ->onDelete('cascade');
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
       Schema::drop('hours');
    }
}
