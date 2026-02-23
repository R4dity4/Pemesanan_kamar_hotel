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
                <div class="topbar-actions">
                    <button class="notif-bell" id="notifBell" title="Notifikasi">
                        <x-lucide-bell />
                        <span class="notif-badge" id="notifBadge" style="display:none">0</span>
                    </button>
                </div>
            </header>

            <!-- Notification dropdown -->
            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <strong>Notifikasi</strong>
                    <button class="notif-clear-btn" id="notifClearBtn">Tandai dibaca</button>
                </div>
                <div class="notif-dropdown-body" id="notifList">
                    <div class="notif-empty">Tidak ada notifikasi baru</div>
                </div>
            </div>
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

        // ==========================================
        // REAL-TIME NOTIFICATION SYSTEM (Polling)
        // ==========================================
        (function() {
            const POLL_INTERVAL = 15000; // 15 detik
            let lastCheck = null; // null = first check
            let isFirstCheck = true;
            let notifications = [];
            let lastTransaksiHash = null;
            let lastPesanHash = null;

            // Auto-refresh halaman jika data berubah
            function autoRefreshContent() {
                // Hanya auto-refresh jika dropdown notif tidak sedang terbuka
                if (dropdown?.classList.contains('show')) return;
                window.location.reload();
            }

            // Create notification sound (Web Audio API - no file needed)
            function playNotifSound() {
                try {
                    const ctx = new (window.AudioContext || window.webkitAudioContext)();
                    // Ding 1
                    const osc1 = ctx.createOscillator();
                    const gain1 = ctx.createGain();
                    osc1.connect(gain1);
                    gain1.connect(ctx.destination);
                    osc1.frequency.value = 830;
                    osc1.type = 'sine';
                    gain1.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain1.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.5);
                    osc1.start(ctx.currentTime);
                    osc1.stop(ctx.currentTime + 0.5);
                    // Ding 2 (higher)
                    const osc2 = ctx.createOscillator();
                    const gain2 = ctx.createGain();
                    osc2.connect(gain2);
                    gain2.connect(ctx.destination);
                    osc2.frequency.value = 1050;
                    osc2.type = 'sine';
                    gain2.gain.setValueAtTime(0, ctx.currentTime + 0.15);
                    gain2.gain.linearRampToValueAtTime(0.3, ctx.currentTime + 0.2);
                    gain2.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.7);
                    osc2.start(ctx.currentTime + 0.15);
                    osc2.stop(ctx.currentTime + 0.7);
                } catch (e) {}
            }

            // DOM refs
            const bell = document.getElementById('notifBell');
            const badge = document.getElementById('notifBadge');
            const dropdown = document.getElementById('notifDropdown');
            const notifList = document.getElementById('notifList');
            const clearBtn = document.getElementById('notifClearBtn');

            // Update badge count
            function updateBadge(count) {
                if (count > 0) {
                    badge.textContent = count > 99 ? '99+' : count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }

            // Toggle dropdown
            bell?.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdown.classList.toggle('show');
            });

            document.addEventListener('click', function(e) {
                if (!dropdown?.contains(e.target) && !bell?.contains(e.target)) {
                    dropdown?.classList.remove('show');
                }
            });

            // Clear notifications
            clearBtn?.addEventListener('click', function() {
                notifications = [];
                renderNotifications();
                updateBadge(0);
            });

            function renderNotifications() {
                if (notifications.length === 0) {
                    notifList.innerHTML = '<div class="notif-empty">Tidak ada notifikasi baru</div>';
                    return;
                }
                notifList.innerHTML = notifications.map(n => `
                    <a href="${n.url}" class="notif-item ${n.type}">
                        <div class="notif-item-icon">${n.icon}</div>
                        <div class="notif-item-content">
                            <div class="notif-item-title">${n.title}</div>
                            <div class="notif-item-desc">${n.desc}</div>
                            <div class="notif-item-time">${n.time}</div>
                        </div>
                    </a>
                `).join('');
            }

            // Show browser notification (if permitted)
            function showBrowserNotif(title, body) {
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification(title, {
                        body: body,
                        icon: '/favicon.ico',
                        tag: 'hotelx-admin'
                    });
                }
            }

            // Request browser notification permission
            if ('Notification' in window && Notification.permission === 'default') {
                Notification.requestPermission();
            }

            // Show floating toast in admin
            function showAdminToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = 'admin-toast ' + type;
                toast.innerHTML = `
                    <div class="admin-toast-content">
                        <span class="admin-toast-icon">${type === 'order' ? '🛎️' : type === 'payment' ? '💰' : type === 'pesan' ? '✉️' : 'ℹ️'}</span>
                        <span class="admin-toast-text">${message}</span>
                    </div>
                    <button class="admin-toast-close" onclick="this.parentElement.remove()">×</button>
                `;
                document.body.appendChild(toast);
                setTimeout(() => toast.classList.add('show'), 10);
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => toast.remove(), 300);
                }, 8000);
            }

            // Polling
            async function checkNotifications() {
                try {
                    let url = '/admin/notifications/check';
                    const params = new URLSearchParams();
                    if (lastCheck) params.set('last_check', lastCheck);
                    if (isFirstCheck) params.set('first', '1');
                    url += '?' + params.toString();

                    const res = await fetch(url, { credentials: 'same-origin' });
                    if (!res.ok) {
                        console.warn('Notification check HTTP error:', res.status);
                        return;
                    }

                    const contentType = res.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.warn('Notification check: non-JSON response (possibly redirected to login)');
                        return;
                    }

                    const data = await res.json();
                    console.log('Notif check:', data);

                    let hasNew = false;

                    // Always update badge with total pending + pending bayar + unread pesan
                    const totalBadge = data.total_pending + data.pending_bayar + data.unread_pesan;
                    updateBadge(totalBadge);

                    // Detect data changes for current page auto-reload
                    const currentPath = window.location.pathname;

                    if (data.transaksi_hash && lastTransaksiHash && data.transaksi_hash !== lastTransaksiHash) {
                        const isTransaksiPage = currentPath.includes('/admin/transaksi');
                        const isDashboardPage = currentPath === '/admin' || currentPath === '/admin/' || currentPath.includes('/admin/dashboard');
                        const isLaporanPage = currentPath.includes('/admin/laporan');

                        if (isTransaksiPage || isDashboardPage || isLaporanPage) {
                            setTimeout(() => autoRefreshContent(), 2000);
                        }
                    }
                    lastTransaksiHash = data.transaksi_hash;

                    // Detect pesan changes for pesan page auto-reload
                    if (data.pesan_hash && lastPesanHash && data.pesan_hash !== lastPesanHash) {
                        const isPesanPage = currentPath.includes('/admin/pesan');
                        if (isPesanPage) {
                            setTimeout(() => autoRefreshContent(), 2000);
                        }
                    }
                    lastPesanHash = data.pesan_hash;

                    // On first check, show existing items in dropdown without toast/sound
                    if (isFirstCheck) {
                        if (data.total_pending > 0) {
                            notifications.push({
                                type: 'order',
                                icon: '🛎️',
                                title: `${data.total_pending} Pesanan Menunggu`,
                                desc: `Ada ${data.total_pending} pesanan pending yang perlu dikonfirmasi`,
                                time: 'Saat ini',
                                url: '/admin/transaksi?status=pending'
                            });
                        }
                        if (data.pending_bayar > 0) {
                            notifications.push({
                                type: 'payment',
                                icon: '💰',
                                title: `${data.pending_bayar} Bukti Bayar`,
                                desc: `Ada ${data.pending_bayar} pembayaran yang perlu diverifikasi`,
                                time: 'Saat ini',
                                url: '/admin/transaksi?status=dibayar'
                            });
                        }
                        if (data.unread_pesan > 0) {
                            notifications.push({
                                type: 'pesan',
                                icon: '✉️',
                                title: `${data.unread_pesan} Pesan Belum Dibaca`,
                                desc: `Ada ${data.unread_pesan} pesan masuk yang belum dibaca`,
                                time: 'Saat ini',
                                url: '/admin/pesan'
                            });
                        }
                        renderNotifications();
                        isFirstCheck = false;
                        lastCheck = data.server_time;
                        return;
                    }

                    // New pending order (after first check)
                    if (data.new_pending > 0 && data.latest) {
                        hasNew = true;
                        const msg = `Pesanan baru dari ${data.latest.nama} - Rp ${data.latest.total}`;

                        notifications.unshift({
                            type: 'order',
                            icon: '🛎️',
                            title: 'Pesanan Baru!',
                            desc: msg,
                            time: data.latest.waktu,
                            url: `/admin/transaksi/${data.latest.no_transaksi}`
                        });

                        showAdminToast(msg, 'order');
                        showBrowserNotif('Pesanan Baru - HOTELX', msg);
                    }

                    // New bukti bayar uploaded
                    if (data.new_bukti > 0) {
                        hasNew = true;
                        const msg = `${data.new_bukti} bukti pembayaran baru perlu diverifikasi`;

                        notifications.unshift({
                            type: 'payment',
                            icon: '💰',
                            title: 'Bukti Bayar Baru',
                            desc: msg,
                            time: 'Baru saja',
                            url: '/admin/transaksi?status=dibayar'
                        });

                        showAdminToast(msg, 'payment');
                    }

                    // New pesan notification
                    if (data.new_pesan > 0 && data.latest_pesan) {
                        hasNew = true;
                        const msg = `Pesan baru dari ${data.latest_pesan.nama} - ${data.latest_pesan.topik}`;

                        notifications.unshift({
                            type: 'pesan',
                            icon: '✉️',
                            title: 'Pesan Masuk Baru!',
                            desc: `${data.latest_pesan.nama}: ${data.latest_pesan.preview}`,
                            time: data.latest_pesan.waktu,
                            url: `/admin/pesan/${data.latest_pesan.id}`
                        });

                        showAdminToast(msg, 'pesan');
                        showBrowserNotif('Pesan Masuk - HOTELX', msg);
                    }

                    // Update pesan badge in sidebar
                    const pesanLink = document.querySelector('.sidebar-nav a[href="/admin/pesan"]');
                    if (pesanLink) {
                        let pesanBadge = pesanLink.querySelector('.badge-notif');
                        if (data.unread_pesan > 0) {
                            if (!pesanBadge) {
                                pesanBadge = document.createElement('span');
                                pesanBadge.className = 'badge-notif';
                                pesanLink.appendChild(pesanBadge);
                            }
                            pesanBadge.textContent = data.unread_pesan;
                        } else if (pesanBadge) {
                            pesanBadge.remove();
                        }
                    }

                    // Keep max 20 notifications
                    if (notifications.length > 20) {
                        notifications = notifications.slice(0, 20);
                    }

                    if (hasNew) {
                        playNotifSound();
                        renderNotifications();
                    }

                    // Update last check time
                    lastCheck = data.server_time;

                } catch (e) {
                    console.warn('Notification check failed:', e);
                }
            }

            // Start polling
            setInterval(checkNotifications, POLL_INTERVAL);
            // First check after 3s (give page time to load)
            setTimeout(checkNotifications, 3000);
        })();
    </script>
</body>
</html>
