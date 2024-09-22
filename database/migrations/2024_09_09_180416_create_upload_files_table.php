<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('upload_categories');
        });

        Schema::create('upload', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('folder')->unique();
            $table->timestamps();

            $table->foreign('category_id')->on('upload_categories')->references('id');
        });

        Schema::create('upload_files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->string('origin');
            $table->string('path');
            $table->string('type');
            $table->string('ext');
            $table->integer('downloads')->default(0);
            $table->unsignedBigInteger('upload_id');
            $table->timestamps();

            $table->foreign('upload_id')->on('upload')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_files');
        Schema::dropIfExists('upload');
        Schema::dropIfExists('upload_categories');
    }
}
