<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertBanner extends Model {
    use HasFactory;

    protected $fillable = ['message', 'color', 'is_active'];
}
