<?php

namespace JumasCola\AdminChat\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JumasCola\AdminChat\Http\Resources\AdminChatMessageListResource;
use JumasCola\AdminChat\Models\AdminChatAttachment;
use JumasCola\AdminChat\Models\AdminChatMessage;

class AdminChatController extends Controller
{
    public function index(Content $content)
    {
        Admin::disablePjax();

        $user_ids = AdminChatMessage::unread()
            ->select("user_id")
            ->groupBy("user_id")
            ->get();
        $user_ids = $user_ids->map(fn($item) => $item->user_id)->toArray();
        $users = User::whereIn("id", $user_ids)->get();

        return $content
            ->title("AdminChat")
            ->description("Chat with users")
            ->body(
                view("adminchat::index", [
                    "users" => $users,
                ])
            );
    }

    public function show(Content $content, User $user)
    {
        $messages = AdminChatMessage::where("user_id", $user->id)->orderBy(
            "created_at",
            "desc"
        );
        $messages = $messages->paginate(5);

        AdminChatMessage::where("user_id", $user->id)
            ->unread()
            ->update(["read_at" => Carbon::now()]);

        $user_ids = AdminChatMessage::unread()
            ->select("user_id")
            ->groupBy("user_id")
            ->get();
        $user_ids = $user_ids->map(fn($item) => $item->user_id)->toArray();
        $users = User::whereIn("id", $user_ids)->get();

        return $content
            ->title("AdminChat")
            ->description("Chat with users")
            ->body(
                view("adminchat::show", [
                    "messages" => AdminChatMessageListResource::collection(
                        $messages
                    ),
                    "user" => $user,
                    "users" => $users,
                ])
            );
    }

    public function search(Request $request)
    {
        $query = $request->q;

        if ($query) {
            $users = User::where("name", "like", "%" . $query . "%")
                ->orWhere("email", "like", "%" . $query . "%")
                ->limit(10)
                ->get();
        } else {
            $user_ids = AdminChatMessage::unread()
                ->select("user_id")
                ->groupBy("user_id")
                ->get();
            $user_ids = $user_ids->map(fn($item) => $item->user_id)->toArray();
            $users = User::whereIn("id", $user_ids)
                ->get()
                ->map(function ($item) {
                    $item->new_messages = true;
                    return $item;
                });
        }

        return $users;
    }

    public function show_api(Request $request, User $user)
    {
        $messages = AdminChatMessage::where("user_id", $user->id)->orderBy(
            "created_at",
            "desc"
        );
        $messages = $messages->paginate(5);

        AdminChatMessage::where("user_id", $user->id)
            ->unread()
            ->update(["read_at" => Carbon::now()]);

        return AdminChatMessageListResource::collection($messages);
    }

    public function store(Request $request, User $user)
    {
        $data = [
            "user_id" => $user->id,
            "text" => $request->text,
            "from_user" => 0,
        ];

        $msg = AdminChatMessage::create($data);

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

        AdminChatMessage::where("user_id", $user->id)
            ->unread()
            ->update(["read_at" => Carbon::now()]);

        return $msg;
    }
}
