<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('address_of_the_recipient')->nullable();
            $table->string('channel')->nullable();
            $table->integer('commission_percent')->nullable();
            $table->integer('commission_rub')->nullable();
            $table->string('delivery_address')->nullable();
            $table->string('name')->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('promocode')->nullable();
            $table->string('size_box')->nullable();
            $table->string('status_product')->nullable();
            $table->string('study_or_teacher')->nullable();
            $table->string('telegram_contact')->nullable();
            $table->string('telegram_number')->nullable();
            $table->boolean('the_number_of_boxes')->nullable();
            $table->string('translation_curse')->nullable();
            $table->string('url_for_product')->nullable();

            $table->foreignId('client_id')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
