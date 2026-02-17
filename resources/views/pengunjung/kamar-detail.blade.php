@extends('layouts.app')

@section('title', $kamar->jenis_kamar . ' - Kamar ' . $kamar->no_kamar)

@section('content')
@php
    $roomImages = [
        'Standard' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=1200',
        'Deluxe' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=1200',
        'Suite' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=1200',
        'Presidential Suite' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=1200',
    ];
    $imgUrl = $kamar->gambar ? asset('storage/' . $kamar->gambar) : ($roomImages[$kamar->jenis_kamar] ?? $roomImages['Standard']);

    $statusColors = [
        'tersedia' => '#28a745',
        'dipesan' => '#ffc107',
        'terisi' => '#dc3545',
        'maintenance' => '#6c757d',
    ];
@endphp

<!-- Hero Image -->
<div class="room-hero" style="background-image:url('{{ $imgUrl }}')">
    <div class="room-hero-overlay"></div>
    <div class="container room-hero-content">
        <a href="/kamar" class="back-link"><x-lucide-arrow-left class="lucide-icon-inline" /> Kembali ke Daftar Kamar</a>
        <h1>{{ $kamar->jenis_kamar }}</h1>
        <p>Kamar No. {{ $kamar->no_kamar }}</p>
    </div>
</div>

