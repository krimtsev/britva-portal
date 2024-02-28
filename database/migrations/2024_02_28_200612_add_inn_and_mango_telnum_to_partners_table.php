<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInnAndMangoTelnumToPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            // INN
            $table->after('organization', function ($table) {
                $table->string('inn', 12);
            });

            // Yclients номер телефона филиала
            $table->after('yclients_id', function ($table) {
                $table->string('mango_telnum', 12)->nullable();
            });
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
            $table->dropColumn('inn');
            $table->dropColumn('mango_telnum');
        });
    }
}
