<?php

namespace App\Services;

use App\Enum\ModelGpt4AllEnum;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\json;

class ModelOnComputer
{
    // API URL
    private string $url = '';

    public function __construct(string $url = '')
    {
        $this->url = ! empty($url) ? $url : env('SERVICE_MODEL');
    }

    public function sendMessage(string $system, string $prompt, int $tokens = 100, float $temperature = 0.7)
    {
        $result = Http::asJson()->timeout(500)->post($this->url.'/generate', [
            'system' => $system,
            'prompt' => $prompt,
            'temperature' => $temperature,
            'max_tokens' => $tokens,
        ]);
        if ($result->failed()) {
            Debugbar::error($result);

            return false;
        }
        $response = (array) $result->object();
        Debugbar::info($response);

        return collect([
            'answer' => $response['response'],
        ]);

    }

    public function sendOllama(string $prompt, float $temperature = 0.7)
    {
        $result = Http::asJson()->timeout(500)->post('http://localhost:11434/api/generate', [
            //            'system'      => $system,
            'prompt' => $prompt,
            'model' => 'llama3.2-vision:latest',
            'stream' => false,
            'format' => 'json',
            'options' => [
                'temperature' => $temperature,
            ],
        ]);
        if ($result->failed()) {
            Debugbar::error($result);

            return false;
        }
        $response = (array) $result->object();
        Debugbar::info($response);

        return collect([
            'answer' => $response['response'],
        ]);

    }

    public function gpt4All(string $prompt, int $tokens = 100, float $temperature = 0.7)
    {
        $request = [
            'model' => ModelGpt4AllEnum::ORC2_FULL,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => $temperature,
            'max_tokens' => $tokens,
        ];
        //        dd(json_encode($request));

        $result = Http::asJson()->timeout(500)->post($this->url.'/v1/chat/completions', $request);
        if ($result->failed()) {
            return false;
        }
        $response = $result->object();
        Debugbar::info($response);
        $answer = $this->answerFromGpt4All($response);

        return collect([
            'answer' => $answer,
        ]);
    }

    private function answerFromGpt4All($response)
    {
        $collection = collect($response);

        $messageContent = $collection['choices'][0]->message->content;

        return $messageContent;
    }
}
