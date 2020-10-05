<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EcpaySend extends Model
{
    protected $table = 'ecpay_send';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'from_address',
        'to_address',
        'amount',
        'fee',
        'user_email',
        'txid'
      ];
      
     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
      protected $hidden = [
          'created_at', 'updated_at'
      ];
}
