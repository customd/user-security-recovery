<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRecoveriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_recoveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('recovery_key_id')->nullable();
            $table->string('type'); // Enum ie Secret Question 1, Secret Question 2/ Phone Number / DL Number etc etc
            $table->string('question')->nullable();
            $table->string('answer', 250);
            $table->timestamps();

            $table->foreign('recovery_key_id')->references('id')->on('recovery_keys');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('user_recoveries');
    }
}
