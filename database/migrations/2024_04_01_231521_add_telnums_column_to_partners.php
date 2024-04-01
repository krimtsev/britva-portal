<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTelnumsColumnToPartners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->after('telnum_3', function ($table) {
                $table->json('telnums')->nullable();
            });

            $table->dropColumn('telnum_1');
            $table->dropColumn('telnum_2');
            $table->dropColumn('telnum_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('telnums');

            $table->after('telnums', function ($table) {
                $table->string('telnum_1', 12)->nullable();
                $table->string('telnum_2', 12)->nullable();
                $table->string('telnum_3', 12)->nullable();
            });
        });
    }
}
