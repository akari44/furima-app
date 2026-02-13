<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*Itemsテーブルのseller_idと１対N*/
    public function sellingItems()
    {
        return $this->hasMany(Item::class, 'seller_id');
    }
        
     /*purchaseテーブルと１対N*/
    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'buyer_id');
    }

    /*likesテーブルとN対N*/
    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'likes')->withTimestamps();
    }



}
