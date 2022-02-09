<?php

namespace JumasCola\AdminChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminChatMessage extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'admin_id',
        'from_user',
        'text',
        'file',
    ];
}
