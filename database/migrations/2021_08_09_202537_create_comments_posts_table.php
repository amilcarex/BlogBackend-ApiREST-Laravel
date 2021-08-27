<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_post', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->integer('post_id')->unsigned();
            $table->integer('comment_id')->unsigned();
            $table->timestamps();

            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade');
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments_posts');
    }
}
