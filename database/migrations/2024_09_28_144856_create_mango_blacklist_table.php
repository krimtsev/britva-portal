<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMangoBlacklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mango_blacklist', function (Blueprint $table) {
            $table->id();
            $table->integer('number_id');
            $table->string('number')->unique();
            $table->string('number_type')->nullable();
            $table->string('comment')->nullable();
            $table->boolean('is_disabled')->default(0);
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
        Schema::dropIfExists('mango_blacklist');
    }
}
