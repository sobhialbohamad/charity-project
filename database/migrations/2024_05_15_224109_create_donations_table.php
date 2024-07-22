<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
          $table->id();
            $table->bigInteger('charities_id')->unsigned()->nullable();
            $table->string('name');
            $table->Text('phone');
            $table->text('image');
            $table->integer('amount');
            $table->integer('number_bill');
            $table->timestamps();

              $table->foreign('charities_id')->references('id')->on('charities')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
};
