<?php

namespace JumasCola\AdminChat\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JumasCola\AdminChat\Http\Resources\AdminChatMessageListResource;
use JumasCola\AdminChat\Models\AdminChatMessage;

class AdminChatController extends Controller
{
  public function index(Content $content)
  {
    $user_ids = AdminChatMessage::where("from_user", true)
      ->whereNull("read_at")
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
      ->where("from_user", true)
      ->whereNull('read_at')
      ->update(["read_at" => Carbon::now()]);

    $user_ids = AdminChatMessage::where("from_user", true)
      ->whereNull("read_at")
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
          "messages" => $messages,
          "user" => $user,
          "users" => $users,
        ])
      );
  }

  public function search(Request $request)
  {
    $query = $request->q;
    $users = User::where("name", "like", "%" . $query . "%")
      ->orWhere("email", "like", "%" . $query . "%")
      ->limit(10)
      ->get();

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
      ->where("from_user", true)
      ->whereNull('read_at')
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

    File::ensureDirectoryExists(public_path("admin_chat_files"));
    if ($request->hasFile("file")) {
      $file = $request->file("file");
      $data["file"] = Storage::put("public/admin_chat_files", $file);
    }

    $message = AdminChatMessage::create($data);

    AdminChatMessage::where("user_id", $user->id)
      ->where("from_user", true)
      ->whereNull('read_at')
      ->update(["read_at" => Carbon::now()]);

    return $message;
  }
}
