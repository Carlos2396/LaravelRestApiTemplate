<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Article extends Model
{
    protected $with = ['user'];
    protected $fillable = ['title', 'body', 'user_id'];

    public static function validate($data) {
        return Validator::make($data, [
            'title' => 'required|max:20',
            'body' => 'required|max:100',
            'user_id' => 'required|integer|exists:users,id'
        ]);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
