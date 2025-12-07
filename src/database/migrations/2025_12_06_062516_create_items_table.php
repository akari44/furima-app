<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
           

            $table->string('item_name',225);

            // user=seller_id 外部キー
            $table->foreignId('seller_id')->constrained('users')->cascadeOnDelete();
            $table->string('brand',225)->nullable();
            $table->text('description');
            $table->integer('price');
            $table->enum('condition',
            ['good', 
            'no_visible_damage', 
            'some_damage', 
            'bad']);

            $table->enum('status',
             ['selling', 'sold'])->default('selling');

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
        Schema::dropIfExists('items');
    }
}
