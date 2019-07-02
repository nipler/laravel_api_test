<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    protected $fillable = ['uuid', 'status', 'payload', 'created_at', 'updated_at'];

	protected $hidden = ['id'];
	
	/**
    * payload преобразуем в json и обратно
    *
    * @var array
    */
	protected $casts = [
		'payload' => 'object',
	];
}
