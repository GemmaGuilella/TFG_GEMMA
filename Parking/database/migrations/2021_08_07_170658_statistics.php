<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Statistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->integer('cars');
            $table->string('spaces');
            $table->integer('00-01');
            $table->integer('01-02');
            $table->integer('02-03');
            $table->integer('03-04');
            $table->integer('04-05');
            $table->integer('05-06');
            $table->integer('06-07');
            $table->integer('07-08');
            $table->integer('08-09');
            $table->integer('09-10');
            $table->integer('10-11');
            $table->integer('11-12');
            $table->integer('12-13');
            $table->integer('13-14');
            $table->integer('14-15');
            $table->integer('15-16');
            $table->integer('16-17');
            $table->integer('17-18');
            $table->integer('18-19');
            $table->integer('19-20');
            $table->integer('20-21');
            $table->integer('21-22');
            $table->integer('22-23');
            $table->integer('23-00');
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
        Schema::drop('statistics');
    }
}
