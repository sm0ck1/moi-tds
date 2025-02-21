@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-black flex flex-col items-center justify-center p-4 relative overflow-hidden">
        <!-- Вспышки на фоне -->
        <div class="absolute inset-0">
            <div class="w-4 h-4 bg-red-600 rounded-full absolute top-10 left-1/4 animate-drip-bg"></div>
            <div class="w-3 h-3 bg-pink-500 rounded-full absolute top-20 right-1/3 animate-drip-bg delay-700"></div>
        </div>

        <!-- Вертикальная структура с "капающим" текстом -->
        <div class="relative z-10 text-center flex flex-col items-center gap-8">
            <!-- Текст сверху -->
            <div class="space-y-4">
                <h1 class="text-3xl md:text-4xl font-bold text-red-500 animate-drip language-text"
                    data-en="Wet"
                    data-ru="Мокро"
                    data-es="Mojado">
                    Wet
                </h1>
                <p class="text-lg md:text-xl text-pink-400 animate-drip delay-300 language-text"
                   data-en="And Wild"
                   data-ru="И дико"
                   data-es="Y salvaje">
                    And Wild
                </p>
                <p class="text-base md:text-lg text-red-300/80 animate-drip delay-600 language-text"
                   data-en="Just For You"
                   data-ru="Только для тебя"
                   data-es="Solo para ti">
                    Just For You
                </p>
            </div>

            <!-- Кнопка-рычаг -->
            <div class="relative">
                <a href="#" data-resource="{{ $url }}"
                   class="inline-block bg-gradient-to-r from-red-600 to-pink-600 text-white font-bold text-lg md:text-xl py-4 px-8 rounded-xl animate-pull language-text"
                   data-en="Pull It"
                   data-ru="Дерни его"
                   data-es="Tíralo">
                    Pull It
                </a>
                <!-- Эффект натяжения -->
                <div class="absolute -top-2 -left-2 w-2 h-2 bg-red-400 rounded-full animate-pull-dot"></div>
                <div class="absolute -top-2 -right-2 w-2 h-2 bg-pink-400 rounded-full animate-pull-dot delay-200"></div>
            </div>

            <!-- Дисклеймер снизу -->
            <p class="text-red-300/50 text-xs animate-fade-in language-text"
               data-en="18+ | Adults Only"
               data-ru="18+ | Только для взрослых"
               data-es="18+ | Solo adultos">
                18+ | Adults Only
            </p>
        </div>
    </div>

    <style>
        /* Вспышки на фоне */
        .animate-drip-bg {
            animation: dripBg 4s infinite ease-in-out;
        }
        @keyframes dripBg {
            0% { transform: translateY(0); opacity: 0.8; }
            50% { transform: translateY(20px); opacity: 0.4; }
            100% { transform: translateY(0); opacity: 0.8; }
        }
        .delay-700 { animation-delay: 0.7s; }

        /* Эффект капающего текста */
        .animate-drip {
            animation: drip 1.5s ease-out forwards;
        }
        @keyframes drip {
            0% { opacity: 0; transform: translateY(-40px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .delay-300 { animation-delay: 0.3s; }
        .delay-600 { animation-delay: 0.6s; }

        /* Эффект натяжения кнопки */
        .animate-pull {
            animation: pull 2s infinite ease-in-out;
        }
        @keyframes pull {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(5px); }
        }

        .animate-pull-dot {
            animation: pullDot 2s infinite ease-in-out;
        }
        @keyframes pullDot {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .delay-200 { animation-delay: 0.2s; }

        /* Появление дисклеймера */
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
