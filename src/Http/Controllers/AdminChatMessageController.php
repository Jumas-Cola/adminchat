<?php

namespace JumasCola\AdminChat\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JumasCola\AdminChat\AdminChat;
use JumasCola\AdminChat\Http\Requests\StoreAdminChatMessageRequest;
use JumasCola\AdminChat\Models\AdminChatMessage;

class AdminChatMessageController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $per_page = $request->get('per_page', 15);

        $messages = AdminChatMessage::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($per_page);

        return $messages;
    }
    
    public function store(StoreAdminChatMessageRequest $request)
    {
        $user = $request->user();

        $data = [
            'user_id' => $user->id,
            'text' => $request->text,
            'from_user' => 1,
        ];

        File::ensureDirectoryExists(public_path('admin_chat_files'));
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file'] = Storage::put('public/admin_chat_files', $file);
        }

        $message = AdminChatMessage::create($data);

        return $message;
    }
}
