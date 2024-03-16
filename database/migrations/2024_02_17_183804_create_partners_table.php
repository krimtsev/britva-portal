<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();

            // Имя партнера (ИП)
            $table->string('organization')->nullable();

            // Название филиала
            $table->string('name')->unique();

            // Номер договора
            $table->string('contract_number', 50)->unique();

            // Почта
            $table->string('email')->nullable();

            // Телефонные номера
            $table->string('telnum_1', 12)->nullable();
            $table->string('telnum_2', 12)->nullable();
            $table->string('telnum_3', 12)->nullable();

            // Yclients ID
            $table->string('yclients_id')->nullable();

            // Адрес организации
            $table->string('address')->nullable();

            // Дата подписания договора
            $table->date('start_at')->nullable();

            $table->timestamps();

            /*$table->index('id', 'partner_user_idx');*/
            /*$table->foreign('id', 'partner_user_fk')->on('partners')->references('id');*/

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
        Schema::dropIfExists('partners');
    }
}
