@extends('layouts.app')

@section('title','Beranda')

@section('no-breadcrumb', true)

@section('content')


<section class="hero-slider" id="heroSlider">
    <button class="hero-prev" id="heroPrev" aria-label="Previous slide" style="display: none;"></button>
    <button class="hero-next" id="heroNext" aria-label="Next slide" style="display: none;"></button>
    <div class="hero-track" id="heroTrack" style="">
        <div class="hero-slide active" style="background-image:url('{{ asset('images/hotel1.jpg') }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content container">
                <span class="hero-subtitle">Selamat Datang di</span>
                <h1>HOTEL<span class="text-accent">X</span></h1>
                <p>Nikmati penginapan mewah dengan layanan terbaik, desain elegan, dan pengalaman lokal yang tak terlupakan.</p>
                <a href="{{ route('reservasi') }}" class="btn-reserve" style="margin-top:24px; text-decoration: none;">Reservasi Sekarang</a>
            </div>
        </div>
        <div class="hero-slide" style="background-image:url('{{ asset('images/hotel2.jpg') }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content container">
                <span class="hero-subtitle">Pengalaman Menginap</span>
                <h1>Kenyamanan Modern</h1>
                <p>Kamar dengan fasilitas lengkap, area santai, dan akses mudah ke atraksi utama kota.</p>
                <a href="{{ route('kamar') }}" class="btn-reserve" style="margin-top:24px; text-decoration: none;">Lihat Kamar</a>
            </div>
        </div>
        <div class="hero-slide" style="background-image:url('{{ asset('images/hotel3.jpg') }}')">
            <div class="hero-overlay"></div>
            <div class="hero-content container">
                <span class="hero-subtitle">Momen Berharga</span>
                <h1>Tak Terlupakan</h1>
                <p>Ciptakan momen berharga bersama keluarga dan orang tersayang di destinasi impian Anda.</p>
                <a href="{{ route('fasilitas') }}" class="btn-reserve" style="margin-top:24px; text-decoration: none;">Jelajahi Fasilitas</a>
            </div>
        </div>
    </div>
    <!-- Slider Indicators -->
    <div class="hero-indicators">
        <span class="hero-dot active" data-slide="0"></span>
        <span class="hero-dot" data-slide="1"></span>
        <span class="hero-dot" data-slide="2"></span>
    </div>
</section>

<!-- Quick Booking Form -->
<section class="quick-booking">
    <div class="container">
        <div class="booking-form-wrapper">
            <h3>Reservasi Cepat</h3>
            <p>Check-in: 14:00 WIB | Check-out: 12:00 WIB</p>
            <a href="{{ route('reservasi') }}" class="btn-reserve" style="text-decoration: none;">Buat Reservasi</a>
            <a href="{{ route('reservasi.cek') }}" class="btn-outline" style="margin-left:12px; text-decoration: none; color:#fff !important; border-color:#fff !important;">Cek Status</a>
        </div>
    </div>
</section>

<div class="section-sep">
    <h2>HOTELX EXPERIENCE</h2>
    <div class="accent"></div>
    <p>Hadir sebagai perwujudan filosofi utama untuk mempersembahkan gaya hidup artistik, kami memberikan makna tersendiri kepada pengalaman tamu dalam suasana kemewahan nan kontemporer.</p>
</div>

<!-- Keunggulan Section -->
<section class="features-section">
    <div class="container">
        <div class="features-grid">
            <div class="feature-item">
                <div class="feature-icon"><x-lucide-building class="lucide-icon-feature" /></div>
                <h4>Kamar Premium</h4>
                <p>Kamar dengan desain elegan dan fasilitas modern untuk kenyamanan maksimal.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><x-lucide-utensils class="lucide-icon-feature" /></div>
                <h4>Kuliner Berkelas</h4>
                <p>Restoran dan cafe dengan menu internasional dan masakan lokal pilihan.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><x-lucide-sparkles class="lucide-icon-feature" /></div>
                <h4>Spa & Wellness</h4>
                <p>Perawatan tubuh dan pikiran dengan terapis profesional bersertifikat.</p>
            </div>
            <div class="feature-item">
                <div class="feature-icon"><x-lucide-waves class="lucide-icon-feature" /></div>
                <h4>Kolam & Gym</h4>
                <p>Fasilitas olahraga lengkap untuk menjaga kebugaran selama menginap.</p>
            </div>
        </div>
    </div>
</section>

