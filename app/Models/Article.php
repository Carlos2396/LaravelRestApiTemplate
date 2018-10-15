<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Article extends Model
{
    protected $fillable = ['title', 'body'];

    public static function validateCreate($data) {
        return Validator::make($data, [
            'title' => 'required|max:20',
            'body' => 'required|max:100'
        ]);
    }

    public static function validateUpdate($data) {
        return Validator::make($data, [
            'title' => 'sometimes|max:20',
            'body' => 'sometimes|max:100'
        ]);
    }
}
