@extends('landings.layout')
@section('content')

    <div
        class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-purple-400 via-pink-500 to-red-500">
        <div class="max-w-4xl mx-auto flex flex-row items-center animate-[fadeIn_0.8s_ease-out_forwards]">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white text-9xl" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="text-white text-2xl">Loading...</div>
            <div class="my-view" data-resource="{{ $url }}"></div>
        </div>
    </div>
    {{--    <div class="my-check" style="display: none;"></div>--}}

@endsection
