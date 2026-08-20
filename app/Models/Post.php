<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['title','content','user_id'])]
class Post extends Model
{


    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
