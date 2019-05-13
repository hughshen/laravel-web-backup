<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('term', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('parent')->unsigned()->default(0);
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->string('taxonomy')->default('category');
            $table->text('meta_data')->nullable();
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('term');
    }
}
