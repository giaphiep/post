<?php

namespace GiapHiep\Post\Models;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $fillable = ['post_id', 'tag_id'];
}
