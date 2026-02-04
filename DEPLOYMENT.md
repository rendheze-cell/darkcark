# IBM Sunucuya Kurulum Talimatları
# FinTech Çark Sistemi - Apache + Ngrok

## 📋 Gereksinimler

- IBM Cloud sunucu (Ubuntu 20.04/22.04 önerilir)
- Root/sudo erişimi
- En az 2GB RAM
- En az 20GB disk alanı
- İnternet bağlantısı

## 🚀 Adım Adım Kurulum

### 1. Sunucuya Bağlanma

SSH ile IBM sunucunuza bağlanın:

```bash
ssh root@<sunucu-ip-adresi>
```

### 2. Projeyi Sunucuya Yükleme

#### Seçenek A: Git ile (Önerilen)
```bash
cd /var/www
git clone <repo-url> carkson
cd carkson
```

#### Seçenek B: FTP/SCP ile
Lokal bilgisayarınızdan:
```bash
scp -r /Users/sinan/Documents/carkson root@<sunucu-ip>:/var/www/
```

### 3. Otomatik Kurulum Scripti

```bash
cd /var/www/carkson
chmod +x install.sh
sudo bash install.sh
```

Bu script şunları yapacak:
- ✅ Sistem güncellemesi
- ✅ Apache web sunucusu kurulumu
- ✅ PHP 8.2 ve gerekli modüller
- ✅ MySQL Server
- ✅ Composer
- ✅ Ngrok
- ✅ Apache modüllerinin etkinleştirilmesi

### 4. Veritabanı Kurulumu

```bash
chmod +x setup_database.sh
sudo bash setup_database.sh
```

Script sizden şunları soracak:
- MySQL root şifresi
- Yeni veritabanı adı (varsayılan: demowa_teze)
- Veritabanı kullanıcı adı
- Veritabanı şifresi

### 5. Composer Bağımlılıklarını Yükleme

```bash
composer install --no-dev --optimize-autoloader
```

### 6. Dosya İzinlerini Ayarlama

```bash
# Proje sahibini www-data yap
sudo chown -R www-data:www-data /var/www/carkson

# Dizin izinlerini ayarla
sudo find /var/www/carkson -type d -exec chmod 755 {} \;

# Dosya izinlerini ayarla
sudo find /var/www/carkson -type f -exec chmod 644 {} \;

# Özel izinler
sudo chmod -R 777 /var/www/carkson/public/uploads
```

### 7. Apache VirtualHost Yapılandırması

```bash
# VirtualHost dosyasını kopyala
sudo cp apache-vhost.conf /etc/apache2/sites-available/carkson.conf

# Varsayılan siteyi devre dışı bırak
sudo a2dissite 000-default.conf

# Yeni siteyi etkinleştir
sudo a2ensite carkson.conf

# Apache'yi test et
sudo apache2ctl configtest

# Apache'yi yeniden başlat
sudo systemctl restart apache2
```

### 8. Uygulama Ayarlarını Yapılandırma

```bash
# .env dosyası oluştur
cp .env.example .env
nano .env
```

Aşağıdaki ayarları yapın:
```env
APP_DEBUG=false
APP_BASE_URL=http://localhost  # Ngrok URL'ini buraya yazacaksınız

# Telegram ayarları (config/telegram.php'de de güncelleyin)
TELEGRAM_BOT_TOKEN=your-bot-token
TELEGRAM_CHAT_ID=your-chat-id
```

### 9. Ngrok Kurulumu ve Yapılandırması

#### Ngrok Hesabı Oluşturma
1. https://ngrok.com adresine gidin
2. Ücretsiz hesap oluşturun
3. Dashboard'dan Auth Token'ınızı alın

#### Ngrok'u Yapılandırma
```bash
# Ngrok'u auth token ile yapılandır
ngrok config add-authtoken <your-auth-token>
```

#### Ngrok'u Başlatma

```bash
# 80 portunu public hale getir
ngrok http 80
```

Ngrok şöyle bir çıktı verecek:
```
Forwarding  https://xxxx-xx-xxx-xxx-xx.ngrok-free.app -> http://localhost:80
```

#### Ngrok'u Arka Planda Çalıştırma

```bash
# Screen session oluştur
screen -S ngrok

# Ngrok'u başlat
ngrok http 80

# Screen'den çık (Ctrl+A, sonra D)
# Geri dönmek için: screen -r ngrok
```

Ya da systemd servisi oluşturun:

```bash
sudo nano /etc/systemd/system/ngrok.service
```

İçeriği:
```ini
[Unit]
Description=Ngrok Tunnel
After=network.target

[Service]
Type=simple
User=root
WorkingDirectory=/root
ExecStart=/usr/local/bin/ngrok http 80
Restart=on-failure

[Install]
WantedBy=multi-user.target
```

Servisi başlatın:
```bash
sudo systemctl daemon-reload
sudo systemctl enable ngrok
sudo systemctl start ngrok
sudo systemctl status ngrok
```