<section class="container" style="padding:60px 0">
    <div class="room-detail-grid">
        <!-- Main Content -->
        <div>
            <!-- Status & Price -->
            <div class="room-status-price">
                <span class="room-status" style="background:{{ $statusColors[$kamar->status] ?? '#666' }}">
                    {{ $kamar->status }}
                </span>
                <span class="room-price">
                    Rp {{ number_format($kamar->harga, 0, ',', '.') }} <small>/ malam</small>
                </span>
            </div>

            <!-- Description -->
            <div class="info-card" style="height:auto; margin-bottom:24px">
                <h4>Deskripsi</h4>
                <p style="line-height:1.8; color:#666">
                    @if($kamar->deskripsi)
                        {{ $kamar->deskripsi }}
                    @else
                        @switch($kamar->jenis_kamar)
                            @case('Standard')
                                Kamar Standard kami menawarkan kenyamanan optimal dengan desain modern dan minimalis. Dilengkapi dengan tempat tidur queen-size yang nyaman, kamar mandi dengan shower, dan pemandangan kota yang menenangkan. Cocok untuk wisatawan bisnis atau pasangan yang mencari akomodasi berkualitas dengan harga terjangkau.
                                @break
                            @case('Deluxe')
                                Kamar Deluxe memberikan pengalaman menginap yang lebih luas dan mewah. Dengan tempat tidur king-size premium, area duduk yang nyaman, dan kamar mandi dengan bathtub terpisah. Nikmati pemandangan kota yang menakjubkan dari jendela besar dan akses ke fasilitas eksklusif hotel.
                                @break
                            @case('Suite')
                                Suite kami adalah pilihan sempurna untuk tamu yang menginginkan ruang lebih dan kemewahan. Terdiri dari ruang tamu terpisah, kamar tidur master dengan kasur super king-size, dan kamar mandi mewah dengan jacuzzi. Ideal untuk keluarga atau tamu yang menginginkan pengalaman premium.
                                @break
                            @case('Presidential Suite')
                                Presidential Suite adalah puncak kemewahan di hotel kami. Menempati seluruh lantai dengan 3 kamar tidur, ruang tamu luas, ruang makan privat, dapur lengkap, dan balkon panoramik. Dilengkapi butler service 24 jam dan akses eksklusif ke semua fasilitas VIP hotel.
                                @break
                            @default
                                Kamar yang nyaman dengan fasilitas lengkap untuk memenuhi kebutuhan Anda selama menginap di hotel kami.
                        @endswitch
                    @endif
                </p>
            </div>

            <!-- Fasilitas Kamar -->
            <div class="info-card" style="height:auto; margin-bottom:24px">
                <h4>Fasilitas Kamar</h4>
                <div class="fasilitas-grid">
                    @php
                        $fasilitasBase = ['Wi-Fi Gratis', 'AC Individual', 'TV 55" Smart TV', 'Safety Box', 'Telepon', 'Sandal & Jubah'];
                        $fasilitasDeluxe = array_merge($fasilitasBase, ['Minibar', 'Mesin Kopi', 'Bathtub']);
                        $fasilitasSuite = array_merge($fasilitasDeluxe, ['Jacuzzi', 'Ruang Tamu', 'Dapur Kecil']);
                        $fasilitasPresidential = array_merge($fasilitasSuite, ['3 Kamar Tidur', 'Ruang Makan', 'Balkon Privat', 'Home Theater']);

                        $fasilitas = match($kamar->jenis_kamar) {
                            'Deluxe' => $fasilitasDeluxe,
                            'Suite' => $fasilitasSuite,
                            'Presidential Suite' => $fasilitasPresidential,
                            default => $fasilitasBase,
                        };
                    @endphp
                    @foreach($fasilitas as $f)
                    <div class="fasilitas-item">
                        <x-lucide-check class="lucide-icon-check" />
                        <span>{{ $f }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Layanan Tambahan -->
            <div class="info-card" style="height:auto">
                <h4>Layanan Tambahan</h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-top:16px">
                    @php
                        $layananBase = ['Room Service 24 Jam', 'Laundry Express', 'Wake-up Call'];
                        $layananDeluxe = array_merge($layananBase, ['Antar-Jemput Bandara', 'Late Check-out']);
                        $layananSuite = array_merge($layananDeluxe, ['Butler Service', 'Spa In-Room']);
                        $layananPresidential = array_merge($layananSuite, ['Private Chef', 'Concierge Eksklusif', 'Limousine Service']);

                        $layanan = match($kamar->jenis_kamar) {
                            'Deluxe' => $layananDeluxe,
                            'Suite' => $layananSuite,
                            'Presidential Suite' => $layananPresidential,
                            default => $layananBase,
                        };
                    @endphp
                    @foreach($layanan as $l)
                    <div style="display:flex; align-items:center; gap:10px; padding:8px 0">
                        <span style="color:var(--accent)">★</span>
                        <span>{{ $l }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div>
            <div class="info-card" style="height:auto; position:sticky; top:24px">
                <h4>Reservasi Kamar Ini</h4>
                @if($kamar->status == 'tersedia')
                <p style="color:#666; font-size:14px; margin:12px 0 20px">Kamar ini tersedia untuk dipesan. Klik tombol di bawah untuk melanjutkan ke halaman reservasi.</p>
                <a href="{{ route('reservasi') }}?kamar={{ $kamar->no_kamar }}" class="btn-reserve" style="display:block; text-align:center; width:100%">Pesan Sekarang</a>
                @else
                <p style="color:#666; font-size:14px; margin:12px 0 20px">Maaf, kamar ini sedang tidak tersedia. Silakan pilih kamar lain atau hubungi kami untuk informasi lebih lanjut.</p>
                <button disabled style="display:block; width:100%; padding:14px; background:#ccc; color:#666; border:none; border-radius:4px; cursor:not-allowed">Tidak Tersedia</button>
                @endif

                <div style="margin-top:24px; padding-top:24px; border-top:1px solid #eee">
                    <p style="margin:0 0 8px; font-size:13px; color:#666">Butuh bantuan?</p>
                    <p style="margin:0; font-weight:600"><x-lucide-phone class="lucide-icon-inline" /> +62 00 2606 2007</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Kamar Lainnya -->
@if($kamarLain->count() > 0)
<section style="background:var(--bg); padding:60px 0">
    <div class="container">
        <h3 style="text-align:center; margin-bottom:32px">Kamar Lainnya</h3>
        <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:24px">
            @foreach($kamarLain as $km)
            @php
                $kmImg = $km->gambar ? asset('storage/' . $km->gambar) : ($roomImages[$km->jenis_kamar] ?? $roomImages['Standard']);
            @endphp
            <div class="info-card" style="padding:0; overflow:hidden">
                <div style="height:160px; background:url('{{ $kmImg }}') center/cover no-repeat"></div>
                <div style="padding:20px">
                    <h4 style="margin:0 0 4px">{{ $km->jenis_kamar }}</h4>
                    <small style="color:#666">Kamar No. {{ $km->no_kamar }}</small>
                    <p style="color:var(--accent); font-size:16px; font-weight:600; margin:12px 0">
                        Rp {{ number_format($km->harga, 0, ',', '.') }} <small style="font-weight:400; color:#666">/ malam</small>
                    </p>
                    <a href="{{ route('kamar.show', $km->no_kamar) }}" style="color:var(--accent); font-size:14px; font-weight:500">Lihat Detail →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
