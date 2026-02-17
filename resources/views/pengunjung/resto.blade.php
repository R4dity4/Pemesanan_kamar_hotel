@extends('layouts.app')

@section('title','Resto & Cafe')

@section('content')
<div class="section-sep">
    <h2>TEMPAT MAKAN KAMI</h2>
    <div class="accent"></div>
    <p>Nikmati pengalaman kuliner terbaik dengan berbagai pilihan tempat makan di hotel kami.</p>
</div>

<!-- Dining Venues -->
<section class="container" style="padding-bottom:60px">
    <div class="dining-showcase">
        <!-- Main Restaurant -->
        <div class="dining-venue">
            <div class="dining-image" style="background-image:url('{{ asset('images/restoranutama.jpg') }}')">
                <span class="dining-badge">Fine Dining</span>
            </div>
            <div class="dining-info">
                <h3>The Grand Restaurant</h3>
                <p>Restoran utama kami menghadirkan pengalaman fine dining dengan menu internasional dan hidangan lokal terbaik. Chef berpengalaman kami menyajikan kreasi kuliner menggunakan bahan-bahan segar berkualitas premium.</p>

                <div class="dining-menu-preview">
                    <h5>Menu Unggulan:</h5>
                    <ul>
                        <li><x-lucide-utensils class="lucide-icon-inline" /> Nasi Goreng Signature HOTELX</li>
                        <li><x-lucide-utensils class="lucide-icon-inline" /> Grilled Australian Beef Tenderloin</li>
                        <li><x-lucide-utensils class="lucide-icon-inline" /> Seafood Platter Mediterranean</li>
                        <li><x-lucide-utensils class="lucide-icon-inline" /> Traditional Indonesian Rijsttafel</li>
                    </ul>
                </div>

                <div class="dining-details">
                    <div class="dining-detail-item">
                        <span class="label">Jam Buka</span>
                        <span class="value">06:30 - 23:00</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Kapasitas</span>
                        <span class="value">120 Kursi</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Dress Code</span>
                        <span class="value">Smart Casual</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lobby Lounge -->
        <div class="dining-venue reverse">
            <div class="dining-image" style="background-image:url('{{ asset('images/cafelobby.jpg') }}')">
                <span class="dining-badge">Cafe</span>
            </div>
            <div class="dining-info">
                <h3>Lobby Lounge & Cafe</h3>
                <p>Tempat yang sempurna untuk bersantai sambil menikmati kopi premium, teh pilihan, dan kudapan ringan. Suasana elegan dengan live music setiap akhir pekan.</p>

                <div class="dining-menu-preview">
                    <h5>Menu Favorit:</h5>
                    <ul>
                        <li><x-lucide-coffee class="lucide-icon-inline" /> Specialty Coffee & Latte Art</li>
                        <li><x-lucide-cake class="lucide-icon-inline" /> Pastries & Artisan Cakes</li>
                        <li><x-lucide-sandwich class="lucide-icon-inline" /> Gourmet Sandwiches</li>
                        <li><x-lucide-glass-water class="lucide-icon-inline" /> Afternoon Tea Set</li>
                    </ul>
                </div>

                <div class="dining-details">
                    <div class="dining-detail-item">
                        <span class="label">Jam Buka</span>
                        <span class="value">07:00 - 22:00</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Live Music</span>
                        <span class="value">Jumat - Minggu</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Dress Code</span>
                        <span class="value">Casual</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 24H Cafe -->
        <div class="dining-venue">
            <div class="dining-image" style="background-image:url('{{ asset('images/kantin.jpg') }}')">
                <span class="dining-badge">24 Jam</span>
            </div>
            <div class="dining-info">
                <h3>24H Cafe & Deli</h3>
                <p>Pilihan makanan dan minuman tersedia kapan saja, 24 jam. Tempat yang ideal untuk tamu yang membutuhkan makan tengah malam atau sarapan pagi-pagi sekali.</p>

                <div class="dining-menu-preview">
                    <h5>Tersedia 24 Jam:</h5>
                    <ul>
                        <li><x-lucide-soup class="lucide-icon-inline" /> Mie & Nasi Goreng</li>
                        <li><x-lucide-beef class="lucide-icon-inline" /> Burger & Snacks</li>
                        <li><x-lucide-cup-soda class="lucide-icon-inline" /> Minuman Segar & Kopi</li>
                        <li><x-lucide-ice-cream-cone class="lucide-icon-inline" /> Es Krim & Dessert</li>
                    </ul>
                </div>

                <div class="dining-details">
                    <div class="dining-detail-item">
                        <span class="label">Jam Buka</span>
                        <span class="value">24 Jam</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Delivery</span>
                        <span class="value">Ke Kamar</span>
                    </div>
                    <div class="dining-detail-item">
                        <span class="label">Dress Code</span>
                        <span class="value">Casual</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-sep">
    <h2>LAYANAN SPESIAL</h2>
    <div class="accent"></div>
</div>

<!-- Special Services -->
<section class="container" style="padding-bottom:80px">
    <div class="dining-services-grid">
        <div class="dining-service-card">
            <div class="service-icon"><x-lucide-utensils class="lucide-icon-feature" /></div>
            <h4>Room Service</h4>
            <p>Nikmati hidangan lezat langsung di kenyamanan kamar Anda dengan layanan room service 24 jam.</p>
        </div>
        <div class="dining-service-card">
            <div class="service-icon"><x-lucide-cake class="lucide-icon-feature" /></div>
            <h4>Private Dining</h4>
            <p>Ruang makan privat untuk acara khusus dengan menu custom sesuai selera Anda.</p>
        </div>
        <div class="dining-service-card">
            <div class="service-icon"><x-lucide-wine class="lucide-icon-feature" /></div>
            <h4>Wine Pairing</h4>
            <p>Sommelier kami siap membantu memilih wine terbaik untuk melengkapi hidangan Anda.</p>
        </div>
        <div class="dining-service-card">
            <div class="service-icon"><x-lucide-party-popper class="lucide-icon-feature" /></div>
            <h4>Catering Event</h4>
            <p>Layanan catering profesional untuk meeting, seminar, dan acara spesial lainnya.</p>
        </div>
    </div>
</section>

<!-- Reservation CTA -->
<section class="dining-cta">
    <div class="container">
        <div class="dining-cta-content">
            <h2>Reservasi Meja</h2>
            <p>Untuk reservasi meja di restoran atau informasi lebih lanjut tentang layanan kuliner kami, silakan hubungi:</p>
            <div class="dining-contact">
                <span><x-lucide-phone class="lucide-icon-inline" /> (021) 1234-5678</span>
                <span><x-lucide-mail class="lucide-icon-inline" /> dining@hotelx.com</span>
            </div><br>
            <div>
            <a href="{{ route('kontak') }}" class="btn-reserve" style="margin-top:20px; text-decoration: none;">Hubungi Kami</a>
            </div>
        </div>
    </div>
</section>
@endsection
