<!-- App css -->
<link href="{{ url('dist/assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-style" />
<!-- Icons -->
<link href="{{ url('dist/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ url('dist/assets/js/head.js') }}"></script>

<!-- Google Fonts (Plus Jakarta Sans) -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Tailwind CSS (CDN) - Preflight disabled to preserve Zoyothemes template styling -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        corePlugins: {
            preflight: false,
        },
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                },
                colors: {
                    primary: '#0F172A',
                    secondary: '#3B82F6',
                    accent: '#8B5CF6',
                    background: '#F8FAFC',
                }
            }
        }
    }
</script>

<!-- Fix: Tailwind's .collapse utility (visibility: collapse) conflicts with Bootstrap's .collapse component -->
<style>
    .collapse {
        visibility: visible !important;
    }
    .collapse:not(.show) {
        display: none;
    }
</style>
