<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryImage extends Model
{
    use HasFactory;

    protected $table= 'temporary_images';

    protected $fillable = ['folder','image'];
}
