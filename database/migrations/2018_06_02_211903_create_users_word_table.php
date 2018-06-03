<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_word', function (Blueprint $table) {
            $table->increments('id', true);
            $table->integer('users_session_id')->index();
            $table->integer('word_id')->index();
            $table->string('scramble', 255)->default('Notset');
            $table->string('word', 255)->default('Notset');
            $table->enum('status', ['Correct', 'Wrong'])->default('Wrong');
            $table->integer('score');
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
        Schema::dropIfExists('users_word');
    }
}