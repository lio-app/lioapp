<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gasprice extends Model
{
    protected $table = 'gasprice';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fee_fastest',
        'fee_fast',
        'fee_average'
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
