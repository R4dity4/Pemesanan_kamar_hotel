@extends('layouts.app')

@section('title','Kontak Kami')

@section('content')
<div class="section-sep">
    <h2>KONTAK KAMI</h2>
    <div class="accent"></div>
    <p>Hubungi tim reservasi atau layanan tamu kami untuk bantuan apapun selama 24 jam.</p>
</div>

<section class="container" style="padding:40px 0 80px">
    <div class="kontak-grid">
        <!-- Kiri: Peta -->
        <div>
            <div class="info-card" style="height:auto; padding:0; overflow:hidden">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d247.50494885754588!2d110.33241430080955!3d-6.99995732984799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708a9ff4d0bc05%3A0x1321bfcfee0330e9!2sJl.%20Bukit%20Watuwila%20VI%20No.26%2C%20Bringin%2C%20Kec.%20Ngaliyan%2C%20Kota%20Semarang%2C%20Jawa%20Tengah%2050189!5e0!3m2!1sid!2sid!4v1768148107711!5m2!1sid!2sid"
                    width="100%"
                    height="350"
                    style="border:0; display:block"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <div class="info-card" style="height:auto; margin-top:24px">
                <h4><x-lucide-map-pin class="lucide-icon-inline" /> Alamat</h4>
                <p style="line-height:1.8; color:#666">
                    Jl. Bukit Watuwila VI No.26<br>
                    Bringin, Kec. Ngaliyan<br>
                    Kota Semarang, Jawa Tengah 50189
                </p>
            </div>
        </div>

        <!-- Kanan: Kontak & Form -->
        <div>
            <div class="info-card" style="height:auto; margin-bottom:24px">
                <h4><x-lucide-phone class="lucide-icon-inline" /> Kontak Reservasi</h4>
                <div style="margin-top:16px">
                    <p style="margin:0 0 12px; display:flex; align-items:center; gap:10px; flex-wrap:wrap">
                        <span style="min-width:80px; color:#666">Telepon</span>
                        <strong>+62 00 2606 2007</strong>
                    </p>
                    <p style="margin:0 0 12px; display:flex; align-items:center; gap:10px">
                        <span style="width:80px; color:#666">WhatsApp</span>
                        <strong>+62 00 2606 2007</strong>
                    </p>
                    <p style="margin:0 0 12px; display:flex; align-items:center; gap:10px">
                        <span style="width:80px; color:#666">Email</span>
                        <strong>info@hotelx.id</strong>
                    </p>
                    <p style="margin:16px 0 0; padding-top:16px; border-top:1px solid #eee; color:#666; font-size:14px">
                        <x-lucide-clock class="lucide-icon-inline" /> Layanan 24/7 — Kami siap membantu Anda kapan saja
                    </p>
                </div>
            </div>

            <div class="info-card" style="height:auto">
                <h4><x-lucide-mail class="lucide-icon-inline" /> Kirim Pesan</h4>

                @if(session('success'))
                <div class="alert alert-success" style="background:#d4edda; color:#155724; padding:12px 16px; border-radius:8px; margin-bottom:16px">
                    <x-lucide-check class="lucide-icon-check" /> {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('kontak.send') }}" method="POST" style="margin-top:16px">
                    @csrf
                    <div class="form-group">
                        <label>Nama Lengkap *</label>
                        <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" value="{{ old('name') }}" required>
                        @error('name')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" placeholder="email@contoh.com" value="{{ old('email') }}" required>
                            @error('email')<span class="error-text">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label>No. Telepon</label>
                            <input type="text" name="phone" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Topik *</label>
                        <select name="topic" class="form-control">
                            <option value="umum" {{ old('topic') == 'umum' ? 'selected' : '' }}>Pertanyaan Umum</option>
                            <option value="reservasi" {{ old('topic') == 'reservasi' ? 'selected' : '' }}>Reservasi</option>
                            <option value="acara" {{ old('topic') == 'acara' ? 'selected' : '' }}>Acara & Meeting</option>
                            <option value="kerjasama" {{ old('topic') == 'kerjasama' ? 'selected' : '' }}>Kerjasama</option>
                            <option value="lainnya" {{ old('topic') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pesan *</label>
                        <textarea name="message" class="form-control" placeholder="Tulis pesan Anda..." rows="4" required>{{ old('message') }}</textarea>
                        @error('message')<span class="error-text">{{ $message }}</span>@enderror
                    </div>
                    <button class="btn-reserve" type="submit" style="width:100%; text-align:center">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
