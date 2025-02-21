@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-gray-950 flex items-center justify-center p-4 relative overflow-hidden">
        <!-- Частицы на фоне -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="w-2 h-2 bg-red-500 rounded-full absolute top-1/4 left-1/3 animate-particle"></div>
            <div class="w-1 h-1 bg-purple-500 rounded-full absolute bottom-1/3 right-1/4 animate-particle delay-500"></div>
            <div class="w-3 h-3 bg-pink-500 rounded-full absolute top-1/2 left-1/2 animate-particle delay-1000"></div>
        </div>

        <!-- Контейнер -->
        <div class="relative z-10 text-center">
            <!-- Текст с эффектом печати -->
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 typewriter language-text"
                data-en="Ready for More?"
                data-ru="Готов к большему?"
                data-es="¿Listo para más?">
            </h1>

            <!-- Кнопка с 3D-эффектом -->
            <div data-resource="{{ $url }}"
               class="inline-block bg-gradient-to-br from-red-600 to-purple-600 text-white font-bold py-4 px-8 text-lg md:text-xl rounded-lg transform transition-transform duration-300 hover:-translate-y-2 hover:shadow-[0_10px_20px_rgba(255,0,102,0.5)] language-text 3d-button"
               data-en="Step Inside"
               data-ru="Шагни внутрь"
               data-es="Entra ahora">
                Step Inside
            </div>

            <!-- Дисклеймер -->
            <p class="text-white/40 text-xs mt-8 language-text animate-fade-in"
               data-en="18+ | Adults Only"
               data-ru="18+ | Только для взрослых"
               data-es="18+ | Solo adultos">
                18+ | Adults Only
            </p>
        </div>
    </div>

    <style>
        /* Частицы */
        .animate-particle {
            animation: particleMove 6s infinite ease-in-out;
        }
        @keyframes particleMove {
            0% { transform: translate(0, 0); opacity: 0.8; }
            50% { transform: translate(20px, -20px); opacity: 0.4; }
            100% { transform: translate(0, 0); opacity: 0.8; }
        }
        .delay-500 { animation-delay: 0.5s; }
        .delay-1000 { animation-delay: 1s; }

        /* Эффект печатной машинки */
        .typewriter {
            overflow: hidden;
            border-right: 3px solid white;
            white-space: nowrap;
            animation: typing 2s steps(20, end) forwards, blink 0.5s step-end infinite;
        }
        @keyframes typing {
            from { width: 0; }
            to { width: 100%; }
        }
        @keyframes blink {
            from, to { border-color: transparent; }
            50% { border-color: white; }
        }

        /* Анимация появления */
        .animate-fade-in {
            animation: fadeIn 2s ease-out forwards;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        /* 3D-эффект кнопки */
        .3d-button:hover {
              transform: perspective(500px) rotateX(10deg) rotateY(5deg) translateY(-5px);
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
