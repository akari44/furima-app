<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();

            // 購入者（ユーザー）
            $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade');

            // 購入された商品
            $table->foreignId('item_id')->constrained('items')->onDelete('cascade');

            // 支払い方法
            $table->foreignId('payment_method_id')->constrained('payment_methods');

            // その時点の配送先
            $table->string('postal_code', 255);
            $table->string('address', 255);
            $table->string('building_name', 255)->nullable();

            // 購入日時
            $table->timestamp('purchased_at')->nullable();

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
        Schema::dropIfExists('purchases');
    }
}