### 10. Ngrok URL'ini Uygulamaya Ekleyin

Ngrok URL'inizi aldıktan sonra:

```bash
nano /var/www/carkson/config/app.php
```

`base_url` değerini güncelleyin:
```php
'base_url' => 'https://xxxx-xx-xxx-xxx-xx.ngrok-free.app',
```

### 11. Admin Paneline Giriş

Admin paneline erişim:
```
https://your-ngrok-url.ngrok-free.app/admin
```

Varsayılan giriş bilgileri:
- **Kullanıcı Adı:** admin
- **Şifre:** admin123

⚠️ **GÜVENLİK:** İlk girişten sonra mutlaka şifreyi değiştirin!

### 12. Test ve Doğrulama

```bash
# Apache durumunu kontrol et
sudo systemctl status apache2

# PHP versiyonunu kontrol et
php -v

# MySQL durumunu kontrol et
sudo systemctl status mysql

# Apache error loglarını kontrol et
sudo tail -f /var/log/apache2/carkson-error.log
```

Ana sayfayı test edin:
```
https://your-ngrok-url.ngrok-free.app
```

## 🔧 Sorun Giderme

### Apache başlamıyor
```bash
# Hata loglarını kontrol et
sudo tail -50 /var/log/apache2/error.log

# Port kontrolü
sudo netstat -tulpn | grep :80
```

### PHP çalışmıyor
```bash
# PHP modülünü kontrol et
sudo a2enmod php8.2
sudo systemctl restart apache2
```

### Veritabanı bağlantı hatası
```bash
# MySQL'in çalıştığını kontrol et
sudo systemctl status mysql

# Kullanıcı yetkilerini kontrol et
mysql -u root -p
```
```sql
SHOW GRANTS FOR 'demowa_teze'@'localhost';
```

### Ngrok bağlantısı kesiliyor
```bash
# Ngrok loglarını kontrol et
curl http://localhost:4040/api/tunnels

# Ngrok'u yeniden başlat
pkill ngrok
ngrok http 80
```

### 403 Forbidden hatası
```bash
# Dosya izinlerini kontrol et
ls -la /var/www/carkson/public

# İzinleri düzelt
sudo chown -R www-data:www-data /var/www/carkson
sudo chmod -R 755 /var/www/carkson
```

## 🔐 Güvenlik Önerileri

1. **Admin Şifresini Değiştirin**
   - İlk girişten sonra admin panelden şifreyi güncelleyin

2. **Veritabanı Şifrelerini Güçlendirin**
   ```bash
   mysql_secure_installation
   ```

3. **Firewall Kuralları**
   ```bash
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw allow 22/tcp
   sudo ufw enable
   ```

4. **SSL Sertifikası** (Opsiyonel - Ngrok zaten HTTPS sağlıyor)
   ```bash
   sudo apt-get install certbot python3-certbot-apache
   ```

5. **Debug Modunu Kapatın**
   ```php
   // config/app.php
   'debug' => false,
   ```

## 📊 Performans Optimizasyonu

### PHP Optimizasyonu
```bash
sudo nano /etc/php/8.2/apache2/php.ini
```

Ayarlar:
```ini
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
upload_max_filesize = 20M
post_max_size = 25M
opcache.enable=1
opcache.memory_consumption=128
```

### Apache Optimizasyonu
```bash
sudo nano /etc/apache2/mods-available/mpm_prefork.conf
```

```apache
<IfModule mpm_prefork_module>
    StartServers 5
    MinSpareServers 5
    MaxSpareServers 10
    MaxRequestWorkers 150
    MaxConnectionsPerChild 3000
</IfModule>
```

Apache'yi yeniden başlatın:
```bash
sudo systemctl restart apache2
```

## 🔄 Güncelleme ve Bakım

### Projeyi Güncelleme
```bash
cd /var/www/carkson
git pull origin main
composer install --no-dev --optimize-autoloader
sudo systemctl restart apache2
```

### Yedekleme
```bash
# Veritabanı yedeği
mysqldump -u demowa_teze -p demowa_teze > backup_$(date +%Y%m%d).sql

# Dosya yedeği
tar -czf carkson_backup_$(date +%Y%m%d).tar.gz /var/www/carkson
```

### Logları Temizleme
```bash
# Apache logları
sudo truncate -s 0 /var/log/apache2/*.log

# Eski logları sil
sudo find /var/log -name "*.log" -mtime +30 -delete
```

## 📞 Destek ve Yardım

Sorun yaşarsanız:
1. Error loglarını kontrol edin
2. PHP ve Apache konfigürasyonlarını gözden geçirin
3. Ngrok bağlantısını kontrol edin
4. Veritabanı bağlantısını test edin

## 🎉 Tamamlandı!

Kurulum tamamlandı! Siteniz şimdi şu adreste çalışıyor:
- **Ana Site:** https://your-ngrok-url.ngrok-free.app
- **Admin Panel:** https://your-ngrok-url.ngrok-free.app/admin

İyi çalışmalar! 🚀
