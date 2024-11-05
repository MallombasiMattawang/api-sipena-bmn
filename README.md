API Sipena BMN

Deskripsi

API Sipena BMN adalah backend API berbasis Laravel yang dikembangkan untuk mendukung aplikasi Sipena BMN (Sistem Pengendalian Barang Milik Negara). Aplikasi ini berfungsi sebagai sistem pendataan, monitoring, pengendalian/inspeksi, serta pelaporan Barang Milik Negara (BMN), yang bertujuan untuk mendukung pengelolaan BMN secara efisien.

API ini menyediakan endpoint untuk mengelola data BMN, memantau status barang, melakukan inspeksi, dan menghasilkan laporan sesuai kebutuhan pengguna.

Fitur Utama

	1.	Endpoint Pendataan BMN
Mengelola data aset BMN termasuk menambahkan, memperbarui, menghapus, dan melihat data BMN.
	2.	Endpoint Monitoring BMN
Memungkinkan pemantauan status dan kondisi BMN secara real-time, termasuk riwayat kondisi dan penggunaan.
	3.	Endpoint Pengendalian/Inspeksi BMN
Menyediakan fitur untuk pencatatan hasil inspeksi dan pengendalian BMN secara berkala.
	4.	Endpoint Pelaporan BMN
Mendukung pembuatan laporan aset BMN dengan data yang dapat disesuaikan sesuai kebutuhan pelaporan.

Teknologi yang Digunakan

	•	Framework: Laravel
	•	Database: MySQL / PostgreSQL
	•	Autentikasi: Laravel Sanctum / Passport untuk otorisasi API
	•	Dokumentasi API: Swagger / Laravel API Documentation (opsional)

Persyaratan

	•	PHP >= 8.0
	•	Composer
	•	MySQL / PostgreSQL
	•	Laravel 9.x

Instalasi

	1.	Clone repository ini:

git clone https://github.com/MallombasiMattawang/api-sipena-bmn.git
cd api-sipena-bmn


	2.	Instal dependensi:

composer install


	3.	Salin file konfigurasi .env:

cp .env.example .env


	4.	Konfigurasi database di file .env:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username_database
DB_PASSWORD=password_database


	5.	Generate application key:

php artisan key:generate


	6.	Jalankan migrasi database:

php artisan migrate


	7.	(Opsional) Seed database dengan data contoh:

php artisan db:seed


	8.	Jalankan server lokal:

php artisan serve



API sekarang dapat diakses di http://localhost:8000.

Penggunaan API

API ini menyediakan endpoint yang dapat diakses oleh frontend Sipena BMN untuk mengelola data BMN. Beberapa contoh endpoint:

	•	POST /api/login - Endpoint untuk login pengguna.
	•	GET /api/bmn - Mendapatkan daftar semua BMN.
	•	POST /api/bmn - Menambahkan data BMN baru.
	•	PUT /api/bmn/{id} - Memperbarui data BMN berdasarkan ID.
	•	DELETE /api/bmn/{id} - Menghapus data BMN berdasarkan ID.
	•	GET /api/inspeksi - Mendapatkan daftar inspeksi BMN.

Catatan: Semua endpoint dilindungi oleh autentikasi, jadi Anda harus mengirimkan token otorisasi dalam setiap permintaan.

Autentikasi

API menggunakan Laravel Sanctum untuk otorisasi. Pastikan frontend Sipena BMN mengirimkan token autentikasi yang valid di header setiap permintaan ke endpoint yang memerlukan autentikasi.

Dokumentasi API

Dokumentasi API dapat diakses dengan menggunakan tool seperti Swagger atau Postman. Jika menggunakan Swagger, Anda dapat menambahkan rute dokumentasi pada aplikasi Laravel untuk memudahkan eksplorasi dan pengujian endpoint.
