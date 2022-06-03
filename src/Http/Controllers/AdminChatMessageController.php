<?php

namespace JumasCola\AdminChat\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JumasCola\AdminChat\AdminChat;
use JumasCola\AdminChat\Http\Requests\StoreAdminChatMessageRequest;
use JumasCola\AdminChat\Http\Resources\AdminChatMessageApiListResource;
use JumasCola\AdminChat\Http\Resources\ChatResource;
use JumasCola\AdminChat\Models\AdminChatMessage;
use JumasCola\AdminChat\Models\AdminChatAttachment;

class AdminChatMessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $per_page = $request->get("per_page", 15);

        $messages = AdminChatMessage::where("user_id", $user->id)
            ->orderBy("created_at", "desc")
            ->paginate($per_page);

        AdminChatMessage::where("user_id", $user->id)
            ->unread(false)
            ->update(["read_at" => Carbon::now()]);

        return AdminChatMessageApiListResource::collection($messages);
    }

    public function store(StoreAdminChatMessageRequest $request)
    {
        $user = $request->user();

        $data = [
            "user_id" => $user->id,
            "text" => $request->text,
            "from_user" => 1,
        ];

        $msg = AdminChatMessage::withoutEvents(function () use ($data) {
            return AdminChatMessage::create($data);
        });

        File::ensureDirectoryExists(public_path("admin_chat_files"));
        if ($request->hasFile("files")) {
            foreach ($request->file("files") as $file) {
                $type = "file";
                if (
                    in_array(
                        $file->extension(),
                        explode(",", "jpg,bmp,png,jpeg,webp")
                    )
                ) {
                    $type = "image";
                }
                AdminChatAttachment::createAndAttach($file, $msg, $type);
            }
        }

        $msg = $msg->fresh();

        event(
            "eloquent.created: JumasCola\AdminChat\Models\AdminChatMessage",
            $msg
        );

        return new ChatResource($msg);
    }
}
