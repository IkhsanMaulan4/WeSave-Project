# WeSave - Aplikasi Keuangan Pribadi

**WeSave** adalah aplikasi web sederhana yang dibuat untuk membantumu mengatur keuangan sehari-hari. Mulai dari mencatat dompet, melacak pengeluaran, hingga bikin target menabung biar impianmu cepat tercapai.

Aplikasi ini dibangun menggunakan **Laravel 12** dan **Bootstrap 5**, jadi performanya ngebut dan tampilannya rapi. Project ini dibuat untuk memenuhi Tugas Final Web Programming.

---

## Fitur-Fitur Keren

Aplikasi ini punya fitur lengkap buat kebutuhan finansialmu:

* **Multi-Level User:** Ada akses khusus buat **Admin** (untuk kelola user & data master) dan **User Biasa** (buat catat keuangan sendiri).
* **Dua Bahasa (Bilingual):** Bisa ganti bahasa **Indonesia 🇮🇩** atau **Inggris 🇺🇸** sesuka hati lewat menu di pojok kanan atas.
* **Kelola Dompet:** Bisa bikin banyak dompet (misal: Cash, Bank BCA, Gopay) dan saldonya terpisah.
* **Catat Transaksi:** Input Pemasukan, Pengeluaran, atau Transfer antar dompet. Saldo bakal update otomatis!
* **Target Menabung (Saving Goals):** Bikin target nabung (misal: "Beli HP Baru") dan pantau progress-nya.
* **Laporan Simpel:** Dashboard interaktif buat lihat ringkasan duitmu.

---

## Panduan Instalasi (Cara Pakai)

Mau coba jalankan di laptopmu? Ikuti langkah gampang ini:

### 1. Persiapan
hal wajib instal:
* PHP (Minimal versi 8.2)
* Composer
* Node.js & NPM
* Database (MySQL/MariaDB/XAMPP)

### 2. Install Project
Buka terminal/CMD, lalu ketik perintah ini satu per satu:

```bash
# 1. Clone repository ini
git clone [https://github.com/username-kamu/wesave-project.git](https://github.com/username-kamu/wesave-project.git)

# 2. Masuk ke foldernya
cd wesave-project

# 3. Install library PHP
composer install

# 4. Install library Frontend (Bootstrap dll)
npm install && npm run build
