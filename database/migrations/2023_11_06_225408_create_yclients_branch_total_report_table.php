<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYclientsBranchTotalReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yclients_branch_total_report', function (Blueprint $table) {
            $table->id();

            // ID партнера
            $table->string('company_id');

            $table->integer('average_sum');

            $table->integer('fullnesss');

            $table->integer('new_client');

            $table->integer('income_total');

            $table->integer('loyalty');

            $table->integer('additional_services');

            $table->integer('sales');

            $table->integer('income_goods');

            $table->integer('comments_total');

            $table->integer('comments_best');

            $table->date('start_date');

            $table->date('end_date');

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
        Schema::dropIfExists('yclients_branch_total_report');
    }
}
