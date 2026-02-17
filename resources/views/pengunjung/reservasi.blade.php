@extends('layouts.app')

@section('title','Reservasi')

@section('content')
<div class="section-sep">
    <h2>RESERVASI</h2>
    <div class="accent"></div>
    <p>Pesan kamar Anda sekarang. Isi formulir di bawah ini untuk melakukan pemesanan.</p>
</div>

<section class="container" style="padding:40px 0 80px">
    <div style="max-width:1100px; margin:0 auto">

        @if(request('success'))
        <div class="alert alert-success" style="text-align:center">
            <h3 style="margin:0 0 10px"><x-lucide-check-circle class="lucide-icon-inline" /> Reservasi Berhasil!</h3>
            <p style="margin:0">Reservasi Anda telah diterima.</p>
            <p style="margin:10px 0 0; font-size:14px">Silakan tunggu konfirmasi dari admin. <a href="/reservasi/cek?no_ktp={{ request('no_ktp') }}" style="color:#155724; font-weight:600">Cek Status Pesanan →</a></p>
        </div>
        @endif

        <!-- Multi-Step Form -->
        <div class="info-card" style="height:auto; margin-bottom:30px">

            <!-- Step Progress Bar -->
            <div class="step-progress">
                <div class="step-item active" data-step="1">
                    <div class="step-circle">1</div>
                    <span class="step-label">Data Diri</span>
                </div>
                <div class="step-connector"></div>
                <div class="step-item" data-step="2">
                    <div class="step-circle">2</div>
                    <span class="step-label">Pilih Kamar</span>
                </div>
                <div class="step-connector"></div>
                <div class="step-item" data-step="3">
                    <div class="step-circle">3</div>
                    <span class="step-label">Layanan</span>
                </div>
                <div class="step-connector"></div>
                <div class="step-item" data-step="4">
                    <div class="step-circle">4</div>
                    <span class="step-label">Konfirmasi</span>
                </div>
            </div>

            <form action="{{ route('reservasi.store') }}" method="POST" id="reservasiForm">
                @csrf

                {{-- ===== STEP 1: Data Diri ===== --}}
                <div class="step-panel active" data-step="1">
                    <div class="form-title" style="margin-top:0"><x-lucide-user class="lucide-icon-inline" /> Data Diri</div>
                    <div class="form-grid-3">
                        <div class="form-group">
                            <label>Nama Lengkap *</label>
                            <input type="text" name="nm_pengunjung" id="inp_nama" class="form-control" placeholder="Masukkan nama lengkap" value="{{ old('nm_pengunjung') }}" required>
                            @error('nm_pengunjung')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>No. KTP *</label>
                            <input type="text" name="no_ktp" id="inp_ktp" class="form-control" placeholder="Masukkan nomor KTP" value="{{ old('no_ktp') }}" required>
                            @error('no_ktp')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>No. Telepon *</label>
                            <input type="text" name="no_tlp" id="inp_tlp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_tlp') }}" required>
                        </div>
                    </div>
                    <div class="form-grid-3">
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Alamat Lengkap *</label>
                            <textarea name="alamat" id="inp_alamat" class="form-control" placeholder="Masukkan alamat lengkap" required style="min-height:60px">{{ old('alamat') }}</textarea>
                            @error('alamat')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>Jenis Kelamin *</label>
                            <select name="jk" id="inp_jk" class="form-control" required>
                                <option value="">-- Pilih --</option>
                                <option value="L" {{ old('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <div class="step-nav">
                        <div></div>
                        <button type="button" class="btn-step btn-step-next" onclick="goToStep(2)">
                            Selanjutnya <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ===== STEP 2: Pilih Kamar ===== --}}
                <div class="step-panel" data-step="2">
                    <div class="form-title" style="margin-top:0"><x-lucide-calendar class="lucide-icon-inline" /> Detail Reservasi</div>
                    <div class="form-grid-4">
                        <div class="form-group">
                            <label>Tanggal Check-in *</label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control" value="{{ old('tgl_masuk') }}" min="{{ date('Y-m-d') }}" required>
                            <small style="color:#666; font-size:12px">Check-in: 14:00 WIB</small>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Check-out *</label>
                            <input type="date" name="tgl_keluar" id="tgl_keluar" class="form-control" value="{{ old('tgl_keluar') }}" required>
                            <small style="color:#666; font-size:12px">Check-out: 12:00 WIB</small>
                        </div>
                        <div class="form-group" style="grid-column: span 2;">
                            <div id="availability-message" style="display:none; padding:12px 16px; border-radius:8px; font-size:14px; margin-top:20px;"></div>
                        </div>
                    </div>

                    <div class="form-title"><x-lucide-door-open class="lucide-icon-inline" /> Pilih Kamar <span id="kamar-loading" style="display:none; font-weight:normal; color:#666">(Mengecek...)</span></div>
                    <div class="kamar-list" id="kamar-list">
                        @forelse($kamars as $kamar)
                        <label class="kamar-item {{ $kamar->status !== 'tersedia' ? 'kamar-dipesan' : '' }}" data-no-kamar="{{ $kamar->no_kamar }}" data-harga="{{ $kamar->harga }}" data-jenis="{{ $kamar->jenis_kamar }}">
                            <input type="checkbox" name="kamar[]" value="{{ $kamar->no_kamar }}"
                                {{ is_array(old('kamar')) && in_array($kamar->no_kamar, old('kamar')) ? 'checked' : '' }}
                                {{ $kamar->status !== 'tersedia' ? 'disabled' : '' }}>
                            <div class="kamar-info">
                                <strong class="{{ $kamar->status !== 'tersedia' ? 'text-strikethrough' : '' }}">
                                    <x-lucide-door-open style="width:14px;height:14px;vertical-align:middle;margin-right:2px;color:var(--accent)" />
                                    No. {{ $kamar->no_kamar }} &middot; {{ $kamar->jenis_kamar }}
                                </strong>
                                <small class="{{ $kamar->status !== 'tersedia' ? 'text-strikethrough' : '' }}">
                                    Rp {{ number_format($kamar->harga, 0, ',', '.') }}<span style="font-weight:400;font-size:10px;opacity:0.85"> /malam</span>
                                </small>
                                @if($kamar->status !== 'tersedia')
                                <span class="badge-dipesan">Tidak Tersedia</span>
                                @endif
                            </div>
                        </label>
                        @empty
                        <div class="empty-state" style="grid-column: span 2;">
                            <x-lucide-bed-double class="empty-state-icon" />
                            <h4>Tidak Ada Kamar</h4>
                            <p>Belum ada kamar yang tersedia saat ini. Silakan coba lagi nanti.</p>
                        </div>
                        @endforelse
                    </div>
                    @error('kamar')<span class="error-text">{{ $message }}</span>@enderror

                    {{-- Price Estimation Panel --}}
                    <div class="price-panel" id="pricePanel">
                        <div class="price-panel-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            Estimasi Harga
                        </div>
                        <div id="priceLines">
                            <div class="price-line empty">Pilih kamar dan tanggal untuk melihat estimasi</div>
                        </div>
                        <div class="price-total">
                            <span class="price-total-label">Total Estimasi</span>
                            <div>
                                <span class="price-total-value" id="priceTotalValue">Rp 0</span>
                                <div class="price-nights" id="priceNights"></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-nav">
                        <button type="button" class="btn-step btn-step-prev" onclick="goToStep(1)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg> Kembali
                        </button>
                        <button type="button" class="btn-step btn-step-next" onclick="goToStep(3)">
                            Selanjutnya <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ===== STEP 3: Layanan Tambahan ===== --}}
                <div class="step-panel" data-step="3">
                    <div class="form-title" style="margin-top:0"><x-lucide-concierge-bell class="lucide-icon-inline" /> Layanan Tambahan <span style="font-weight:normal; color:#666">(Opsional)</span></div>
                    <p style="color:#666; font-size:13px; margin:-8px 0 16px">Tambahkan layanan ekstra untuk kenyamanan Anda. Lewati jika tidak diperlukan.</p>

                    @if(isset($layananTambahan) && $layananTambahan->count() > 0)
                    <div class="layanan-list">
                        @foreach($layananTambahan as $index => $layanan)
                        <div class="layanan-item" title="{{ $layanan->deskripsi }}" data-harga="{{ $layanan->harga }}" data-nama="{{ $layanan->nama_layanan }}">
                            <input type="hidden" name="layanan[{{ $index }}][id]" value="{{ $layanan->id }}">
                            <div class="layanan-info">
                                <strong>{{ $layanan->nama_layanan }}</strong>
                                <span class="layanan-harga">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="layanan-qty">
                                <button type="button" class="qty-btn minus" data-index="{{ $index }}">-</button>
                                <input type="number" name="layanan[{{ $index }}][jumlah]" class="qty-input" value="0" min="0" max="10" data-harga="{{ $layanan->harga }}">
                                <button type="button" class="qty-btn plus" data-index="{{ $index }}">+</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="empty-state">
                        <x-lucide-sparkles class="empty-state-icon" />
                        <h4>Tidak Ada Layanan Tambahan</h4>
                        <p>Belum ada layanan tambahan yang tersedia. Anda dapat langsung lanjut ke konfirmasi.</p>
                    </div>
                    @endif

                    {{-- Price Panel (updated with services) --}}
                    <div class="price-panel" id="pricePanelStep3">
                        <div class="price-panel-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            Estimasi Harga
                        </div>
                        <div id="priceLinesStep3"></div>
                        <div class="price-total">
                            <span class="price-total-label">Total Estimasi</span>
                            <div>
                                <span class="price-total-value" id="priceTotalStep3">Rp 0</span>
                                <div class="price-nights" id="priceNightsStep3"></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-nav">
                        <button type="button" class="btn-step btn-step-prev" onclick="goToStep(2)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg> Kembali
                        </button>
                        <button type="button" class="btn-step btn-step-next" onclick="goToStep(4)">
                            Review & Konfirmasi <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ===== STEP 4: Review & Konfirmasi ===== --}}
                <div class="step-panel" data-step="4">
                    <div class="form-title" style="margin-top:0"><x-lucide-clipboard-check class="lucide-icon-inline" /> Review Pesanan Anda</div>
                    <p style="color:#666; font-size:13px; margin:-8px 0 20px">Periksa kembali data Anda sebelum mengirim reservasi.</p>

                    {{-- Data Diri Review --}}
                    <div class="review-section">
                        <div class="review-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            Data Diri
                        </div>
                        <div class="review-grid">
                            <div class="review-item">
                                <span class="review-label">Nama</span>
                                <span class="review-value" id="rev_nama">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">No. KTP</span>
                                <span class="review-value" id="rev_ktp">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">No. Telepon</span>
                                <span class="review-value" id="rev_tlp">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Jenis Kelamin</span>
                                <span class="review-value" id="rev_jk">-</span>
                            </div>
                            <div class="review-item" style="grid-column: span 2;">
                                <span class="review-label">Alamat</span>
                                <span class="review-value" id="rev_alamat">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Reservasi Details Review --}}
                    <div class="review-section">
                        <div class="review-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            Detail Reservasi
                        </div>
                        <div class="review-grid">
                            <div class="review-item">
                                <span class="review-label">Check-in</span>
                                <span class="review-value" id="rev_checkin">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Check-out</span>
                                <span class="review-value" id="rev_checkout">-</span>
                            </div>
                            <div class="review-item">
                                <span class="review-label">Durasi</span>
                                <span class="review-value" id="rev_durasi">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Kamar Review --}}
                    <div class="review-section">
                        <div class="review-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v11a1 1 0 001 1h16a1 1 0 001-1V7"/><path d="M21 7H3l2-4h14l2 4z"/></svg>
                            Kamar Dipilih
                        </div>
                        <div class="review-rooms" id="rev_kamar">-</div>
                    </div>

                    {{-- Layanan Review --}}
                    <div class="review-section" id="rev_layanan_section" style="display:none">
                        <div class="review-section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                            Layanan Tambahan
                        </div>
                        <div class="review-services" id="rev_layanan"></div>
                    </div>

                    {{-- Final Price --}}
                    <div class="price-panel" id="pricePanelFinal">
                        <div class="price-panel-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            Ringkasan Biaya
                        </div>
                        <div id="priceLinesFinal"></div>
                        <div class="price-total">
                            <span class="price-total-label">Total Pembayaran</span>
                            <div>
                                <span class="price-total-value" id="priceTotalFinal">Rp 0</span>
                                <div class="price-nights" id="priceNightsFinal"></div>
                            </div>
                        </div>
                    </div>

                    <div class="step-nav">
                        <button type="button" class="btn-step btn-step-prev" onclick="goToStep(3)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg> Kembali
                        </button>
                        <button class="btn-reserve" type="submit" id="submitBtn" style="text-align:center">
                            <x-lucide-send class="lucide-icon-inline" /> Kirim Reservasi
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Availability Calendar Grid -->
        <div class="info-card" style="height:auto; margin-bottom:30px; padding:0; overflow:hidden">
            <div style="padding:20px 24px 12px; border-bottom:1px solid #eee; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px">
                <h4 style="margin:0"><x-lucide-calendar-range class="lucide-icon-inline" /> Ketersediaan Kamar — 7 Hari Kedepan</h4>
                <div style="display:flex; align-items:center; gap:16px; font-size:12px; color:#666">
                    <span><span class="cal-dot cal-dot-available"></span> Tersedia</span>
                    <span><span class="cal-dot cal-dot-booked"></span> Dipesan</span>
                    <span><span class="cal-dot cal-dot-confirmed"></span> Dikonfirmasi</span>
                </div>
            </div>
            <div class="avail-calendar-wrapper">
                <table class="avail-calendar">
                    <thead>
                        <tr>
                            <th class="avail-room-header">Kamar</th>
                            @foreach($calendarDates as $date)
                            <th class="avail-date-header {{ $date->isToday() ? 'avail-today' : '' }}">
                                <span class="avail-day-name">{{ $date->locale('id')->isoFormat('ddd') }}</span>
                                <span class="avail-day-num">{{ $date->format('d') }}</span>
                                <span class="avail-month">{{ $date->locale('id')->isoFormat('MMM') }}</span>
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kamars as $kamar)
                        <tr>
                            <td class="avail-room-cell">
                                <strong>{{ $kamar->no_kamar }}</strong>
                                <small>{{ $kamar->jenis_kamar }}</small>
                            </td>
                            @foreach($calendarDates as $date)
                            @php
                                $dateStr = $date->format('Y-m-d');
                                $status = $bookedMap[$kamar->no_kamar][$dateStr] ?? null;
                                $isBooked = !is_null($status);
                                $cellClass = 'avail-cell';
                                if ($date->isToday()) $cellClass .= ' avail-today';
                                if ($isBooked) {
                                    $cellClass .= in_array($status, ['dibayar', 'selesai']) ? ' avail-confirmed' : ' avail-booked';
                                } else {
                                    $cellClass .= ' avail-available';
                                }
                            @endphp
                            <td class="{{ $cellClass }}" title="Kamar {{ $kamar->no_kamar }} — {{ $date->locale('id')->isoFormat('D MMM YYYY') }}: {{ $isBooked ? 'Dipesan' : 'Tersedia' }}">
                                @if($isBooked)
                                    <span class="avail-icon-booked">✕</span>
                                @else
                                    <span class="avail-icon-ok">✓</span>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Bottom Cards - 3 columns -->
        <div class="reservasi-info-grid">
            <div class="info-card" style="height:auto">
                <h4><x-lucide-search class="lucide-icon-inline" /> Cek Status Pesanan</h4>
                <p style="color:#666; font-size:14px; margin-bottom:16px">Sudah reservasi? Masukkan No. KTP untuk melihat status.</p>
                <form action="{{ route('reservasi.cek') }}" method="GET">
                    <div class="form-group" style="margin-bottom:12px">
                        <input type="text" name="no_ktp" class="form-control" placeholder="Masukkan No. KTP" required>
                    </div>
                    <button class="btn-reserve" type="submit" style="width:100%; text-align:center">Cek Status</button>
                </form>
            </div>

            <div class="info-card" style="height:auto">
                <h4><x-lucide-info class="lucide-icon-inline" /> Informasi Penting</h4>
                <ul style="margin:12px 0 0; padding-left:18px; color:#666; line-height:1.9; font-size:14px">
                    <li>Check-in: <strong>14:00 WIB</strong></li>
                    <li>Check-out: <strong>12:00 WIB</strong></li>
                    <li>Pembayaran setelah dikonfirmasi</li>
                    <li>Upload bukti bayar untuk konfirmasi</li>
                    <li>Pembatalan gratis H-1 check-in</li>
                </ul>
            </div>

            <div class="info-card" style="height:auto; background:var(--dark); color:var(--white)">
                <h4 style="color:var(--white)"><x-lucide-headphones class="lucide-icon-inline" /> Butuh Bantuan?</h4>
                <p style="color:rgba(255,255,255,0.7); font-size:14px; margin:12px 0 16px">Tim reservasi kami siap membantu:</p>
                <p style="margin:0 0 8px; font-size:16px"><strong><x-lucide-phone class="lucide-icon-inline" style="color:#fff" /> +62 21 1234 5678</strong></p>
                <p style="margin:0; font-size:14px; color:rgba(255,255,255,0.7)"><x-lucide-mail class="lucide-icon-inline" /> reservasi@hotelx.com</p>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ===== MULTI-STEP LOGIC =====
    let currentStep = 1;

    window.goToStep = function(step) {
        if (step > currentStep && !validateStep(currentStep)) return;

        currentStep = step;

        // Update panels
        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`.step-panel[data-step="${step}"]`).classList.add('active');

        // Update progress indicators
        document.querySelectorAll('.step-item').forEach(item => {
            const s = parseInt(item.dataset.step);
            item.classList.remove('active', 'completed');
            if (s === step) item.classList.add('active');
            else if (s < step) item.classList.add('completed');
        });

        document.querySelectorAll('.step-connector').forEach((conn, i) => {
            conn.classList.toggle('completed', i < step - 1);
        });

        if (step === 4) populateReview();
        if (step === 3) updatePrice();

        document.querySelector('.step-progress').scrollIntoView({ behavior: 'smooth', block: 'start' });

        if (step > 1 && step < 4) {
            const labels = ['', 'Data Diri', 'Pilih Kamar', 'Layanan Tambahan', 'Konfirmasi'];
            Toast.info('Langkah ' + step, labels[step]);
        }
    };

    function validateStep(step) {
        if (step === 1) {
            const fields = ['inp_nama', 'inp_ktp', 'inp_tlp', 'inp_alamat'];
            const empty = fields.some(id => !document.getElementById(id).value.trim());
            const jk = document.getElementById('inp_jk').value;
            if (empty || !jk) {
                Toast.warning('Data tidak lengkap', 'Silakan isi semua data diri yang diperlukan.');
                return false;
            }
            return true;
        }
        if (step === 2) {
            const ci = document.getElementById('tgl_masuk').value;
            const co = document.getElementById('tgl_keluar').value;
            const sel = document.querySelectorAll('.kamar-item input[type="checkbox"]:checked');

            if (!ci || !co) {
                Toast.warning('Tanggal belum dipilih', 'Silakan pilih tanggal check-in dan check-out.');
                return false;
            }
            if (new Date(co) <= new Date(ci)) {
                Toast.error('Tanggal tidak valid', 'Tanggal check-out harus setelah check-in.');
                return false;
            }
            if (sel.length === 0) {
                Toast.warning('Kamar belum dipilih', 'Silakan pilih minimal satu kamar.');
                return false;
            }
            return true;
        }
        return true;
    }

    // ===== PRICE CALCULATION =====
    function formatRupiah(num) {
        return 'Rp ' + num.toLocaleString('id-ID');
    }

    function getNights() {
        const ci = document.getElementById('tgl_masuk').value;
        const co = document.getElementById('tgl_keluar').value;
        if (!ci || !co) return 0;
        const diff = (new Date(co) - new Date(ci)) / (1000 * 60 * 60 * 24);
        return diff > 0 ? diff : 0;
    }

    function getSelectedRooms() {
        const rooms = [];
        document.querySelectorAll('.kamar-item input[type="checkbox"]:checked').forEach(cb => {
            const item = cb.closest('.kamar-item');
            rooms.push({
                no: item.dataset.noKamar,
                harga: parseInt(item.dataset.harga),
                jenis: item.dataset.jenis
            });
        });
        return rooms;
    }

    function getSelectedServices() {
        const services = [];
        document.querySelectorAll('.layanan-item').forEach(item => {
            const qty = parseInt(item.querySelector('.qty-input').value) || 0;
            if (qty > 0) {
                services.push({
                    nama: item.dataset.nama,
                    harga: parseInt(item.dataset.harga),
                    qty: qty
                });
            }
        });
        return services;
    }

    function updatePrice() {
        const nights = getNights();
        const rooms = getSelectedRooms();
        const services = getSelectedServices();
        let roomTotal = 0, serviceTotal = 0, html = '';

        if (rooms.length > 0 && nights > 0) {
            rooms.forEach(r => {
                const sub = r.harga * nights;
                roomTotal += sub;
                html += `<div class="price-line"><span>Kamar ${r.no} (${r.jenis}) × ${nights} mlm</span><span class="price-val">${formatRupiah(sub)}</span></div>`;
            });
        } else {
            html += '<div class="price-line empty">Pilih kamar dan tanggal untuk melihat estimasi</div>';
        }

        services.forEach(s => {
            const sub = s.harga * s.qty;
            serviceTotal += sub;
            html += `<div class="price-line"><span>${s.nama} × ${s.qty}</span><span class="price-val">${formatRupiah(sub)}</span></div>`;
        });

        const total = roomTotal + serviceTotal;
        const nightsText = nights > 0 ? `${nights} malam · ${rooms.length} kamar` : '';

        ['priceLines', 'priceLinesStep3', 'priceLinesFinal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.innerHTML = html;
        });
        ['priceTotalValue', 'priceTotalStep3', 'priceTotalFinal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = formatRupiah(total);
        });
        ['priceNights', 'priceNightsStep3', 'priceNightsFinal'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = nightsText;
        });
    }

    // Bind price updates
    document.querySelectorAll('.kamar-item input[type="checkbox"]').forEach(cb => cb.addEventListener('change', updatePrice));
    document.querySelectorAll('.qty-input').forEach(inp => inp.addEventListener('change', updatePrice));
    document.getElementById('tgl_masuk').addEventListener('change', updatePrice);
    document.getElementById('tgl_keluar').addEventListener('change', updatePrice);

    // ===== REVIEW POPULATION =====
    function populateReview() {
        document.getElementById('rev_nama').textContent = document.getElementById('inp_nama').value;
        document.getElementById('rev_ktp').textContent = document.getElementById('inp_ktp').value;
        document.getElementById('rev_tlp').textContent = document.getElementById('inp_tlp').value;
        const jkSel = document.getElementById('inp_jk');
        document.getElementById('rev_jk').textContent = jkSel.options[jkSel.selectedIndex]?.text || '-';
        document.getElementById('rev_alamat').textContent = document.getElementById('inp_alamat').value;

        const ci = document.getElementById('tgl_masuk').value;
        const co = document.getElementById('tgl_keluar').value;
        const nights = getNights();
        const fmtDate = (d) => {
            if (!d) return '-';
            return new Date(d).toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        };
        document.getElementById('rev_checkin').textContent = fmtDate(ci);
        document.getElementById('rev_checkout').textContent = fmtDate(co);
        document.getElementById('rev_durasi').textContent = nights > 0 ? nights + ' malam' : '-';

        // Rooms
        const rooms = getSelectedRooms();
        const roomsEl = document.getElementById('rev_kamar');
        roomsEl.innerHTML = rooms.length > 0
            ? rooms.map(r => `<div class="review-room-tag"><strong>No. ${r.no} · ${r.jenis}</strong><span>${formatRupiah(r.harga)}/malam</span></div>`).join('')
            : '<span style="color:#999">Belum ada kamar dipilih</span>';

        // Services
        const services = getSelectedServices();
        const secEl = document.getElementById('rev_layanan_section');
        const svcEl = document.getElementById('rev_layanan');
        if (services.length > 0) {
            secEl.style.display = 'block';
            svcEl.innerHTML = services.map(s => `<div class="review-service-tag"><strong>${s.nama} × ${s.qty}</strong><span>${formatRupiah(s.harga * s.qty)}</span></div>`).join('');
        } else {
            secEl.style.display = 'none';
        }

        updatePrice();
    }

    // ===== AVAILABILITY CHECK =====
    const tglMasuk = document.getElementById('tgl_masuk');
    const tglKeluar = document.getElementById('tgl_keluar');
    const loadingIndicator = document.getElementById('kamar-loading');
    const availMsg = document.getElementById('availability-message');
    let checkTimeout;

    function checkAvailability() {
        const ci = tglMasuk.value, co = tglKeluar.value;
        if (!ci || !co) return;

        if (new Date(co) <= new Date(ci)) {
            availMsg.style.display = 'block';
            availMsg.style.background = '#f8d7da';
            availMsg.style.color = '#721c24';
            availMsg.innerHTML = '⚠️ Tanggal check-out harus setelah tanggal check-in';
            return;
        }

        loadingIndicator.style.display = 'inline';

        fetch('/api/availability/check', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ check_in: ci, check_out: co })
        })
        .then(r => r.json())
        .then(data => {
            loadingIndicator.style.display = 'none';
            const unavail = data.unavailable_rooms.map(r => String(r.no_kamar));

            document.querySelectorAll('.kamar-item').forEach(item => {
                const no = item.dataset.noKamar;
                const cb = item.querySelector('input[type="checkbox"]');
                const strong = item.querySelector('strong');
                const small = item.querySelector('small');
                const badge = item.querySelector('.badge-dipesan');
                if (badge) badge.remove();

                if (unavail.includes(no)) {
                    item.classList.add('kamar-dipesan');
                    cb.disabled = true;
                    cb.checked = false;
                    strong.classList.add('text-strikethrough');
                    small.classList.add('text-strikethrough');
                    const b = document.createElement('span');
                    b.className = 'badge-dipesan';
                    b.textContent = 'Tidak Tersedia';
                    item.querySelector('.kamar-info').appendChild(b);
                } else {
                    item.classList.remove('kamar-dipesan');
                    cb.disabled = false;
                    strong.classList.remove('text-strikethrough');
                    small.classList.remove('text-strikethrough');
                }
            });

            availMsg.style.display = 'block';
            if (data.available_count > 0) {
                availMsg.style.background = '#d4edda';
                availMsg.style.color = '#155724';
                availMsg.innerHTML = `✓ ${data.available_count} kamar tersedia untuk tanggal yang dipilih`;
            } else {
                availMsg.style.background = '#f8d7da';
                availMsg.style.color = '#721c24';
                availMsg.innerHTML = '⚠ Tidak ada kamar tersedia untuk tanggal yang dipilih';
            }
            updatePrice();
        })
        .catch(() => { loadingIndicator.style.display = 'none'; });
    }

    tglMasuk.addEventListener('change', () => { clearTimeout(checkTimeout); checkTimeout = setTimeout(checkAvailability, 300); });
    tglKeluar.addEventListener('change', () => { clearTimeout(checkTimeout); checkTimeout = setTimeout(checkAvailability, 300); });
    if (tglMasuk.value && tglKeluar.value) checkAvailability();

    // ===== AUTO-SELECT ROOM FROM URL =====
    const preselectedKamar = new URLSearchParams(window.location.search).get('kamar');
    if (preselectedKamar) {
        goToStep(2);
        const target = document.querySelector(`.kamar-item[data-no-kamar="${preselectedKamar}"]`);
        if (target) {
            const cb = target.querySelector('input[type="checkbox"]');
            if (cb && !cb.disabled) {
                cb.checked = true;
                target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                target.style.transition = 'box-shadow 0.3s, transform 0.3s';
                target.style.boxShadow = '0 0 0 3px var(--accent), 0 4px 16px rgba(183,143,90,0.3)';
                target.style.transform = 'scale(1.02)';
                setTimeout(() => { target.style.boxShadow = ''; target.style.transform = ''; }, 2000);
                updatePrice();
            }
        }
    }

    // ===== LAYANAN QTY CONTROLS =====
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.layanan-item');
            const input = container.querySelector('.qty-input');
            let val = parseInt(input.value) || 0;
            if (this.classList.contains('plus') && val < 10) val++;
            if (this.classList.contains('minus') && val > 0) val--;
            input.value = val;
            container.classList.toggle('layanan-active', val > 0);
            updatePrice();
        });
    });

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            const container = this.closest('.layanan-item');
            container.classList.toggle('layanan-active', (parseInt(this.value) || 0) > 0);
            updatePrice();
        });
    });

    // ===== FORM SUBMIT =====
    document.getElementById('reservasiForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;vertical-align:middle;margin-right:6px"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Mengirim...';
        Toast.info('Mengirim Reservasi', 'Mohon tunggu sebentar...');
    });

    updatePrice();
});
</script>
<style>@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }</style>
@endsection
