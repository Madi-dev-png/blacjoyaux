<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(protected ChatService $chat) {}

    /** Endpoint AJAX appelé par le widget de chat présent sur toutes les pages. */
    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array',
        ]);

        $result = $this->chat->reply(
            $validated['message'],
            $validated['history'] ?? []
        );

        return response()->json([
            'reply'  => $result['reply'],
            'source' => $result['source'],
        ]);
    }
}
