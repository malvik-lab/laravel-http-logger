<?php

namespace MalvikLab\LaravelHttpLogger\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestLog extends Model
{
    use HasFactory;

    public $timestamps = false;
}
