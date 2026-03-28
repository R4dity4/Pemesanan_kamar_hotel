@extends('layouts.app')

@section('title','Aktivitas')

@section('content')
<div class="section-sep">
    <h2>AKTIVITAS UNGGULAN</h2>
    <div class="accent"></div>
    <p>Nikmati berbagai aktivitas menarik yang telah kami siapkan untuk mengisi waktu menginap Anda.</p>
</div>

<section class="container" style="margin-top: 40px;">
    <div class="activity-grid">
        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/hotel3.jpg') }}')">
                <span class="activity-badge">Populer</span>
            </div>
            <div class="activity-content">
                <h4>Tur Kota</h4>
                <p>Program tur bersama pemandu berlisensi untuk mengeksplorasi destinasi terbaik di sekitar kota. Kunjungi landmark bersejarah, pasar tradisional, dan tempat-tempat menarik lainnya.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 4-6 jam</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Maks: 10 orang/grup</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: 08:00 & 14:00</li>
                </ul>
            </div>
        </div>

        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/restoranutama.jpg') }}')"></div>
            <div class="activity-content">
                <h4>Cooking Class</h4>
                <p>Belajar membuat hidangan lokal bersama chef kami. Pelajari resep tradisional dan teknik memasak profesional dalam suasana yang menyenangkan.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 2-3 jam</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Maks: 6 orang/sesi</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: 10:00 & 15:00</li>
                </ul>
            </div>
        </div>

        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/spa.jpg') }}')"></div>
            <div class="activity-content">
                <h4>Yoga & Wellness</h4>
                <p>Sesi yoga pagi dan kelas kebugaran berfokus pada pemulihan energi dan kesejahteraan. Dipandu oleh instruktur bersertifikat internasional.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 1-1.5 jam</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Maks: 15 orang/sesi</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: 06:30 & 17:00</li>
                </ul>
            </div>
        </div>

        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/pool.jpg') }}')"></div>
            <div class="activity-content">
                <h4>Aqua Fitness</h4>
                <p>Olahraga menyegarkan di kolam renang dengan instruktur profesional. Cocok untuk semua usia dan tingkat kebugaran.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 45 menit</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Maks: 12 orang/sesi</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: 07:00 & 16:00</li>
                </ul>
            </div>
        </div>

        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/gym.jpg') }}')"></div>
            <div class="activity-content">
                <h4>Personal Training</h4>
                <p>Latihan personal dengan trainer berpengalaman di fitness center kami. Program disesuaikan dengan kebutuhan dan tujuan Anda.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 1 jam</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Private session</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: Fleksibel</li>
                </ul>
            </div>
        </div>

        <div class="activity-card">
            <div class="activity-image" style="background-image:url('{{ asset('images/cafelobby.jpg') }}')"></div>
            <div class="activity-content">
                <h4>Wine & Coffee Tasting</h4>
                <p>Eksplorasi cita rasa wine pilihan dan kopi premium dari berbagai daerah bersama sommelier dan barista profesional kami.</p>
                <ul class="activity-details">
                    <li><svg class="lucide-icon-inline"><use href="#i-timer"/></svg> Durasi: 1.5 jam</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-users"/></svg> Maks: 8 orang/sesi</li>
                    <li><svg class="lucide-icon-inline"><use href="#i-clock"/></svg> Jadwal: 16:00 & 19:00</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Booking Info -->
<section class="activity-booking">
    <div class="container">
        <div class="booking-info-card">
            <div class="booking-info-content">
                <h3>Tertarik dengan Aktivitas Kami?</h3>
                <p>Hubungi concierge atau reservasi melalui resepsionis untuk mendaftar aktivitas yang Anda minati. Beberapa aktivitas memerlukan reservasi minimal 24 jam sebelumnya.</p>
            </div>
            <div class="booking-info-action">
                <a href="{{ route('kontak') }}" class="btn-reserve" style="text-decoration:none">Hubungi</a>
            </div>
        </div>
    </div>
</section>
@endsection
