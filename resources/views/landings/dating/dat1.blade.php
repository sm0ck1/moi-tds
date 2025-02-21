@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-900 via-pink-900 to-red-900 flex items-center justify-center p-4">
        <div class="max-w-4xl w-full">
            <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-6 md:p-8 text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 language-text"
                    data-en="Find Your Passion Today"
                    data-ru="Найди свою страсть сегодня"
                    data-es="Encuentra tu pasión hoy">
                    Find Your Passion Today
                </h1>
                <p class="text-white/80 text-lg md:text-xl mb-8 language-text"
                   data-en="Find your perfect match."
                   data-ru="Найди идеального партнера."
                   data-es="Encuentra tu pareja perfecta.">
                    Find your perfect match.
                </p>
                <div data-resource="{{ $url }}"
                   class="inline-block bg-gradient-to-r from-pink-600 to-red-600 text-white font-bold py-4 px-8 rounded-full text-lg md:text-xl hover:from-pink-700 hover:to-red-700 transform hover:scale-105 transition-all duration-300 language-text"
                   data-en="Start Now"
                   data-ru="Начать сейчас"
                   data-es="Empezar ahora">
                    Start Now
                </div>
                <p class="text-white/50 text-sm mt-6 language-text"
                   data-en="18+ | Adults Only"
                   data-ru="18+ | Только для взрослых"
                   data-es="18+ | Solo adultos">
                    18+ | Adults Only
                </p>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in-down {
            animation: fadeInDown 1s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const userLang = navigator.language || navigator.userLanguage;
            let lang = 'en';
            if (userLang.startsWith('ru')) lang = 'ru';
            else if (userLang.startsWith('es')) lang = 'es';
            document.querySelectorAll('.language-text').forEach(element => {
                const text = element.getAttribute(`data-${lang}`);
                if (text) element.textContent = text;
            });
        });
    </script>
@endsection
