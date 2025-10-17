<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
   public function chat(Request $request)
{
    try {
        $apiKey = env('GEMINI_API_KEY');

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post(
            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent',
            [
                "contents" => [
                    [
                        "parts" => [
                            ["text" => $request->input('message')]
                        ]
                    ]
                ]
            ]
        );
        \Log::info('Réponse brute Gemini : ' . $response->body());


        $data = $response->json();

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            $reply = $data['candidates'][0]['content']['parts'][0]['text'];
        } else {
            $reply = 'Aucune réponse reçue de l’API.';
        }

        return response()->json([
            'reply' => $reply
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
}