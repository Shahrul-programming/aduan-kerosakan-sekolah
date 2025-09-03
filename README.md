<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).




# 📌 Sistem Aduan Kerosakan Sekolah

Sistem ini membolehkan guru membuat aduan kerosakan di sekolah, kemudian dipantau oleh pihak pengurusan sekolah, dan seterusnya ditugaskan kepada kontraktor untuk pembaikan.  


## 🎯 Objektif


## 👥 Peranan (User Roles)

### 1. Super Admin
  1. Nama Sekolah  
  2. Kod Sekolah  
  3. Alamat Sekolah  
  4. Nama Pengetua  
  5. No WhatsApp Pengetua  
  6. Nama PK HEM  
  7. No WhatsApp PK HEM  

### 2. Pengurusan Sekolah
  - Aduan baru dihantar oleh guru.  
  - Kontraktor update progress.  

### 3. Guru / Staff
#### Proses Pendaftaran
1. Dapatkan QR daripada **Pengurusan Sekolah**.  
2. Scan QR → sistem automatik **capture nombor WhatsApp guru/staff**.  
3. Selepas itu, sistem paparkan borang pendaftaran:  
   - Nama Guru / Staff  
   - Email  
   - Password untuk login  
4. Selepas daftar, guru boleh login menggunakan email + password.  

#### Fungsi Guru

### 4. Kontraktor
  - Ada tugasan baru.  
  - Tugasan dihantar balik untuk penambahbaikan.  


## 🔔 Sistem Notifikasi (WhatsApp Gateway)
  - Guru terima update bila aduan status berubah.  
  - Pengurusan terima alert bila ada aduan baru atau kontraktor update progress.  
  - Kontraktor terima tugasan baru melalui WhatsApp.  


## 📊 Ciri Utama
  - Baru  
  - Dalam Semakan  
  - Assigned  
  - Dalam Proses  
  - Selesai  


## 🗄️ Struktur Database (Cadangan)

### Jadual Utama
1. **users**
   - id  
   - name  
   - email  
   - password  
   - role (super_admin, pengurusan, guru, kontraktor)  
   - school_id (nullable untuk super admin)  
   - phone (auto capture masa QR scan)  
   - created_at, updated_at  

2. **schools**
   - id  
   - name (nama sekolah)  
   - code (kod sekolah)  
   - address  
   - principal_name  
   - principal_phone  
   - hem_name (nama PK HEM)  
   - hem_phone (no WhatsApp PK HEM)  
   - qr_code (unik untuk daftar guru)  
   - created_at, updated_at  

3. **complaints**
   - id  
   - complaint_number (unik, contoh: ADUAN-2025-0001)  
   - school_id  
   - user_id (guru yang buat aduan)  
   - category (elektrik, air, bangunan, ict, dll)  
   - description  
   - image (nullable)  
   - video (nullable)  
   - priority (tinggi/sederhana/rendah)  
   - status (baru, semakan, assigned, proses, selesai)  
   - assigned_to (contractor_id)  
   - created_at, updated_at  

4. **contractors**
   - id  
   - name  
   - company_name  
   - phone  
   - email  
   - address  
   - created_at, updated_at  

5. **progress_updates**
   - id  
   - complaint_id  
   - contractor_id  
   - description  
   - image_before (nullable)  
   - image_after (nullable)  
   - created_at, updated_at  

6. **whatsapp_numbers**
   - id  
   - number  
   - status (active/inactive)  
   - qr_code (untuk login)  
   - session_data (token simpan session whatsapp web)  
   - created_at, updated_at  

7. **activity_logs**
   - id  
   - user_id  
   - action (contoh: "buat aduan", "assign kontraktor", "update status")  
   - complaint_id (nullable)  
   - created_at  


## 📝 Flow Sistem
1. **Pendaftaran Guru**  
   - Pengurusan sekolah download & beri QR → Guru scan → sistem capture nombor WhatsApp → borang daftar → akaun siap.  

2. **Guru buat aduan**  
   - Isi butiran aduan + gambar/video → sistem generate nombor unik.  

3. **Pengurusan sekolah urus aduan**  
   - Semak aduan → letak priority → assign kontraktor.  

4. **Kontraktor**  
   - Terima notifikasi WhatsApp → login → acknowledge tugasan.  
   - Update progress + upload gambar before/after.  
   - Bila siap, tukar status ke "Selesai".  

5. **Notifikasi WhatsApp** dihantar ke semua pihak pada setiap perubahan status.  

6. **Pengurusan** boleh muat turun laporan bila perlu.  


## 🔮 Teknologi Cadangan


