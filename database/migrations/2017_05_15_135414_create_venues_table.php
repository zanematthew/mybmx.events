<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            // Specific fields
            $table->string('name');
            $table->string('district');
            $table->string('usabmx_id');
            $table->string('website');
            $table->string('image_uri');
            $table->text('description');
            $table->string('streetAddress');
            $table->string('zipCode');
            $table->string('lat');
            $table->string('long');
            $table->string('email');
            $table->string('primary_contact');
            $table->string('phone_number');

            // Relationship
            $table->integer('city_id')->unsigned();
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venues');
    }
}
