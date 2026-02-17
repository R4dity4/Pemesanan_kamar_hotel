@extends('layouts.admin')

@section('title', 'Data Kamar')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Kamar</h3>
        <a href="/admin/kamar/create" class="btn btn-primary">+ Tambah Kamar</a>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No. Kamar</th>
                    <th>Jenis</th>
                    <th>Harga/Malam</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kamars as $k)
                <tr>
                    <td>{{ $k->no_kamar }}</td>
                    <td>{{ $k->jenis_kamar }}</td>
                    <td>Rp {{ number_format($k->harga, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $k->status }}">{{ $k->status }}</span></td>
                    <td>
                        <div class="btn-group">
                            <a href="/admin/kamar/{{ $k->no_kamar }}/edit" class="btn btn-sm btn-secondary">Edit</a>
                            <form action="/admin/kamar/{{ $k->no_kamar }}" method="POST" onsubmit="return confirm('Hapus kamar ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center; color:#999">Belum ada data kamar</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
