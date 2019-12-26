<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyVal extends Model
{
	public $fillable = [
		'key',
        'key_val'
    ];
    protected $casts = [
        'key_val' => 'array'
    ];
}
