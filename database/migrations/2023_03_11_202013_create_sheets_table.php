<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('spreadsheet_id');
            $table->string('spreadsheet_name');
            $table->string('spreadsheet_range');
            $table->string('table_header')->nullable();
            $table->boolean('is_published')->default(1);
            $table->unsignedBigInteger('user_id');
            $table->string('slug')->unique();
            $table->timestamps();

            $table->index('user_id', 'sheet_user_idx');
            $table->foreign('user_id', 'sheet_user_fk')->on('users')->references('id');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sheets');
    }
}
