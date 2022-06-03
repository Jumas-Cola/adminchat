<?php

namespace JumasCola\AdminChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminChatMessage extends Model
{
    use HasFactory;

    public $fillable = ["user_id", "admin_id", "from_user", "text"];

    /**
     * Unread messages
     *
     * @return void
     */
    public function scopeUnread($query, $from_user = true)
    {
        $query->where("from_user", $from_user)->whereNull("read_at");
    }

    public function attachments()
    {
        return $this->morphMany(AdminChatAttachment::class, "attachable");
    }

    public function files()
    {
        return $this->attachments()
            ->get()
            ->map(
                fn($i) => [
                    "id" => $i->id,
                    "type" => $i->type,
                    "origin_name" => $i->origin_name,
                    "path" => url(Storage::url($i->path)),
                ]
            );
    }
}
