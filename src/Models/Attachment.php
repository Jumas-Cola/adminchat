<?php

namespace JumasCola\AdminChat\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminChatAttachment extends Model
{
    use HasFactory;

    protected $fillable = ["type", "path", "order", "origin_name"];

    public function attachable()
    {
        return $this->morphTo();
    }

    public static function createAndAttach($file, $attachable, $type = "image")
    {
        $path = Storage::put("public/admin_chat_files", $file);
        $attachment = self::create([
            "type" => $type,
            "path" => $path,
            "origin_name" => $file->getClientOriginalName(),
        ]);

        $attachment
            ->attachable()
            ->associate($attachable)
            ->save();

        $attachment->save();

        return $attachable;
    }

    protected static function booted()
    {
        static::addGlobalScope("order", function (Builder $builder) {
            $builder->orderBy("order", "desc")->orderBy("id", "asc");
        });

        self::deleted(function ($model) {
            Storage::delete($model->path);
        });
    }
}
