<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('parent')->unsigned()->default(0);
            $table->integer('author')->unsigned()->default(0);
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('slug');
            $table->string('type')->default('post');
            $table->text('meta_data')->nullable();
            $table->string('status')->default('publish');
            $table->integer('created_at')->unsigned();
            $table->integer('updated_at')->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post');
    }
}
