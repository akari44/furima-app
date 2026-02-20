<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Item extends Model
{
    use HasFactory;

    protected $fillable = ['seller_id','item_name', 'price', 'description'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
    // アイテムからカテゴリ
        public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    

    // アイテムからイメージ画像
    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public const CONDITION_LABELS = [
        'good' => '良好',
        'no_visible_damage' => '目立った傷や汚れなし',
        'some_damage' => 'やや傷や汚れあり',
        'bad' => '状態が悪い',
    ];

    public function getConditionLabelAttribute()
    {
        return self::CONDITION_LABELS[$this->condition] ?? '不明';
    }
    // アイテムから支払い情報
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
    
    // アイテムからいいねしたユーザー
    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }
    // アイテムからいいね情報
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // 検索機能
    public function scopeKeyword($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('item_name', 'like', '%' . $keyword . '%');
        }

        return $query;
    }
    
    // アイテムからコメント
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

}

