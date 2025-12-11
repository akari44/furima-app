<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id','item_name', 'price', 'description'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // アイテムから購入者
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // イメージ画像から商品
    public function image()
    {
        return $this->hasMany(ItemImage::class);
    }
}
