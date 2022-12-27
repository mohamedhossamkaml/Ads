<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // CREATING TABLE
        Schema::create('ads', function (Blueprint $table) {

            $table->id();
            $table->string('title')->unique();
            $table->mediumText('description')->nullable();
            $table->string('type')->default('free');
            $table->unsignedBigInteger('advertiserid')->unsigned();
            $table->unsignedBigInteger('category_id')->unsigned();
            $table->unsignedBigInteger('tags_id')->unsigned();
            $table->date('start_date')->unique();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('advertiserid')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->foreign('tags_id')->references('id')->on('tags');

        // FOREIGN KEY CONSTRAINT

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
