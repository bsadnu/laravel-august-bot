<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribers', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('telegram_id')->unsigned()->comment('Telegram ID');
            $table->string('username', 128)->nullable()->comment('Telegram username');
            $table->string('first_name', 128)->nullable()->comment('Telegram first name');
            $table->string('last_name', 128)->nullable()->comment('Telegram last name');
            $table->timestamps();

            $table->unique('telegram_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('subscribers');
    }
}
