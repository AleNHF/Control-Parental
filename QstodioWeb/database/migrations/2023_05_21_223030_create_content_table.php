<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date')->nullable();
            $table->string('name')->nullable();
            $table->string('path');
            $table->string('url');
            $table->string('type'); //PARENT NAME FOR AWS
            $table->string('contentData'); //NAME FOR AWS
            $table->boolean('capture')->nullable()->default(false);
            $table->unsignedBigInteger('children_id');        
            $table->timestamps();

            $table->foreign('children_id')->references('id')->on('children')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content');
    }
}
