<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KeyVal extends Model
{
	public $fillable = [
		'key',
        'key_val',
        'ttl'
    ];
    protected $casts = [
        'key_val' => 'array'
    ];
}
