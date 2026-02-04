#!/bin/bash
# IBM Sunucu Kurulum Scripti - FinTech Çark Sistemi

echo "=================================="
echo "FinTech Çark Sistemi Kurulumu"
echo "=================================="
echo ""

# Renk kodları
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Root kontrolü
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Bu script root yetkisiyle çalıştırılmalıdır${NC}"
    echo "Lütfen 'sudo bash install.sh' komutunu kullanın"
    exit 1
fi

# Sistem güncellemesi
echo -e "${YELLOW}[1/9] Sistem güncelleniyor...${NC}"
apt-get update -y
apt-get upgrade -y

# Apache kurulumu
echo -e "${YELLOW}[2/9] Apache web sunucusu kuruluyor...${NC}"
apt-get install -y apache2

# PHP ve gerekli modüller
echo -e "${YELLOW}[3/9] PHP 8.2 ve modüller kuruluyor...${NC}"
apt-get install -y software-properties-common
add-apt-repository ppa:ondrej/php -y
apt-get update -y
apt-get install -y php8.2 php8.2-cli php8.2-common php8.2-mysql php8.2-xml php8.2-curl php8.2-mbstring php8.2-zip php8.2-gd php8.2-intl libapache2-mod-php8.2

# MySQL/MariaDB kurulumu
echo -e "${YELLOW}[4/9] MySQL Server kuruluyor...${NC}"
apt-get install -y mysql-server

# Composer kurulumu
echo -e "${YELLOW}[5/9] Composer kuruluyor...${NC}"
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer

# Apache modüllerini etkinleştir
echo -e "${YELLOW}[6/9] Apache modülleri etkinleştiriliyor...${NC}"
a2enmod rewrite
a2enmod headers
a2enmod ssl

# Ngrok kurulumu
echo -e "${YELLOW}[7/9] Ngrok kuruluyor...${NC}"
cd /tmp
wget https://bin.equinox.io/c/bNyj1mQVY4c/ngrok-v3-stable-linux-amd64.tgz
tar -xzf ngrok-v3-stable-linux-amd64.tgz
mv ngrok /usr/local/bin/
chmod +x /usr/local/bin/ngrok

# Proje dizini oluştur
echo -e "${YELLOW}[8/9] Proje dizini hazırlanıyor...${NC}"
mkdir -p /var/www/carkson
chown -R www-data:www-data /var/www/carkson
chmod -R 755 /var/www/carkson

# Apache'yi yeniden başlat
echo -e "${YELLOW}[9/9] Apache yeniden başlatılıyor...${NC}"
systemctl restart apache2
systemctl enable apache2

echo ""
echo -e "${GREEN}=================================="
echo "Kurulum tamamlandı!"
echo "==================================${NC}"
echo ""
echo "Sıradaki adımlar:"
echo "1. Proje dosyalarını /var/www/carkson dizinine yükleyin"
echo "2. MySQL veritabanını oluşturun ve database.sql dosyasını içe aktarın"
echo "3. config/database.php dosyasını veritabanı bilgilerinizle güncelleyin"
echo "4. Apache VirtualHost yapılandırmasını yapın"
echo "5. Ngrok ile public URL oluşturun"
echo ""
echo "Detaylı talimatlar için DEPLOYMENT.md dosyasını okuyun"
