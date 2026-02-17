<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - HOTELX</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Mobile Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="/admin" class="logo">HOTEL<span>X</span></a>
                <small>Admin Panel</small>
            </div>
            <nav class="sidebar-nav">
                <a href="/admin" class="{{ request()->is('admin') ? 'active' : '' }}">
                    <x-lucide-layout-dashboard class="icon" /> Dashboard
                </a>
                <a href="/admin/kamar" class="{{ request()->is('admin/kamar*') ? 'active' : '' }}">
                    <x-lucide-bed-double class="icon" /> Data Kamar
                </a>
                <a href="/admin/transaksi" class="{{ request()->is('admin/transaksi*') ? 'active' : '' }}">
                    <x-lucide-clipboard-list class="icon" /> Transaksi
                </a>
                <a href="/admin/pesan" class="{{ request()->is('admin/pesan*') ? 'active' : '' }}">
                    <x-lucide-mail class="icon" /> Pesan Masuk
                    @php $unreadCount = \App\Models\Pesan::where('dibaca', false)->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="badge-notif">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="/admin/laporan" class="{{ request()->is('admin/laporan*') ? 'active' : '' }}">
                    <x-lucide-file-text class="icon" /> Laporan
                </a>
            </nav>
            <div class="sidebar-footer">
                <div class="user-info">
                    <span>{{ session('karyawan')->nm_karyawan ?? 'Admin' }}</span>
                    <small>{{ session('karyawan')->id_karyawan ?? '' }}</small>
                </div>
                <form action="/admin/logout" method="POST" style="margin:0">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            </div>
        </aside>
        <main class="main-content">
            <header class="topbar">
                <button class="mobile-sidebar-toggle" id="mobileSidebarToggle" aria-label="Toggle sidebar">
                    <x-lucide-menu />
                </button>
                <h1>@yield('title', 'Dashboard')</h1>
            </header>
            <div class="content">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile Sidebar Toggle
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (mobileSidebarToggle && sidebar) {
            mobileSidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                sidebarOverlay?.classList.toggle('active');
            });

            sidebarOverlay?.addEventListener('click', function() {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
            });
        }
    </script>
</body>
</html>
