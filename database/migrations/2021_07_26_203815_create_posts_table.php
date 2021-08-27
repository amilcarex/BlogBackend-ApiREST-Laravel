<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->text('slug');
            $table->text('tittle');
            $table->longText('content');
            $table->string('image', 300)->nullable();
            $table->integer('author_id')->unsigned()->nullable();
            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
            $table->string('author_name', 60);
            $table->tinyInteger('visibility')->default(1)->unsigned();
            $table->foreign('visibility')->references('id')->on('visibility_options');
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
        Schema::dropIfExists('posts');
    }
}
