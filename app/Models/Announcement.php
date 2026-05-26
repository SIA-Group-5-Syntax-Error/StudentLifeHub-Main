<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    // This line tells Laravel it is safe to insert these fields via the API
    protected $fillable = ['title', 'content'];
}