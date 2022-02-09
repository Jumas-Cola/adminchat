<?php

namespace JumasCola\AdminChat\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AdminChatMessageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_user_id' => $this->admin_user_id,
            'text' => $this->text,
            'created_at' => $this->created_at->toDateTimeString(),
            'file' => $this->file ? url(Storage::url($this->file)) : null,
            'from_user' => $this->from_user,
        ];
    }
}
