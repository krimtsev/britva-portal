<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientsColumnstoYclientsBranchReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yclients_branch_report', function (Blueprint $table) {
            // Всего клиентов
            $table->integer('total_client')->default(0);

            // Вернувшиеся (постоянные) клиенты
            $table->integer('return_client')->default(0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yclients_branch_report', function (Blueprint $table) {
            $table->dropColumn('total_client');
            $table->dropColumn('return_client');
        });
    }
}
