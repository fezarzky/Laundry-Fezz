<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Laundry Fezz - Sistem Manajemen Laundry

## Deskripsi Proyek
Laundry Fezz adalah sistem manajemen laundry berbasis web yang dikembangkan menggunakan PHP, MySQL, dan Bootstrap. Sistem ini memungkinkan pengelolaan data pelanggan, paket laundry, dan transaksi dengan interface yang user-friendly.

## Fitur Utama

### 🔐 Autentikasi
- Login & Register
- Manajemen Session
- Role-based Access Control (Admin & Pelanggan)

### 👥 Manajemen Pengguna
- Profile Management
- Change Password
- User Registration

### 📦 Manajemen Paket
- CRUD Paket Laundry
- Harga per Kilogram
- Deskripsi Layanan

### 💰 Manajemen Transaksi
- Input Transaksi Baru
- Tracking Status (Pending, Proses, Selesai)
- Kalkulasi Otomatis Total Harga
- History Transaksi

### 📊 Dashboard
- Statistik Real-time
- Recent Transactions
- User Analytics

## Teknologi yang Digunakan

### Backend
- **PHP 7.4+** - Server-side scripting
- **MySQL 5.7+** - Database management
- **MySQLi** - Database connectivity

### Frontend
- **HTML5** - Markup language
- **CSS3** - Styling
- **Bootstrap 5.1.3** - CSS framework
- **JavaScript (ES6)** - Client-side scripting
- **Font Awesome 6.0** - Icons
- **DataTables** - Enhanced tables

### Development Tools
- **Composer** - PHP dependency manager
- **npm** - Node package manager
- **Vite** - Build tool

## Persyaratan Sistem

### Server Requirements
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Apache/Nginx Web Server
- Extension yang diperlukan:
  - php-mysqli
  - php-pdo
  - php-json
  - php-session

### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/yourusername/laundry-fezz.git
cd laundry-fezz
```

### 2. Setup Database
```sql
-- Buat database
CREATE DATABASE laundry_fezz;

-- Import schema
mysql -u root -p laundry_fezz < database/schema.sql
```

### 3. Konfigurasi Database
Edit file `routes/config.php`:
```php
private $host = "localhost";
private $username = "root";
private $password = "your_password";
private $database = "laundry_fezz";
```

### 4. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Build assets
npm run build
```

### 5. Setup Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

### 6. Run Application
```bash
# Development server
php -S localhost:8000 -t public/

# Or use your web server (Apache/Nginx)
```

## Struktur Database

### Tabel Utama

#### 1. jenis_kelamin
- `id` (Primary Key)
- `kode_jk` (Unique)
- `jenis_kelamin`

#### 2. user
- `id` (Primary Key)
- `username` (Unique)
- `password`
- `akses_id` (1=Admin, 2=Pelanggan)
- `kode_pelanggan` (Unique)
- `nama_pelanggan`
- `jenis_kelamin` (Foreign Key)
- `alamat`
- `email`
- `no_hp`
- `created_at`
- `updated_at`

#### 3. paket
- `id` (Primary Key)
- `kode_paket` (Unique)
- `paket`
- `harga_paket`
- `deskripsi`
- `created_at`
- `updated_at`

#### 4. transaksi
- `id` (Primary Key)
- `kode_transaksi` (Unique)
- `kode_pelanggan` (Foreign Key)
- `kode_paket` (Foreign Key)
- `harga`
- `kilo`
- `total`
- `tanggal_masuk`
- `tanggal_keluar`
- `status`
- `created_at`
- `updated_at`

## Panduan Penggunaan

### Login Default
- **Admin**: 
  - Username: `admin`
  - Password: `admin123`

### Workflow Transaksi
1. Admin/Pelanggan login ke sistem
2. Pilih menu "Transaksi"
3. Klik "Tambah Transaksi"
4. Isi data pelanggan dan pilih paket
5. Input berat cucian
6. Sistem menghitung total otomatis
7. Simpan transaksi
8. Update status sesuai progress

### Manajemen Paket
1. Login sebagai Admin
2. Pilih menu "Kelola Data" > "Data Paket"
3. Tambah/Edit/Hapus paket sesuai kebutuhan

## API Endpoints

### Authentication
- `POST /process/login_process.php` - User login
- `POST /process/register_process.php` - User registration
- `GET /logout.php` - User logout

### Transactions
- `GET /pages/transaksi/index.php` - View transactions
- `POST /pages/transaksi/process.php` - Create/Update/Delete transaction

### Packages
- `GET /pages/paket/index.php` - View packages
- `POST /pages/paket/process.php` - Create/Update/Delete package

### Profile
- `GET /pages/profile/index.php` - View profile
- `POST /pages/profile/process.php` - Update profile/password

## Security Features

### Input Validation
- SQL Injection prevention menggunakan `mysqli_real_escape_string()`
- XSS protection dengan `htmlspecialchars()`
- CSRF protection dengan session validation

### Session Management
- Secure session handling
- Auto logout setelah inaktivitas
- Role-based access control

### Password Security
- Password hashing (recommended: `password_hash()`)
- Password strength validation
- Secure password reset

## Optimisasi Performa

### Database
- Indexing pada kolom yang sering diquery
- Stored procedures untuk operasi kompleks
- View untuk join table yang sering digunakan

### Frontend
- Minifikasi CSS dan JS
- Image optimization
- Lazy loading untuk tabel besar
- DataTables untuk pagination

## Rencana Pengembangan (React.js Integration)

### Migrasi ke React.js
1. **Phase 1**: Setup React environment
   - Install React, Redux, Axios
   - Setup build configuration
   - Create component structure

2. **Phase 2**: Convert UI Components
   - Login/Register forms
   - Dashboard components
   - Data tables with React

3. **Phase 3**: API Development
   - Convert PHP to REST API
   - JWT authentication
   - API documentation

4. **Phase 4**: Advanced Features
   - Real-time notifications
   - Progressive Web App (PWA)
   - Mobile responsiveness

### Keunggulan React.js Implementation
- **Better User Experience**: SPA dengan loading yang lebih cepat
- **Component Reusability**: Komponen yang dapat digunakan ulang
- **State Management**: Redux untuk state management yang lebih baik
- **Modern Development**: Hot reload, modern JavaScript features
- **SEO Friendly**: Server-side rendering dengan Next.js
- **Mobile Ready**: React Native untuk aplikasi mobile

## Troubleshooting

### Common Issues

#### Database Connection Error
```
Koneksi database gagal: Access denied for user
```
**Solution**: Periksa kredensial database di `routes/config.php`

#### Session Issues
```
Headers already sent error
```
**Solution**: Pastikan tidak ada output sebelum `session_start()`

#### Permission Denied
```
403 Forbidden Error
```
**Solution**: Set proper file permissions (755 for directories, 644 for files)

## Contributing

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Contact

- **Developer**: Your Name
- **Email**: your.email@example.com
- **Project Link**: https://github.com/yourusername/laundry-fezz

## Acknowledgments

- Bootstrap team untuk CSS framework
- Font Awesome untuk icon set
- DataTables untuk enhanced table functionality
- PHP community untuk dokumentasi yang lengkap

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
