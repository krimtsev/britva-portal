<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateYclientsBranchReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yclients_branch_report', function (Blueprint $table) {
            $table->id();

            // ID партнера
            $table->string('company_id');

            // ID сотрудника
            $table->integer('staff_id');

            // Имя сотрудника
            $table->string('name');

            // Профессия сотрудника
            $table->string('specialization');

            $table->integer('average_sum');

            $table->integer('fullnesss');

            $table->integer('new_client');

            $table->string('income_total');

            $table->string('income_goods');

            $table->integer('comments_total');

            $table->integer('comments_best');

            $table->integer('loyalty');

            $table->integer('sales');

            $table->integer('additional_services');

            $table->integer('sum');

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
        Schema::dropIfExists('yclients_branch_report');
    }
}
