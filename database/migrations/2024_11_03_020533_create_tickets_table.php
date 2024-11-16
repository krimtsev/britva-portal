<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('partner_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('state')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->foreign('category_id')->on('tickets_categories')->references('id');

            $table->index('partner_id');
            $table->foreign('partner_id')->on('partners')->references('id');

            $table->index('user_id');
            $table->foreign('user_id')->on('users')->references('id');
        });

        Schema::create('tickets_messages', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();
            $table->softDeletes();

            $table->index('ticket_id');
            $table->foreign('ticket_id')->on('tickets')->references('id');

            $table->index('user_id');
            $table->foreign('user_id')->on('users')->references('id');
        });

        Schema::create('tickets_files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name');
            $table->string('origin');
            $table->string('path');
            $table->string('type');
            $table->string('ext');
            $table->unsignedBigInteger('ticket_message_id');
            $table->timestamps();

            $table->index('ticket_message_id');
            $table->foreign('ticket_message_id')->on('tickets_messages')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets_messages');
        Schema::dropIfExists('tickets_categories');
        Schema::dropIfExists('tickets');
    }
}
