<?php

namespace JumasCola\AdminChat\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            "id" => $this->id,
            "chat_id" => $this->chat_id,
            "user_id" => $this->user_id,
            "status" => $this->status,
            "text" => $this->text,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "files" => $this->files(),
        ];
    }
}
