<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_settings', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string('facebook', 100)->nullable();
            $table->string('twitter', 100)->nullable();
            $table->string('linkedIn', 100)->nullable();
            $table->string('youtube', 100)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('github', 100)->nullable();
            $table->string('twitch', 100)->nullable();
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
        Schema::dropIfExists('social_settings');
    }
}
