@extends('layouts.app')

@section('title','Kamar & Suite')

@section('content')
<div class="section-sep">
    <h2>KAMAR & SUITE</h2>
    <div class="accent"></div>
    <p>Pilihan kamar kami dirancang untuk memenuhi segala kebutuhan perjalanan Anda — mulai dari kenyamanan hingga kemewahan.</p>
</div>

<section class="container" style="padding:40px 0 80px">
    <!-- Search & Filter Bar -->
    <div class="search-filter-bar">
        <form action="{{ route('kamar') }}" method="GET" class="search-form">
            <div class="search-input-wrapper">
                <x-lucide-search class="search-icon" />
                <input type="text" name="search" class="search-input" placeholder="Cari kamar..." value="{{ request('search') }}">
            </div>
            <select name="jenis" class="filter-select">
                <option value="">Semua Jenis</option>
                @foreach($jenisKamarList as $jenis)
                <option value="{{ $jenis }}" {{ request('jenis') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                @endforeach
            </select>
            <select name="status" class="filter-select">
                <option value="">Semua Status</option>
                <option value="tersedia" {{ request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="dipesan" {{ request('status') == 'dipesan' ? 'selected' : '' }}>Dipesan</option>
                <option value="terisi" {{ request('status') == 'terisi' ? 'selected' : '' }}>Terisi</option>
            </select>
            <button type="submit" class="btn-filter">
                <x-lucide-filter class="lucide-icon-inline" /> Filter
            </button>
            @if(request('search') || request('jenis') || request('status'))
            <a href="{{ route('kamar') }}" class="btn-reset">
                <x-lucide-x class="lucide-icon-inline" /> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Results Info -->
    @if(request('search') || request('jenis') || request('status'))
    <div class="search-results-info">
        <span>Menampilkan {{ $kamars->total() }} hasil</span>
        @if(request('search'))
        <span class="search-tag">Pencarian: "{{ request('search') }}"</span>
        @endif
        @if(request('jenis'))
        <span class="search-tag">Jenis: {{ request('jenis') }}</span>
        @endif
        @if(request('status'))
        <span class="search-tag">Status: {{ request('status') }}</span>
        @endif
    </div>
    @endif

    <div class="kamar-grid">
        @forelse($kamars as $kamar)
        @php
            $roomImages = [
                'Standard' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800',
                'Deluxe' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=800',
                'Suite' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800',
                'Presidential Suite' => 'https://images.unsplash.com/photo-1578683010236-d716f9a3f461?w=800',
            ];
            $imgUrl = $kamar->gambar ? asset('storage/' . $kamar->gambar) : ($roomImages[$kamar->jenis_kamar] ?? 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800');
        @endphp
        <div class="info-card" style="padding:0; overflow:hidden">
            <div style="height:200px; background:url('{{ $imgUrl }}') center/cover no-repeat"></div>
            <div style="padding:20px">
                <div style="display:flex; justify-content:space-between; align-items:start; margin-bottom:10px">
                    <div>
                        <h4 style="margin:0">{{ $kamar->jenis_kamar }}</h4>
                        <small style="color:#666">Kamar No. {{ $kamar->no_kamar }}</small>
                    </div>
                    @php
                        $statusColors = [
                            'tersedia' => '#28a745',
                            'dipesan' => '#ffc107',
                            'terisi' => '#dc3545',
                            'maintenance' => '#6c757d',
                        ];
                    @endphp
                    <span style="padding:4px 10px; background:{{ $statusColors[$kamar->status] ?? '#666' }}; color:#fff; border-radius:20px; font-size:11px; text-transform:uppercase">
                        {{ $kamar->status }}
                    </span>
                </div>
                <p style="color:#C8A97E; font-size:18px; font-weight:600; margin:12px 0">
                    Rp {{ number_format($kamar->harga, 0, ',', '.') }} <small style="font-weight:400; color:#666">/ malam</small>
                </p>
                <a href="{{ route('kamar.show', $kamar->no_kamar) }}" class="btn-reserve" style="display:block; text-align:center; margin-top:16px; background:#333; border-color:#333; text-decoration:none">Lihat Detail</a>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; text-align:center; padding:60px; color:#666">
            <p style="font-size:48px; margin:0"><x-lucide-building class="lucide-icon-feature" /></p>
            @if(request('search') || request('jenis') || request('status'))
            <h3>Tidak Ada Hasil</h3>
            <p>Tidak ditemukan kamar yang sesuai dengan pencarian Anda.</p>
            <a href="{{ route('kamar') }}" class="btn-reserve" style="display:inline-block; margin-top:16px; text-decoration:none">Lihat Semua Kamar</a>
            @else
            <h3>Belum Ada Data Kamar</h3>
            <p>Kamar akan segera tersedia. Silakan kembali lagi nanti.</p>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($kamars->hasPages())
    <div class="pagination-wrapper">
        {{ $kamars->links('vendor.pagination.simple') }}
    </div>
    @endif
</section>
@endsection
