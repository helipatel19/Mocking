<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $task;

    protected $fillable = ['title','description'];
}
