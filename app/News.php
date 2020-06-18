<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{

    protected $table = 'news';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'title',
      'description',
      'status'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}
