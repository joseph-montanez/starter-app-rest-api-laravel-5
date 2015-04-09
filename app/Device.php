<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model {
	protected $table = 'devices';
	protected $fillable = ['uuid', 'user_id'];
    
    static function findByUUID($uuid) {
    	return Device::where('uuid', '=', $uuid)->first();
    }

    function user() {
        return $this->belongsTo('\user\User', 'users_id', 'id');
    }
}