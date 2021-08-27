<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string('webTittle', 50);
            $table->string('homeVideo')->nullable();
            $table->tinyInteger('localVideo')->default(1);
            $table->string('adminEmail', 100);
            $table->tinyInteger('allowRegister')->default(0);
            $table->string('pinnedOrder', 5)->default('Desc');
            $table->integer('defaultRole')->unsigned();
            $table->foreign('defaultRole')->references('id')->on('roles');
            $table->string('bgLogin', 400);
            $table->string('bgRegister', 400);
            $table->integer('maxPostsToDisplay')->default(10)->unsigned();
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
        Schema::dropIfExists('general_settings');
    }
}
