<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_experience', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string('company', 80)->nullable();
            $table->string('occupation', 60);
            $table->date('from');
            $table->date('to')->nullable();
            $table->string('logo')->nullable();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_experience');
    }
}
