<footer class="site-footer">
    <div class="container footer-inner">
        <div class="footer-col">
            <h4>HOTEL<span style="color:var(--accent)">X</span></h4>
            <p>Jl. Bukit Watuwila VI No.26<br> Bringin, Kec. Ngaliyan<br> Kota Semarang, Jawa Tengah 50189</p>
            <p style="margin-top:16px">
                <strong style="color:var(--accent)">Reservasi:</strong><br>
                +62 00 2606 2007
            </p>
        </div>
        <div class="footer-col">
            <h5>Navigasi</h5>
            <ul>
                <li><a href="#kamar">Kamar & Suite</a></li>
                <li><a href="#aktivitas">Aktivitas</a></li>
                <li><a href="#fasilitas">Fasilitas</a></li>
                <li><a href="#kontak">Kontak Kami</a></li>
                <li><a href="#reservasi">Reservasi</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Hubungi Kami</h5>
            <p>Email: info@hotelx.id</p>
            <p>Tel: +62 00 2606 2007</p>
            <p style="margin-top:16px">
                <a href="{{ url('pengunjung/medsos/instagaram') }}" style="color:rgba(255,255,255,0.7); margin-right:12px">Instagram</a>
                <a href="{{ url('pengunjung/medsos/fesnuk')}}" style="color:rgba(255,255,255,0.7); margin-right:12px">Facebook</a>
                <a href="{{ url('pengunjung/medsos/twister')}}" style="color:rgba(255,255,255,0.7)">Twitter</a>
            </p>
        </div>
    </div>
    <div class="footer-bottom container">© {{ date('Y') }} HOTELX. All rights reserved. | <a href="{{ url('pengunjung/medsos/privacypolicy') }}" style="color:rgba(255,255,255,0.5)">Privacy Policy</a></div>
</footer>
