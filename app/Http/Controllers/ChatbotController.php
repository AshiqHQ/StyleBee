<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Services\KnowledgeBaseService;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    public function __construct(
        protected GeminiService $gemini,
        protected KnowledgeBaseService $knowledgeBase
    ) {}

    /**
     * Show the chat widget page (optional standalone page).
     */
    public function index()
    {
        return view('chatbot.index');
    }

    /**
     * Handle an incoming chat message from the customer.
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'session_id' => 'nullable|string',
        ]);

        $sessionId = $request->input('session_id') ?? (string) Str::uuid();
        $question = $request->input('message');

        // 1) RETRIEVE: search MySQL for relevant info
        $context = $this->knowledgeBase->findRelevantContext($question);

        // 2) Load recent conversation history for this session (for follow-up questions)
        $history = ChatMessage::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->limit(10) // last 10 messages, keep the prompt small
            ->get()
            ->map(fn ($m) => ['role' => $m->role, 'text' => $m->message])
            ->toArray();

        // 3) GENERATE: ask Gemini, giving it the context + history
        $answer = $this->gemini->askWithContext($question, $context, $history);

        // 4) Save both messages to DB so the conversation has memory
        ChatMessage::create(['session_id' => $sessionId, 'role' => 'user', 'message' => $question]);
        ChatMessage::create(['session_id' => $sessionId, 'role' => 'model', 'message' => $answer]);

        return response()->json([
            'session_id' => $sessionId,
            'reply' => $answer,
        ]);
    }
}
