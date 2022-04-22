<?php

namespace JumasCola\AdminChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminChatMessage extends Model
{
    use HasFactory;

    public $fillable = ["user_id", "admin_id", "from_user", "text", "file"];

    /**
     * Unread messages
     *
     * @return void
     */
    public function scopeUnread($query, $from_user = true)
    {
        $query->where("from_user", $from_user)->whereNull("read_at");
    }
}
