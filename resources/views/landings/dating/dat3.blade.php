@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Неоновые линии фона -->
        <div class="absolute inset-0 flex justify-center items-center">
            <div class="w-64 h-64 border-2 border-pink-500 rounded-full animate-spin-slow opacity-30"></div>
            <div class="w-72 h-72 border-2 border-purple-500 rounded-full animate-spin-slow reverse opacity-20"></div>
        </div>

        <!-- Кнопка в центре -->
        <div class="relative z-10">
            <a href="#" data-resource="{{ $url }}"
               class="block bg-gradient-to-r from-pink-600 to-purple-600 text-white font-bold text-xl md:text-2xl py-6 px-10 rounded-full animate-pulse-btn hover:from-pink-700 hover:to-purple-700 transition-all duration-300 language-text"
               data-en="Enter Now"
               data-ru="Войти сейчас"
               data-es="Entrar ahora">
                Enter Now
            </a>
        </div>

        <!-- Плавающий текст -->
        <div class="absolute inset-0 pointer-events-none">
            <h1 class="absolute top-10 left-1/4 text-2xl md:text-3xl text-pink-400 animate-float-in-left language-text"
                data-en="Your Adventure"
                data-ru="Твое приключение"
                data-es="Tu aventura">
                Your Adventure
            </h1>
            <p class="absolute bottom-20 right-1/4 text-lg md:text-xl text-purple-300 animate-float-in-right language-text"
               data-en="Awaits You"
               data-ru="Ждет тебя"
               data-es="Te espera">
                Awaits You
            </p>
            <p class="absolute bottom-10 left-1/2 transform -translate-x-1/2 text-xs text-pink-300/50 animate-fade-in language-text"
               data-en="18+ | Adults Only"
               data-ru="18+ | Только для взрослых"
               data-es="18+ | Solo adultos">
                18+ | Adults Only
            </p>
        </div>
    </div>

    <style>
        .animate-spin-slow {
            animation: spin 20s linear infinite;
        }
        .reverse {
            animation-direction: reverse;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .animate-pulse-btn {
            animation: pulseBtn 2s infinite;
        }
        @keyframes pulseBtn {
            0% { transform: scale(1); box-shadow: 0 0 15px rgba(147, 51, 234, 0.5); }
            50% { transform: scale(1.1); box-shadow: 0 0 25px rgba(147, 51, 234, 0.8); }
            100% { transform: scale(1); box-shadow: 0 0 15px rgba(147, 51, 234, 0.5); }
        }

        .animate-float-in-left {
            animation: floatInLeft 1.5s ease-out forwards;
        }
        @keyframes floatInLeft {
            0% { opacity: 0; transform: translateX(-50px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .animate-float-in-right {
            animation: floatInRight 1.5s ease-out forwards;
        }
        @keyframes floatInRight {
            0% { opacity: 0; transform: translateX(50px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .animate-fade-in {
            animation: fadeIn 2s ease-out forwards;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
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
