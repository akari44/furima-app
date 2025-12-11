<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_images', function (Blueprint $table) {
            $table->id();

        // 商品ID（外部キー）
        $table->foreignId('item_id')->constrained('items')->onDelete('cascade');

        // 画像パス
        $table->string('image_path', 255);

        // メイン画像フラグ（1商品1枚ならtrue固定になる）
        $table->boolean('is_main')->default(true);

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
        Schema::dropIfExists('item_images');
    }
}
