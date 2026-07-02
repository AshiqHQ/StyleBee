<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        // gemini-2.5-flash = best free-tier model for chat (fast + good quality)
        $this->model = config('services.gemini.model', 'gemini-2.5-flash');
    }

    /**
     * Ask Gemini a question, giving it context (your database data) to answer from.
     *
     * @param string $question   The customer's question
     * @param string $context    Relevant data pulled from your MySQL database
     * @param array  $history    Previous chat messages [['role' => 'user'/'model', 'text' => '...'], ...]
     * @return string            Gemini's answer
     */
    public function askWithContext(string $question, string $context, array $history = []): string
    {
        $systemInstruction = <<<PROMPT
You are a friendly customer support assistant for an online clothing store that sells
men's, women's, and kids' fashion (panjabis, shirts, sarees, abayas, jeans, accessories, etc).

Answer the customer's question using ONLY the information provided in the "CONTEXT" section below.
Rules:
- If the context contains matching products, mention the product name, price (use the sale
  price if present), and stock status.
- If asked about an order, only reference orders shown in the context — never guess an order status.
- If the answer is not contained in the context, politely say you don't have that information
  and suggest the customer browse the site or contact human support — do NOT make up prices,
  stock, or order details.
- Keep answers short, warm, and easy to read. Use Taka (BDT) as the currency when quoting prices.

CONTEXT:
{$context}
PROMPT;

        $contents = [];

        foreach ($history as $turn) {
            $contents[] = [
                'role' => $turn['role'], // 'user' or 'model'
                'parts' => [['text' => $turn['text']]],
            ];
        }

        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $question]],
        ];

        $response = Http::timeout(30)->post(
            "{$this->baseUrl}/{$this->model}:generateContent?key={$this->apiKey}",
            [
                'system_instruction' => [
                    'parts' => [['text' => $systemInstruction]],
                ],
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.3,
                    'maxOutputTokens' => 500,
                ],
            ]
        );

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return "Sorry, I'm having trouble answering right now. Please try again in a moment.";
        }

        $data = $response->json();

        return $data['candidates'][0]['content']['parts'][0]['text']
            ?? "Sorry, I couldn't generate a response. Please try rephrasing your question.";
    }
}
