<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->integer('position')->default(0);
            $table->bigInteger('parent_id')
                  ->nullable()
                  ->unsigned();
            $table->boolean('done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('todos')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
