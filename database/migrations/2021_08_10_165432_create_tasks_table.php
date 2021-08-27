<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->integer('id')->autoIncrement()->unsigned();
            $table->string('name');
            $table->string('description');
            $table->tinyInteger('status_id')->unsigned()->default(1);
            $table->foreign('status_id')
                ->references('id')
                ->on('statuses');
            $table->integer('task_id')->unsigned()->nullable();
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->date('date_to_end');
            $table->timestamps(); 
            $table->timestamp('end_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