## 📂 To-Do (Development Roadmap)

---

## 🏗️ Pelan Pembangunan Sistem Mengikut Fasa

### Fasa 1: Asas & Infrastruktur
- Setup projek Laravel & konfigurasi asas.
- Setup database & migrasi jadual utama (users, schools, complaints, contractors).
- Sistem authentication & role management (Super Admin, Pengurusan, Guru, Kontraktor).
- Dashboard asas untuk setiap peranan.
- CRUD Sekolah, Guru, Kontraktor.

### Fasa 2: Modul Aduan & Proses Utama
- Modul pendaftaran guru melalui QR code.
- Fungsi guru untuk buat aduan (termasuk upload gambar/video).
- Pengurusan boleh lihat, semak, dan tetapkan prioriti aduan.
- Sistem penjanaan nombor unik aduan.
- Status aduan: Baru, Dalam Semakan, Assigned, Dalam Proses, Selesai.

### Fasa 3: Tugasan & Progress
- Pengurusan assign aduan kepada kontraktor.
- Kontraktor acknowledge tugasan (terima/tolak).
- Kontraktor update progress kerja (upload gambar before/after).
- Audit trail/log aktiviti untuk semua perubahan.

### Fasa 4: Notifikasi & Komunikasi
- Integrasi WhatsApp notification (multi number + QR scan).
- Integrasi email notification sebagai backup.
- Sistem auto-reminder untuk aduan yang belum selesai.

### Fasa 5: Laporan & Analitik
- Modul laporan (PDF/Excel) untuk pengurusan & super admin.
- Dashboard analitik/statistik (jumlah aduan, status, prestasi, dsb).

### Fasa 6: Penambahbaikan & Customization
- Role customization (contoh: tambah technician dalaman).
- Penambahbaikan UI/UX dashboard (responsive/mobile friendly).
- Pembangunan aplikasi mudah alih (optional, jika perlu).
## 💡 Cadangan Penambahbaikan Sistem

1. **Integrasi Email**  
   Selain WhatsApp, tambah notifikasi melalui email untuk backup komunikasi.

2. **Auto-Reminder**  
   Sistem hantar reminder automatik jika aduan belum selesai dalam tempoh tertentu.

3. **Analitik & Statistik**  
   Dashboard analitik: jumlah aduan mengikut kategori, status, masa penyelesaian, prestasi kontraktor.

4. **Role Customization**  
   Benarkan admin sekolah tambah peranan baru (contoh: technician dalaman).

5. **Mobile App (optional)**  
   Bangunkan aplikasi mudah alih untuk pengalaman lebih lancar.


# 📐 Wireframe (Mock UI)

## 1. Pengurusan Sekolah - Download QR Pendaftaran Guru
Halaman untuk generate dan muat turun QR code.

------------------------------------------------
|  Sekolah Menengah Puncak Jalil               |
------------------------------------------------
| [ Generate QR Code Pendaftaran Guru ]        |
------------------------------------------------
| QR Code Preview:                             |
|  ███ █ ███ ███                               |
|  █ █ █ █ █ █ █                               |
|  ███ █ ███ ███                               |
------------------------------------------------
| [ Download QR as PNG ] [ Download QR as PDF ]|
------------------------------------------------

🔹 Fungsi:
- Butang untuk generate QR unik sekolah.  
- QR preview dipaparkan.  
- Pilihan download dalam format PNG/PDF.  

---

## 2. Guru - Daftar Akaun (Scan QR)
Proses bila guru scan QR → sistem capture nombor WhatsApp → paparkan borang pendaftaran.

------------------------------------------------
|  Pendaftaran Akaun Guru                      |
------------------------------------------------
| WhatsApp Number: +60123456789 (auto-capture) |
------------------------------------------------
| Nama Guru / Staff:  [_____________________]  |
| Email:              [_____________________]  |
| Password:           [_____________________]  |
| Confirm Password:   [_____________________]  |
------------------------------------------------
| [ Daftar Akaun ]                             |
------------------------------------------------

🔹 Fungsi:
- Nombor WhatsApp automatik diisi selepas scan QR.  
- Guru hanya perlu isi Nama, Email, dan Password.  
- Selepas daftar → boleh login guna email & password.  

---

## 3. Flow Ringkas UI
1. **Pengurusan Sekolah** → buka dashboard → generate QR → download & beri QR pada guru.  
2. **Guru** → scan QR → sistem auto-capture nombor WhatsApp → isi borang daftar → akaun siap.  
3. **Guru login** → boleh buat aduan.  
#   a d u a n - k e r o s a k a n - s e k o l a h  
 