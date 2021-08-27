<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublicViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('public_views', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('page', 60);
            $table->integer('post_id')->unsigned()->nullable();
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
            $table->text('post_tittle')->nullable();
            $table->bigInteger('views');

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
        Schema::dropIfExists('public_views');
    }
}
