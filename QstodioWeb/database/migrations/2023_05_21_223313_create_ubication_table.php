<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUbicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ubication', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('coordinates');
            $table->time('time');
            $table->date('date');
            $table->unsignedBigInteger('children_id');

            $table->foreign('children_id')
            ->references('id')->on('children')->onDelete('cascade');
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
        Schema::dropIfExists('ubication');
    }
}
