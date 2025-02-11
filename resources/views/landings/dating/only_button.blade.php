@extends('landings.layout')
@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-purple-400 via-pink-500 to-red-500">
        <div class="max-w-4xl mx-auto text-center animate-[fadeIn_0.8s_ease-out_forwards]">
            <div
               data-resource="{{ $url }}"
               class="inline-block px-8 py-4 text-lg font-semibold text-purple-600 bg-white rounded-full
                transform transition-all duration-300 ease-in-out
                hover:scale-105 hover:shadow-[0_0_30px_rgba(255,255,255,0.5)]
                active:scale-95
                animate-bounce-slow">
                🚀 Only 18+ →
            </div>
        </div>
    </div>
@endsection
