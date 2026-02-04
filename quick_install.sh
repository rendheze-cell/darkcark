#!/bin/bash
# Hızlı Kurulum Scripti - Tüm işlemleri tek seferde yapar

echo "========================================="
echo "FinTech Çark Sistemi - Hızlı Kurulum"
echo "========================================="
echo ""

# Renk kodları
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# Root kontrolü
if [ "$EUID" -ne 0 ]; then 
    echo -e "${RED}Bu script root yetkisiyle çalıştırılmalıdır${NC}"
    echo "Lütfen 'sudo bash quick_install.sh' komutunu kullanın"
    exit 1
fi

# Proje dizini kontrolü
PROJECT_DIR="/var/www/carkson"
if [ ! -d "$PROJECT_DIR" ]; then
    echo -e "${RED}Proje dizini bulunamadı: $PROJECT_DIR${NC}"
    echo "Lütfen önce projeyi bu dizine yükleyin"
    exit 1
fi

cd $PROJECT_DIR

# 1. Sistem kurulumu
echo -e "${BLUE}[1/6] Sistem bileşenleri kuruluyor...${NC}"
bash install.sh

# 2. Dosya izinleri
echo -e "${BLUE}[2/6] Dosya izinleri ayarlanıyor...${NC}"
chown -R www-data:www-data $PROJECT_DIR
find $PROJECT_DIR -type d -exec chmod 755 {} \;
find $PROJECT_DIR -type f -exec chmod 644 {} \;
chmod +x install.sh setup_database.sh quick_install.sh

# Özel dizinler
mkdir -p $PROJECT_DIR/public/uploads
chmod -R 777 $PROJECT_DIR/public/uploads

# 3. Composer bağımlılıkları
echo -e "${BLUE}[3/6] Composer bağımlılıkları yükleniyor...${NC}"
composer install --no-dev --optimize-autoloader

# 4. Apache VirtualHost
echo -e "${BLUE}[4/6] Apache yapılandırılıyor...${NC}"
cp apache-vhost.conf /etc/apache2/sites-available/carkson.conf
a2dissite 000-default.conf 2>/dev/null
a2ensite carkson.conf
systemctl restart apache2

# 5. Veritabanı kurulumu
echo -e "${BLUE}[5/6] Veritabanı kurulumu başlıyor...${NC}"
bash setup_database.sh

# 6. Ngrok talimatları
echo -e "${BLUE}[6/6] Ngrok yapılandırması...${NC}"
echo ""
echo -e "${YELLOW}Ngrok kurulumu için lütfen aşağıdaki adımları uygulayın:${NC}"
echo ""
echo "1. https://ngrok.com adresine gidin ve hesap oluşturun"
echo "2. Auth token'ınızı alın"
echo "3. Aşağıdaki komutu çalıştırın:"
echo -e "${GREEN}   ngrok config add-authtoken <your-token>${NC}"
echo ""
echo "4. Ngrok'u başlatın:"
echo -e "${GREEN}   ngrok http 80${NC}"
echo ""
echo "5. Ngrok URL'ini config/app.php dosyasında güncelleyin"
echo ""

echo -e "${GREEN}========================================="
echo "Kurulum başarıyla tamamlandı!"
echo "=========================================${NC}"
echo ""
echo "Admin Panel: http://localhost/admin"
echo "Kullanıcı Adı: admin"
echo "Şifre: admin123"
echo ""
echo -e "${YELLOW}ÖNEMLİ: Güvenlik için admin şifresini mutlaka değiştirin!${NC}"
echo ""
echo "Detaylı bilgi için: cat DEPLOYMENT.md"
