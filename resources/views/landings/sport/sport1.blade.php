@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-gray-900 flex flex-col md:flex-row items-center justify-center p-8 relative overflow-hidden">
        <!-- Картинка слева -->
        <img
            src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=500&q=80"
            alt="Winning Moment"
            class="w-full md:w-1/2 h-1/2 md:h-full object-cover rounded-lg md:rounded-r-none opacity-80 animate-fade-in-slow"
        >

        <!-- Контент справа -->
        <div class="flex-1 flex flex-col items-center justify-center w-full max-w-xl gap-12 text-center">
            <!-- Заголовок -->
            <h1 class="text-4xl md:text-5xl font-extrabold text-yellow-400 animate-slide-in-right language-text"
                data-en="🏆 Bet & Win Big"
                data-ru="🏆 Ставь и выигрывай по-крупному"
                data-es="🏆 Apuesta y gana en grande">
                🏆 Bet & Win Big
            </h1>

            <!-- Короткий текст -->
            <p class="text-xl md:text-2xl text-white/80 animate-slide-in-right delay-300 language-text"
               data-en="Live Action. Instant Cash. 🔥"
               data-ru="Живые события. Моментальные деньги. 🔥"
               data-es="Acción en vivo. Dinero al instante. 🔥">
                Live Action. Instant Cash. 🔥
            </p>

            <!-- Кнопка внизу -->
            <div>
                <div
                    data-resource="{{ $url }}"
                    class="inline-block px-10 py-5 text-xl font-bold text-gray-900 bg-yellow-400 rounded-full
                transform transition-all duration-300 ease-in-out
                hover:scale-110 hover:shadow-[0_0_40px_rgba(255,215,0,0.7)]
                active:scale-95
                animate-glow language-text"
                    data-en="🎟️ Grab Your Chance"
                    data-ru="🎟️ Хватай свой шанс"
                    data-es="🎟️ Toma tu oportunidad">
                    🎟️ Grab Your Chance
                </div>
            </div>

            <!-- Дисклеймер -->
            <p class="absolute bottom-6 left-1/2 transform -translate-x-1/2 text-sm text-white/60 animate-fade-in delay-600 language-text"
               data-en="🔞 18+ | Responsible Gaming"
               data-ru="🔞 18+ | Ответственная игра"
               data-es="🔞 18+ | Juego responsable">
                🔞 18+ | Responsible Gaming
            </p>
        </div>
    </div>

    <style>
        /* Плавное появление картинки */
        .animate-fade-in-slow {
            animation: fadeInSlow 2s ease-out forwards;
        }
        @keyframes fadeInSlow {
            0% { opacity: 0; }
            100% { opacity: 0.8; }
        }

        /* Слайд текста справа */
        .animate-slide-in-right {
            animation: slideInRight 1.5s ease-out forwards;
        }
        @keyframes slideInRight {
            0% { opacity: 0; transform: translateX(100px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        .delay-300 { animation-delay: 0.3s; }
        .delay-600 { animation-delay: 0.6s; }

        /* Сияние кнопки */
        .animate-glow {
            animation: glow 2s infinite ease-in-out;
        }
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 15px rgba(255, 215, 0, 0.5); }
            50% { box-shadow: 0 0 30px rgba(255, 215, 0, 0.8); }
        }

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
