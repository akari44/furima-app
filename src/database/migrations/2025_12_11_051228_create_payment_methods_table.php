<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();

            // 画面に表示する支払い方法名（例：カード支払い）
            $table->string('display_name', 50);

            // アプリ内部で使う識別子（例：credit_card / konbini）
            $table->string('code', 50)->unique();

            // Stripe の決済方式（例：card / konbini）
            $table->string('stripe_method', 50)->nullable();

            // 利用可能フラグ（true：使える / false：非表示）
            $table->boolean('is_active')->default(true);

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
        Schema::dropIfExists('payment_methods');
    }
}