<section class="section-info">
    <div class="container info-inner">
        <div style="flex:1">
            <div class="info-card">
                <h2>Pengalaman yang Anda Dapatkan</h2>
                <p>Mulai dari sambutan hangat, sarapan ala chef, hingga layanan kebersihan harian. Kami menghadirkan pengalaman berkelas untuk setiap tamu.</p>
                <ul>
                    <li><x-lucide-check class="lucide-icon-check" /> Check-in cepat & layanan 24 jam</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Spa dan pusat kebugaran</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Tur & aktivitas lokal</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Restoran fine dining</li>
                    <li><x-lucide-check class="lucide-icon-check" /> WiFi gratis di seluruh area</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Parkir luas & aman</li>
                </ul>
                <a class="btn-outline" href="{{ route('fasilitas') }}" style="margin-top:20px">Lihat Fasilitas</a>
            </div>
        </div>
        <div style="flex:1">
            <div class="info-card highlight-card">
                <span class="promo-badge"><x-lucide-search class="lucide-icon-check" style="width:14px; height:14px; margin-right:4px;" /> STATUS</span>
                <h4>Cek Status Pesanan</h4>
                <p>Masukkan nomor KTP yang digunakan saat reservasi untuk melihat status pemesanan Anda.</p>
                <form action="{{ route('reservasi.cek') }}" method="GET" style="margin-top: 20px;">
                    <div style="margin-bottom: 16px;">
                        <label for="no_ktp_home" style="display: block; margin-bottom: 8px; font-weight: 500; color: #fff;">Nomor KTP</label>
                        <input type="text" name="no_ktp" id="no_ktp_home" placeholder="Masukkan nomor KTP"
                            style="width: 100%; padding: 12px 16px; border: 1px solid rgba(255,255,255,0.3); border-radius: 8px; font-size: 15px; background: rgba(255,255,255,0.1); color: #fff; box-sizing: border-box;"
                            required>
                    </div>
                    <button type="submit" class="btn-reserve" style="width: 100%; border: none; cursor: pointer;">
                        <x-lucide-search style="width:16px; height:16px; margin-right:8px; vertical-align: middle;" /> Cek Status Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<div class="section-sep">
    <h2>KAMAR & SUITE</h2>
    <div class="accent"></div>
    <p>Suite menawan untuk pengalaman menginap yang menyenangkan dalam kemewahan memukau.</p>
</div>

<section class="container js-slider" data-slider="rooms">
    <div style="display:flex; justify-content:space-between; align-items:center">
        <h3>Pilih Kamar Anda</h3>
        <div class="slider-controls">
            <button class="slider-btn slider-prev" data-direction="prev">‹</button>
            <button class="slider-btn slider-next" data-direction="next">›</button>
        </div>
    </div>
    <div class="slider-track">
        <div class="slide" style="width: 374px; !important;">
            <div class="room-image" style="background-image:url('{{ asset('images/kamardeluxe.jpg') }}')">
                <span class="room-badge">Popular</span>
            </div>
            <h4>Suite Deluxe</h4>
            <p>Ruang luas 45m², pemandangan kota, dan layanan premium dengan balkon pribadi.</p>
            <div class="slide-footer">
                <a href="{{ route('kamar') }}?jenis=Suite" class="btn-outline btn-sm">Lihat Detail</a>
            </div>
        </div>
        <div class="slide" style="width: 374px; !important;">
            <div class="room-image" style="background-image:url('{{ asset('images/kamarsuperior.jpg') }}')"></div>
            <h4>Kamar Superior</h4>
            <p>Desain modern 35m² dengan king bed dan kenyamanan optimal untuk istirahat Anda.</p>
            <div class="slide-footer">
                <a href="{{ route('kamar') }}?jenis=Deluxe" class="btn-outline btn-sm">Lihat Detail</a>
            </div>
        </div>
        <div class="slide" style="width: 374px; !important;">
            <div class="room-image" style="background-image:url('{{ asset('images/kamarstandar.jpg') }}')"></div>
            <h4>Kamar Standar</h4>
            <p>Solusi nyaman 25m² dengan twin/double bed dan harga terjangkau.</p>
            <div class="slide-footer">
                <a href="{{ route('kamar') }}?jenis=Standard" class="btn-outline btn-sm">Lihat Detail</a>
            </div>
        </div>
    </div>
    <div class="slider-cta">
        <a href="{{ route('kamar') }}" class="btn-outline">Lihat Semua Kamar →</a>
    </div>
</section>

<div class="section-sep" style="margin-top:40px">
    <h2>FASILITAS</h2>
    <div class="accent"></div>
    <p>Fasilitas lengkap untuk kenyamanan dan kebutuhan Anda selama menginap.</p>
</div>

