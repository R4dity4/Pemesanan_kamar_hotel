<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Hotel')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>html,body{height:100%}</style>
</head>
<body>
    <!-- Page Loading Overlay -->
    <div class="page-loader" id="pageLoader">
        <div class="loader-inner">
            <div class="wrapper">
                <div class="candles">
                    <div class="light__wave"></div>
                    <div class="candle1">
                        <div class="candle1__body">
                            <div class="candle1__eyes">
                                <span class="candle1__eyes-one"></span>
                                <span class="candle1__eyes-two"></span>
                            </div>
                            <div class="candle1__mouth"></div>
                        </div>
                        <div class="candle1__stick"></div>
                    </div>
                    <div class="candle2">
                        <div class="candle2__body">
                            <div class="candle2__eyes">
                                <div class="candle2__eyes-one"></div>
                                <div class="candle2__eyes-two"></div>
                            </div>
                        </div>
                        <div class="candle2__stick"></div>
                    </div>
                    <div class="candle2__fire"></div>
                    <div class="sparkles-one"></div>
                    <div class="sparkles-two"></div>
                    <div class="candle__smoke-one"></div>
                    <div class="candle__smoke-two"></div>
                </div>
                <div class="floor"></div>
            </div>
            <p class="loader-text">Memuat halaman<span class="loader-dots"></span></p>
        </div>
    </div>

    @include('partials.header')

    @hasSection('no-breadcrumb')
    @else
        @include('partials.breadcrumb')
    @endif

    <main class="site-main">
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Back to Top -->
    <button class="back-to-top" id="backToTop" aria-label="Kembali ke atas">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>
    </button>

    <script src="{{ asset('js/app.js') }}"></script>

    <script>
    // Toast Notification System
    window.Toast = {
        show(type, title, message, duration = 4000) {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;

            const icons = {
                success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>',
                error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>',
                warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
                info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>'
            };

            toast.innerHTML = `
                <div class="toast-icon">${icons[type] || icons.info}</div>
                <div class="toast-body">
                    <div class="toast-title">${title}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.closest('.toast').remove()">&times;</button>
                <div class="toast-progress" style="animation-duration:${duration}ms"></div>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('toast-exit');
                setTimeout(() => toast.remove(), 300);
            }, duration);
        },
        success(title, msg) { this.show('success', title, msg); },
        error(title, msg) { this.show('error', title, msg); },
        warning(title, msg) { this.show('warning', title, msg); },
        info(title, msg) { this.show('info', title, msg); }
    };

    // Back to Top
    (function() {
        const btn = document.getElementById('backToTop');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            btn.classList.toggle('visible', window.scrollY > 400);
        });
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();

    // Show Laravel session messages as toasts
    @if(session('success'))
        Toast.success('Berhasil', @json(session('success')));
    @endif
    @if(session('error'))
        Toast.error('Gagal', @json(session('error')));
    @endif
    @if(session('info'))
        Toast.info('Info', @json(session('info')));
    @endif
    </script>

    <script>
    // Page Loader — show on navigation, hide on load
    (function() {
        const loader = document.getElementById('pageLoader');
        // Hide loader once page is fully loaded
        window.addEventListener('load', function() {
            loader.classList.add('loaded');
        });
        // Fallback: hide after 4s max
        setTimeout(function() { loader.classList.add('loaded'); }, 4000);

        // Show loader when navigating away
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a[href]');
            if (!link) return;
            const href = link.getAttribute('href');
            // Skip non-navigation links
            if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) return;
            if (link.target === '_blank') return;
            if (e.ctrlKey || e.metaKey || e.shiftKey) return;
            // Skip same-page anchors
            if (link.origin === window.location.origin && link.pathname === window.location.pathname) return;
            loader.classList.remove('loaded');
        });

        // Also show on form submit
        document.addEventListener('submit', function() {
            loader.classList.remove('loaded');
        });

        // Handle browser back/forward
        window.addEventListener('pageshow', function(e) {
            if (e.persisted) loader.classList.add('loaded');
        });
    })();
    </script>

    @yield('scripts')
</body>
</html>
