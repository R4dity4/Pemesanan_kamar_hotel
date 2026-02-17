@extends('layouts.admin')

@section('title', 'Tambah Kamar')

@section('content')
<div class="card" style="max-width:600px">
    <h3 class="card-title" style="margin-bottom:24px">Form Tambah Kamar</h3>

    <form action="/admin/kamar" method="POST">
        @csrf
        <div class="form-group">
            <label>No. Kamar</label>
            <input type="number" name="no_kamar" class="form-control" value="{{ old('no_kamar') }}" required>
        </div>
        <div class="form-group">
            <label>Jenis Kamar</label>
            <select name="jenis_kamar" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="Standard" {{ old('jenis_kamar') == 'Standard' ? 'selected' : '' }}>Standard</option>
                <option value="Deluxe" {{ old('jenis_kamar') == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                <option value="Suite" {{ old('jenis_kamar') == 'Suite' ? 'selected' : '' }}>Suite</option>
                <option value="Presidential Suite" {{ old('jenis_kamar') == 'Presidential Suite' ? 'selected' : '' }}>Presidential Suite</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga per Malam (Rp)</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="tersedia">Tersedia</option>
                <option value="terisi">Terisi</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="/admin/kamar" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