<section class="container js-slider" data-slider="facilities">
    <div style="display:flex; justify-content:space-between; align-items:center">
        <h3>Fasilitas Unggulan</h3>
        <div class="slider-controls">
            <button class="slider-btn slider-prev" data-direction="prev">‹</button>
            <button class="slider-btn slider-next" data-direction="next">›</button>
        </div>
    </div>
    <div class="slider-track">
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/spa.jpg') }}')"></div>
            <h4>Spa & Wellness</h4>
            <p>Perawatan relaksasi dan pijat profesional dengan aromaterapi pilihan.</p>
        </div>
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/pool.jpg') }}')"></div>
            <h4>Kolam Renang</h4>
            <p>Kolam outdoor infinity dengan area berjemur dan poolside bar.</p>
        </div>
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/gym.jpg') }}')"></div>
            <h4>Fitness Center</h4>
            <p>Peralatan gym modern 24 jam dengan personal trainer on request.</p>
        </div>
    </div>
    <div class="slider-cta">
        <a href="{{ route('fasilitas') }}" class="btn-outline">Lihat Semua Fasilitas →</a>
    </div>
</section>

<div class="section-sep" style="margin-top:40px">
    <h2>RESTO & CAFE</h2>
    <div class="accent"></div>
    <p>Kuliner pilihan dari chef berpengalaman — sajian kasual hingga fine dining.</p>
</div>

<section class="container js-slider" data-slider="dining" style="margin-bottom:60px">
    <div style="display:flex; justify-content:space-between; align-items:center">
        <h3>Tempat Makan Kami</h3>
        <div class="slider-controls">
            <button class="slider-btn slider-prev" data-direction="prev">‹</button>
            <button class="slider-btn slider-next" data-direction="next">›</button>
        </div>
    </div>
    <div class="slider-track">
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/cafelobby.jpg') }}')"></div>
            <h4>Lobby Lounge</h4>
            <p>Kopi spesial, teh premium, dan kudapan ringan dalam suasana elegan.</p>
        </div>
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/restoranutama.jpg') }}')"></div>
            <h4>The Grand Restaurant</h4>
            <p>Menu internasional dan hidangan lokal dengan chef berpengalaman.</p>
        </div>
        <div class="slide">
            <div class="room-image" style="height:180px; background-image:url('{{ asset('images/kantin.jpg') }}')"></div>
            <h4>24H Cafe</h4>
            <p>Pilihan makanan dan minuman tersedia kapan saja, 24 jam.</p>
        </div>
    </div>
    <div class="slider-cta">
        <a href="{{ route('resto') }}" class="btn-outline">Lihat Menu & Tempat Makan →</a>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Siap untuk Pengalaman Menginap Terbaik?</h2>
            <p>Pesan kamar sekarang dan nikmati berbagai keuntungan eksklusif.</p>
            <div class="cta-buttons">
                <a href="{{ route('reservasi') }}" class="btn-reserve" style="text-decoration: none !important;">Reservasi Sekarang</a>
                <a href="{{ route('kontak') }}" class="btn-outline" style="border-color:#fff; color:#fff">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Slider
    const heroTrack = document.getElementById('heroTrack');
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroPrev = document.getElementById('heroPrev');
    const heroNext = document.getElementById('heroNext');
    const heroDots = document.querySelectorAll('.hero-dot');
    let currentSlide = 0;
    let heroInterval;

    function goToSlide(index) {
        heroSlides.forEach(slide => slide.classList.remove('active'));
        heroDots.forEach(dot => dot.classList.remove('active'));

        currentSlide = index;
        if (currentSlide >= heroSlides.length) currentSlide = 0;
        if (currentSlide < 0) currentSlide = heroSlides.length - 1;

        heroSlides[currentSlide].classList.add('active');
        heroDots[currentSlide].classList.add('active');
    }

    function nextSlide() {
        goToSlide(currentSlide + 1);
    }

    function prevSlide() {
        goToSlide(currentSlide - 1);
    }

    function startAutoSlide() {
        heroInterval = setInterval(nextSlide, 5000);
    }

    function stopAutoSlide() {
        clearInterval(heroInterval);
    }

    heroNext.addEventListener('click', () => {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
    });

    heroPrev.addEventListener('click', () => {
        stopAutoSlide();
        prevSlide();
        startAutoSlide();
    });

    heroDots.forEach(dot => {
        dot.addEventListener('click', () => {
            stopAutoSlide();
            goToSlide(parseInt(dot.dataset.slide));
            startAutoSlide();
        });
    });

    startAutoSlide();

    // Content Sliders
    document.querySelectorAll('.js-slider').forEach(slider => {
        const track = slider.querySelector('.slider-track');
        const prevBtn = slider.querySelector('.slider-prev');
        const nextBtn = slider.querySelector('.slider-next');

        if (track && prevBtn && nextBtn) {
            const scrollAmount = 350;

            nextBtn.addEventListener('click', () => {
                track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            });

            prevBtn.addEventListener('click', () => {
                track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            });
        }
    });
});
</script>
@endsection
