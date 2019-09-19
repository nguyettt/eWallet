<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'wallet_id', 'cat_id', 'details', 'amount', 'benefit_wallet', 'delete_flag',
    ];

    protected $table='transaction';

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function wallet()
    {
        return $this->belongsTo('App\Wallet');
    }

    public function category()
    {
        return $this->belongsTo('App\Category', 'cat_id');
    }
}
