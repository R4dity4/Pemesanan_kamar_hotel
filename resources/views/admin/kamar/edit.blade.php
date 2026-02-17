@extends('layouts.admin')

@section('title', 'Edit Kamar')

@section('content')
<div class="card" style="max-width:600px">
    <h3 class="card-title" style="margin-bottom:24px">Edit Kamar #{{ $kamar->no_kamar }}</h3>

    <form action="/admin/kamar/{{ $kamar->no_kamar }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>No. Kamar</label>
            <input type="number" class="form-control" value="{{ $kamar->no_kamar }}" disabled>
        </div>
        <div class="form-group">
            <label>Jenis Kamar</label>
            <select name="jenis_kamar" class="form-control" required>
                <option value="Standard" {{ old('jenis_kamar', $kamar->jenis_kamar) == 'Standard' ? 'selected' : '' }}>Standard</option>
                <option value="Deluxe" {{ old('jenis_kamar', $kamar->jenis_kamar) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                <option value="Suite" {{ old('jenis_kamar', $kamar->jenis_kamar) == 'Suite' ? 'selected' : '' }}>Suite</option>
                <option value="Presidential Suite" {{ old('jenis_kamar', $kamar->jenis_kamar) == 'Presidential Suite' ? 'selected' : '' }}>Presidential Suite</option>
            </select>
        </div>
        <div class="form-group">
            <label>Harga per Malam (Rp)</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga', $kamar->harga) }}" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="terisi" {{ $kamar->status == 'terisi' ? 'selected' : '' }}>Terisi</option>
                <option value="maintenance" {{ $kamar->status == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
            </select>
        </div>
        <div class="btn-group">
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="/admin/kamar" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
