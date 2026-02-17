@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')
<div class="page-header">
    <a href="/admin/pesan" class="btn btn-secondary"><x-lucide-arrow-left class="lucide-icon-btn" /> Kembali</a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><x-lucide-mail class="lucide-icon-inline" /> Detail Pesan</h3>
        <div class="header-actions">
            <form action="/admin/pesan/{{ $pesan->id }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger"><x-lucide-trash-2 class="lucide-icon-btn" /> Hapus</button>
            </form>
        </div>
    </div>

    <div class="pesan-detail">
        <div class="pesan-info-grid">
            <div class="info-item">
                <label>Dari</label>
                <div class="info-value">
                    <strong>{{ $pesan->nama }}</strong>
                </div>
            </div>
            <div class="info-item">
                <label>Email</label>
                <div class="info-value">
                    <a href="mailto:{{ $pesan->email }}">{{ $pesan->email }}</a>
                </div>
            </div>
            <div class="info-item">
                <label>Telepon</label>
                <div class="info-value">
                    @if($pesan->telepon)
                    <a href="tel:{{ $pesan->telepon }}">{{ $pesan->telepon }}</a>
                    @else
                    <span style="color:#999">-</span>
                    @endif
                </div>
            </div>
            <div class="info-item">
                <label>Topik</label>
                <div class="info-value">
                    <span class="badge badge-{{ $pesan->topik }}">{{ ucfirst($pesan->topik) }}</span>
                </div>
            </div>
            <div class="info-item">
                <label>Tanggal</label>
                <div class="info-value">
                    {{ $pesan->created_at->format('d M Y, H:i') }}
                    <small style="color:#999">({{ $pesan->created_at->diffForHumans() }})</small>
                </div>
            </div>
            <div class="info-item">
                <label>Status</label>
                <div class="info-value">
                    @if($pesan->dibaca)
                    <span class="badge badge-success">Sudah Dibaca</span>
                    @else
                    <span class="badge badge-warning">Belum Dibaca</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="pesan-body">
            <label>Isi Pesan</label>
            <div class="pesan-content-box">
                {!! nl2br(e($pesan->pesan)) !!}
            </div>
        </div>

        <div class="pesan-actions-box">
            <h4>Balas Pesan</h4>
            <div class="reply-buttons">
                <a href="mailto:{{ $pesan->email }}?subject=Re: {{ ucfirst($pesan->topik) }} - HOTELX" class="btn btn-primary">
                    <x-lucide-mail class="lucide-icon-btn" /> Balas via Email
                </a>
                @if($pesan->telepon)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesan->telepon) }}" target="_blank" class="btn btn-success">
                    <x-lucide-message-circle class="lucide-icon-btn" /> Balas via WhatsApp
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    margin-bottom: 20px;
}

.pesan-detail {
    padding: 24px;
}

.pesan-info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
    padding-bottom: 24px;
    border-bottom: 1px solid #eee;
}

@media (max-width: 768px) {
    .pesan-info-grid { grid-template-columns: 1fr 1fr; }
}

.info-item label {
    display: block;
    font-size: 12px;
    color: #999;
    margin-bottom: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item .info-value {
    font-size: 15px;
}

.info-item a {
    color: #b78f5a;
    text-decoration: none;
}

.info-item a:hover {
    text-decoration: underline;
}

.pesan-body {
    margin-bottom: 24px;
}

.pesan-body > label {
    display: block;
    font-size: 12px;
    color: #999;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.pesan-content-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    line-height: 1.8;
    font-size: 15px;
}

.pesan-actions-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
}

.pesan-actions-box h4 {
    margin: 0 0 16px;
    font-size: 14px;
    color: #666;
}

.reply-buttons {
    display: flex;
    gap: 12px;
}

.badge-umum { background: #6c757d; color: #fff; }
.badge-reservasi { background: #17a2b8; color: #fff; }
.badge-acara { background: #6f42c1; color: #fff; }
.badge-kerjasama { background: #28a745; color: #fff; }
.badge-lainnya { background: #ffc107; color: #333; }
.badge-success { background: #28a745; color: #fff; }
.badge-warning { background: #ffc107; color: #333; }
</style>
@endsection
