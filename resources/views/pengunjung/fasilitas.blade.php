@extends('layouts.app')

@section('title','Fasilitas')

@section('content')
<div class="section-sep">
    <h2>FASILITAS UNGGULAN</h2>
    <div class="accent"></div>
    <p>Fasilitas modern dengan standar internasional untuk memenuhi kebutuhan Anda.</p>
</div>

<!-- Main Facilities -->
<section class="container" style="padding-bottom:60px">
    <div class="facility-showcase">
        <!-- Spa -->
        <div class="facility-item">
            <div class="facility-image" style="background-image:url('{{ asset('images/spa.jpg') }}')"></div>
            <div class="facility-info">
                <h3>Spa & Wellness Center</h3>
                <p>Pusat perawatan tubuh dan pikiran dengan berbagai treatment premium. Nikmati pijat relaksasi, aromaterapi, facial treatment, dan body scrub dengan terapis berpengalaman.</p>
                <ul class="facility-features">
                    <li><x-lucide-check class="lucide-icon-check" /> Massage Tradisional & Modern</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Aromatherapy Treatment</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Facial & Body Care</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Sauna & Steam Room</li>
                </ul>
                <div class="facility-hours">
                    <span><x-lucide-clock class="lucide-icon-inline" /> Jam Operasional: 09:00 - 21:00</span>
                </div>
            </div>
        </div>

        <!-- Pool -->
        <div class="facility-item reverse">
            <div class="facility-image" style="background-image:url('{{ asset('images/pool.jpg') }}')"></div>
            <div class="facility-info">
                <h3>Infinity Swimming Pool</h3>
                <p>Kolam renang outdoor dengan pemandangan kota yang menakjubkan. Dilengkapi dengan area berjemur, poolside bar, dan jacuzzi untuk relaksasi maksimal.</p>
                <ul class="facility-features">
                    <li><x-lucide-check class="lucide-icon-check" /> Kolam Dewasa & Anak</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Poolside Bar & Service</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Sunbed & Cabana</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Jacuzzi & Hot Tub</li>
                </ul>
                <div class="facility-hours">
                    <span><x-lucide-clock class="lucide-icon-inline" /> Jam Operasional: 06:00 - 21:00</span>
                </div>
            </div>
        </div>

        <!-- Gym -->
        <div class="facility-item">
            <div class="facility-image" style="background-image:url('{{ asset('images/gym.jpg') }}')"></div>
            <div class="facility-info">
                <h3>Fitness Center</h3>
                <p>Gym modern dengan peralatan lengkap dari Technogym. Tersedia personal trainer bersertifikat untuk membantu mencapai target kebugaran Anda.</p>
                <ul class="facility-features">
                    <li><x-lucide-check class="lucide-icon-check" /> Cardio Equipment</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Weight Training Area</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Personal Training Available</li>
                    <li><x-lucide-check class="lucide-icon-check" /> Yoga & Aerobic Studio</li>
                </ul>
                <div class="facility-hours">
                    <span><x-lucide-clock class="lucide-icon-inline" /> Jam Operasional: 24 Jam</span>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep">
    <h2>FASILITAS LAINNYA</h2>
    <div class="accent"></div>
</div>

<!-- Additional Facilities Grid -->
<section class="container" style="padding-bottom:80px">
    <div class="facilities-grid">
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-building-2 class="lucide-icon-feature" /></div>
            <h4>Business Center</h4>
            <p>Ruang kerja dengan komputer, printer, dan koneksi internet berkecepatan tinggi.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-party-popper class="lucide-icon-feature" /></div>
            <h4>Ballroom & Meeting</h4>
            <p>Ruang pertemuan dan ballroom untuk acara bisnis, seminar, dan pernikahan.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-car class="lucide-icon-feature" /></div>
            <h4>Parkir Luas</h4>
            <p>Area parkir yang aman dengan kapasitas besar dan sistem keamanan 24 jam.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-wifi class="lucide-icon-feature" /></div>
            <h4>WiFi Gratis</h4>
            <p>Koneksi internet berkecepatan tinggi gratis di seluruh area hotel.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-bell-ring class="lucide-icon-feature" /></div>
            <h4>Concierge 24 Jam</h4>
            <p>Layanan concierge profesional siap membantu kebutuhan Anda kapan saja.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-shirt class="lucide-icon-feature" /></div>
            <h4>Laundry Service</h4>
            <p>Layanan laundry dan dry cleaning dengan hasil berkualitas dan cepat.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-bus class="lucide-icon-feature" /></div>
            <h4>Airport Transfer</h4>
            <p>Layanan antar-jemput bandara dengan kendaraan nyaman dan sopir profesional.</p>
        </div>
        <div class="facility-card">
            <div class="facility-card-icon"><x-lucide-landmark class="lucide-icon-feature" /></div>
            <h4>ATM Center</h4>
            <p>Tersedia ATM dari berbagai bank untuk kemudahan transaksi keuangan Anda.</p>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content">
            <h2>Rasakan Semua Fasilitas Premium Kami</h2>
            <p>Pesan kamar sekarang dan nikmati akses ke seluruh fasilitas hotel.</p>
            <div class="cta-buttons">
                <a href="{{ route('reservasi') }}" class="btn-reserve" style="text-decoration: none;">Reservasi Sekarang</a>
                <a href="{{ route('kamar') }}" class="btn-outline" style="border-color:#fff; color:#fff">Lihat Kamar</a>
            </div>
        </div>
    </div>
</section>
@endsection
