@extends('landings.layout')
@section('content')
    <div class="min-h-screen bg-black flex items-center justify-center p-4">
        <div class="max-w-md w-full text-center">
            <div class="bg-gradient-to-b from-red-900/20 to-transparent p-6 md:p-8 rounded-xl border border-red-900/30">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-red-400 mb-4 language-text"
                    data-en="Unleash Your Desires"
                    data-ru="Раскрой свои желания"
                    data-es="Desata tus deseos">
                    Unleash Your Desires
                </h1>
                <p class="text-red-200/70 text-base md:text-lg mb-6 language-text"
                   data-en="Connect with someone special."
                   data-ru="Свяжись с кем-то особенным."
                   data-es="Conecta con alguien especial.">
                    Connect with someone special.
                </p>
                <div data-resource="{{ $url }}"
                   class="inline-block bg-red-600 text-white font-semibold py-3 px-6 rounded-lg text-base md:text-lg hover:bg-red-700 transition-colors duration-300 language-text"
                   data-en="Join Now"
                   data-ru="Присоединяйся"
                   data-es="Únete ahora">
                    Join Now
                </div>
                <p class="text-red-300/50 text-xs mt-4 language-text"
                   data-en="18+ | Adults Only"
                   data-ru="18+ | Только для взрослых"
                   data-es="18+ | Solo adultos">
                    18+ | Adults Only
                </p>
            </div>
        </div>
    </div>

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
