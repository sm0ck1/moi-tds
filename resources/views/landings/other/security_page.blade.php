@extends('landings.layout')
@section('content')
    <div class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-blue-400 via-indigo-500 to-purple-500">
        <div class="max-w-4xl mx-auto text-center animate-[fadeIn_0.8s_ease-out_forwards]">
            <div class="bg-white/90 backdrop-blur-sm p-8 rounded-2xl shadow-xl mb-6">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto mb-4 text-blue-500 animate-spin-slow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Security Check</h2>
                    <p class="text-gray-600 mb-4">Please verify that you're a human</p>
                </div>

                <div
                    data-resource="{{ $url }}"
                    class="inline-block px-8 py-4 text-lg font-semibold text-white bg-blue-500 rounded-full
                    transform transition-all duration-300 ease-in-out
                    hover:scale-105 hover:bg-blue-600 hover:shadow-[0_0_30px_rgba(59,130,246,0.5)]
                    active:scale-95">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Verify Now
                    </span>
                </div>

                <div class="mt-6 text-sm text-gray-500 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    Protected by Security System
                </div>
            </div>
        </div>
    </div>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

@endsection
