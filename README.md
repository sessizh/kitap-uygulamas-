📚 Book Management API & Web Application
Bu proje, JWT ile güvenli kimlik doğrulama, kullanıcı yetkilendirmesi ve modern Bootstrap 5 tasarımıyla geliştirilmiş bir kitap yönetim sistemidir.
Hem API (Backend - PHP & MySQL) hem de Web Arayüzü (HTML, CSS, JS) entegre olarak çalışır.

🚀 Özellikler
JWT ile Güvenli Giriş Sistemi

Kullanıcı ve Admin Rolleri

Kitap Ekleme, Düzenleme ve Silme (Admin Yetkisi Gerekli)

Modern ve Duyarlı (Responsive) Arayüz

Yüklenme Animasyonu (Spinner) ve Geçiş Efektleri

Yetki Kontrolleri ve Otomatik Token Zaman Aşımı

Gelişmiş Hata Yönetimi

📂 Proje Yapısı
pgsql
Kopyala
Düzenle
├── book_api/ (Backend API)
│   ├── auth/
│   │   └── login.php
│   ├── books/
│   │   ├── add.php
│   │   ├── update.php
│   │   ├── delete.php
│   │   └── list.php
│   └── config/
│       └── database.php
├── book_web/ (Frontend)
│   ├── index.html (Login Page)
│   ├── register.html
│   ├── books.html
│   ├── add_edit.html
│   ├── css/
│   │   └── styles.css
│   └── js/
│       └── api.js
└── README.md
🛠️ Kurulum
1️⃣ Gereksinimler
PHP 8.x

MySQL / phpMyAdmin

XAMPP (Tavsiye Edilir)

2️⃣ Veritabanı Yapısı
sql
Kopyala
Düzenle
CREATE DATABASE book_db;

USE book_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('user', 'admin') DEFAULT 'user'
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    author VARCHAR(100),
    category VARCHAR(100),
    description TEXT
);
🔑 Admin Kullanıcısı Ekleyin:

sql
Kopyala
Düzenle
INSERT INTO users (name, email, password, role) 
VALUES ('Admin', 'admin@example.com', '<HASHED_PASSWORD>', 'admin');
<HASHED_PASSWORD> için PHP password_hash() fonksiyonunu kullanın veya hazır hash ile ekleyin.

3️⃣ XAMPP Ayarları
htdocs içine book_api ve book_web klasörlerini kopyalayın.

Apache ve MySQL’i başlatın.

📖 API Kullanımı
Endpoint	Method	Yetki	Açıklama
/auth/login.php	POST	-	Kullanıcı Girişi
/books/list.php	GET	JWT	Kitap Listeleme
/books/add.php	POST	JWT	Kitap Ekleme
/books/update.php	POST	JWT	Kitap Güncelleme
/books/delete.php	DELETE	Admin	Kitap Silme

📌 Kullanıcı Rolleri
User: Sadece kitap görüntüleyebilir.

Admin: Kitap ekleyebilir, güncelleyebilir ve silebilir.

🎯 Yapılacaklar (TODO)
✅ Kullanıcı Kayıt Ekranı

✅ JWT ile Yetki Kontrolleri

✅ Yükleme Animasyonu

📅 Kitap Arama ve Filtreleme

📂 Export to PDF / Excel Özelliği

📱 Tam Mobil Uyum

