<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('front/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('front/fonts/font-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/styles.css') }}">
    <link rel="shortcut icon" href="{{ asset('front/images/logo/168.png') }}" />
    <title>Syarat & Ketentuan Mitra JajanEmas</title>
</head>
<body>
    <div class="preload preload-container">
        <div class="logo-img">
            <img src="{{ asset('front/images/logo/logo-dark2.png') }}" alt="">
        </div>
        <div class="spinner-circle lg success">
            <span class="spinner-circle1 spinner-child"></span>
            <span class="spinner-circle2 spinner-child"></span>
            <span class="spinner-circle3 spinner-child"></span>
            <span class="spinner-circle4 spinner-child"></span>
            <span class="spinner-circle5 spinner-child"></span>
            <span class="spinner-circle6 spinner-child"></span>
            <span class="spinner-circle7 spinner-child"></span>
            <span class="spinner-circle8 spinner-child"></span>
            <span class="spinner-circle9 spinner-child"></span>
        </div>
    </div>

    <section class="tf-section">
        <div class="tf-container">
            <div class="mx-auto" style="max-width: 820px;">
                <div class="mb-4 text-center">
                    <img src="{{ asset('front/images/logo/168.png') }}" alt="JajanEmas" style="height:56px">
                </div>
                <h1 class="title mb-3 text-center">Syarat & Ketentuan Mitra JajanEmas</h1>
                <p class="body-3 text-dark-4 mb-4 text-center">Dokumen ini mengatur ketentuan kemitraan di platform JajanEmas. Dengan mendaftar sebagai Mitra, Anda menyatakan telah membaca, memahami, dan menyetujui poin-poin di bawah.</p>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">1. Kualifikasi Mitra</h5>
                    <ul class="mb-0">
                        <li>Memiliki identitas resmi dan kontak yang valid.</li>
                        <li>Memiliki kemampuan operasional dasar untuk layanan penjualan/pengiriman emas.</li>
                        <li>Bersedia mengikuti peraturan internal dan kebijakan yang berlaku.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">2. Pendaftaran & Aktivasi</h5>
                    <ul class="mb-0">
                        <li>Pendaftaran dilakukan melalui kanal resmi JajanEmas.</li>
                        <li>Aktivasi akun dilakukan setelah proses verifikasi oleh tim JajanEmas.</li>
                        <li>JajanEmas berhak menolak pendaftaran tanpa kewajiban memberikan alasan.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">3. Tanggung Jawab Mitra</h5>
                    <ul class="mb-0">
                        <li>Menjaga akurasi informasi dan kerahasiaan akun.</li>
                        <li>Mematuhi standar layanan, termasuk waktu proses dan kualitas pelayanan kepada pelanggan.</li>
                        <li>Tidak melakukan praktik yang melanggar hukum atau merugikan pelanggan.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">4. Komisi & Pembayaran</h5>
                    <ul class="mb-0">
                        <li>Besaran komisi mengikuti ketentuan pada sistem dan dapat berubah sewaktu-waktu.</li>
                        <li>Pembayaran komisi dilakukan sesuai siklus yang ditetapkan dan bukti transaksi yang sah.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">5. Kepatuhan & Audit</h5>
                    <ul class="mb-0">
                        <li>Mitra wajib patuh terhadap kebijakan AML dan KYC yang berlaku.</li>
                        <li>JajanEmas dapat melakukan audit terhadap aktivitas Mitra bila diperlukan.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">6. Pembatasan & Penonaktifan</h5>
                    <ul class="mb-0">
                        <li>JajanEmas berhak membatasi atau menonaktifkan akun Mitra yang melanggar ketentuan.</li>
                        <li>Penonaktifan dapat dilakukan sementara atau permanen sesuai tingkat pelanggaran.</li>
                    </ul>
                </div>

                <div class="card card-body mb-3">
                    <h5 class="mb-2">7. Perubahan Ketentuan</h5>
                    <ul class="mb-0">
                        <li>Ketentuan dapat diperbarui dari waktu ke waktu.</li>
                        <li>Perubahan akan diumumkan melalui platform resmi dan berlaku setelah dipublikasikan.</li>
                    </ul>
                </div>

                <div class="card card-body mb-4">
                    <h5 class="mb-2">8. Kontak & Dukungan</h5>
                    <p class="mb-0">Untuk pertanyaan atau dukungan, silakan menghubungi layanan resmi JajanEmas melalui kanal yang tersedia di situs.</p>
                </div>

                <div class="d-grid gap-2 mt-3" style="grid-template-columns: 1fr 1fr;">
                    <a href="{{ url('/') }}" class="tf-btn primary d-block w-100" style="padding: 8px 12px; font-size: 0.875rem;">Kembali ke Beranda</a>
                    <a href="{{ route('mitra.login') }}" class="tf-btn primary d-block w-100" style="padding: 8px 12px; font-size: 0.875rem;">Login Mitra</a>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('front/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/js/jquery.min.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
</body>
</html>