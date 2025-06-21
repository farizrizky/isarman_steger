<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Aplikasi Penyewaan Isarman Steger Bengkulu</title>
  <style>
    body { font-family: Arial, sans-serif; line-height: 1.6; margin: 2em; }
    pre { background: #f4f4f4; padding: 1em; overflow-x: auto; }
    code { background: #f0f0f0; padding: 0.2em 0.4em; }
    h1, h2 { color: #2c3e50; }
  </style>
</head>
<body>

<h1>Aplikasi Penyewaan Isarman Steger Bengkulu</h1>

<p>Aplikasi ini merupakan sistem penyewaan barang yang dikembangkan untuk kebutuhan internal <strong>Isarman Steger Bengkulu</strong>.</p>

<h2>🚀 Langkah Instalasi</h2>
<ol>
  <li><strong>Salin dan sesuaikan file <code>.env</code></strong><br>
    Jika belum ada file <code>.env</code>, salin dari template:
    <pre><code>cp .env.example .env</code></pre>
    Atur konfigurasi database dan environment sesuai kebutuhan.
  </li>

  <li><strong>Install dependency menggunakan Composer</strong>
    <pre><code>composer install</code></pre>
  </li>

  <li><strong>Jalankan migrasi database</strong>
    <pre><code>php artisan migrate</code></pre>
  </li>

  <li><strong>Seed database dengan data awal</strong>
    <pre><code>php artisan db:seed --class=InitialSetupSeeder</code></pre>
  </li>

  <li><strong>(Opsional) Generate application key</strong>
    <pre><code>php artisan key:generate</code></pre>
  </li>
</ol>

<h2>✅ Catatan Tambahan</h2>
<ul>
  <li>Pastikan folder <code>storage/</code> dan <code>bootstrap/cache/</code> memiliki permission yang sesuai (biasanya 755 atau 775).</li>
  <li><strong>Membuat symbolic link untuk folder <code>storage</code></strong><br>
    Jika perintah <code>php artisan storage:link</code> tidak bekerja di shared hosting, kamu bisa membuat symbolic link secara manual melalui terminal SSH:
    <pre><code>ln -s /home/USERNAME/domains/NAMADOMAIN.com/public_html/storage/app/public /home/USERNAME/domains/NAMADOMAIN.com/public_html/public/storage</code></pre>
    <strong>Keterangan:</strong>
    <ul>
      <li><code>USERNAME</code>: ganti dengan username cPanel atau akun hosting kamu</li>
      <li><code>NAMADOMAIN.com</code>: ganti dengan nama domain milikmu</li>
    </ul>
    Contoh pada Niagahoster:
    <pre><code>ln -s /home/u123456789/domains/myapp.com/public_html/storage/app/public /home/u123456789/domains/myapp.com/public_html/public/storage</code></pre>
  </li>
</ul>

</body>
</html>
