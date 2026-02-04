# FinTech Çark Sistemi

Finlandiya bankaları için etkileşimli çark sistemi.

## 🚀 Özellikler

- 🎡 Etkileşimli çark sistemi
- 🏦 Çoklu banka desteği (Nordea, OP, Danske, Handelsbanken, Aktia, Ålandsbanken, OmaSP)
- 👨‍💼 Kapsamlı admin paneli
- 📱 Telegram entegrasyonu
- 🔒 Güvenli oturum yönetimi
- 📊 Kullanıcı takibi ve raporlama

## 📋 Gereksinimler

- PHP >= 8.2
- MySQL/MariaDB 5.7+
- Apache Web Server
- Composer
- Ngrok (public URL için)

## 🛠️ Kurulum

### Hızlı Kurulum (Ubuntu/Debian)

```bash
# Projeyi klonlayın
git clone <repository-url> carkson
cd carkson

# Kurulum scriptini çalıştırın
chmod +x quick_install.sh
sudo bash quick_install.sh
```

### Manuel Kurulum

Detaylı kurulum talimatları için [DEPLOYMENT.md](DEPLOYMENT.md) dosyasına bakın.

## ⚙️ Yapılandırma

### 1. Ortam Değişkenleri

```bash
cp .env.example .env
nano .env
```

### 2. Veritabanı

```bash
# Veritabanını oluşturun
mysql -u root -p < database.sql

# Veritabanı bilgilerini güncelleyin
nano config/database.php
```

### 3. Telegram (Opsiyonel)

```bash
nano config/telegram.php
```

## 🚀 Çalıştırma

### Apache ile

```bash
# Apache'yi başlatın
sudo systemctl start apache2

# Ngrok ile public URL oluşturun
ngrok http 80
```

Site erişim:
- **Ana Site:** http://localhost veya ngrok URL
- **Admin Panel:** http://localhost/admin

### Admin Girişi

- **Kullanıcı Adı:** admin
- **Şifre:** admin123

⚠️ **ÖNEMLİ:** İlk girişten sonra şifreyi mutlaka değiştirin!

## 📁 Proje Yapısı

```
carkson/
├── admin/              # Admin panel
│   ├── Controllers/    # Admin controller'ları
│   ├── Models/         # Admin modelleri
│   └── Views/          # Admin görünümleri
├── app/                # Ana uygulama
│   ├── Controllers/    # Controller'lar
│   ├── Core/           # Core sınıflar
│   ├── Models/         # Model'ler
│   └── Views/          # Görünümler
├── config/             # Yapılandırma dosyaları
├── public/             # Public erişilebilir dosyalar
│   ├── css/           # Stil dosyaları
│   ├── js/            # JavaScript dosyaları
│   └── images/        # Görseller
└── vendor/            # Composer bağımlılıkları
```

## 🔐 Güvenlik

- Debug modunu production'da kapatın (`config/app.php`)
- Admin şifresini değiştirin
- Veritabanı şifrelerini güçlü yapın
- `.env` dosyasını asla commit etmeyin
- Firewall kurallarını ayarlayın
- SSL sertifikası kullanın (Ngrok otomatik sağlar)

## 📊 Veritabanı

Sistem aşağıdaki tabloları kullanır:

- `banks` - Banka bilgileri
- `users` - Kullanıcı verileri
- `user_sessions` - Oturum bilgileri
- `admin_users` - Admin kullanıcılar
- `settings` - Sistem ayarları

## 🤝 Katkıda Bulunma

1. Fork edin
2. Feature branch oluşturun (`git checkout -b feature/amazing`)
3. Commit edin (`git commit -m 'feat: Add amazing feature'`)
4. Push edin (`git push origin feature/amazing`)
5. Pull Request açın

## 📝 Lisans

Bu proje özel kullanım içindir.

## 📞 Destek

Sorun yaşarsanız [DEPLOYMENT.md](DEPLOYMENT.md) dosyasındaki sorun giderme bölümüne bakın.

## 🎯 Roadmap

- [ ] API entegrasyonu
- [ ] Mobil uygulama
- [ ] Çoklu dil desteği
- [ ] Gelişmiş raporlama
- [ ] Email bildirimleri

---

**Not:** Bu sistem eğitim ve test amaçlıdır. Production ortamında kullanmadan önce güvenlik önlemlerini almayı unutmayın.
