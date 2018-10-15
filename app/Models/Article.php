<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Article extends Model
{
    protected $fillable = ['title', 'body'];

    public static function validate($data) {
        return Validator::make($data, [
            'title' => 'required|max:20',
            'body' => 'required|max:100'
        ]);
    }
}
