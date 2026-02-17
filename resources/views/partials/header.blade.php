<header class="site-header">
    <div class="container header-inner">
        <a class="logo" href="/">HOTEL<span class="logo-accent">X</span></a>

        <!-- Mobile Menu Toggle -->
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>

        <nav class="main-nav" id="mainNav" aria-label="Main navigation">
            <ul>
                <li><a href="{{ url('/kamar') }}">Kamar & Suite</a></li>
                <li><a href="{{ url('/aktivitas') }}">Aktivitas</a></li>
                <li><a href="{{ url('/fasilitas') }}">Fasilitas</a></li>
                <li><a href="{{ url('/resto') }}">Resto dan kafe</a></li>
                <li><a href="{{ url('/kontak') }}">Kontak Kami</a></li>
                <li><a class="btn-reserve" href="{{ url('/reservasi') }}">Reservasi</a></li>
            </ul>
        </nav>

        <!-- Mobile Nav Overlay -->
        <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>
    </div>
</header>
