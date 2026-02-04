#!/bin/bash
# Veritabanı Kurulum ve Konfigürasyon Scripti

echo "=================================="
echo "Veritabanı Kurulum Scripti"
echo "=================================="
echo ""

# Renk kodları
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Veritabanı bilgilerini kullanıcıdan al
read -p "MySQL root şifresi: " -s MYSQL_ROOT_PASSWORD
echo ""
read -p "Yeni veritabanı adı [demowa_teze]: " DB_NAME
DB_NAME=${DB_NAME:-demowa_teze}

read -p "Veritabanı kullanıcı adı [demowa_teze]: " DB_USER
DB_USER=${DB_USER:-demowa_teze}

read -p "Veritabanı kullanıcı şifresi: " -s DB_PASSWORD
echo ""

if [ -z "$DB_PASSWORD" ]; then
    echo -e "${RED}Veritabanı şifresi boş olamaz!${NC}"
    exit 1
fi

echo ""
echo -e "${YELLOW}Veritabanı oluşturuluyor...${NC}"

# Veritabanını oluştur
mysql -u root -p"$MYSQL_ROOT_PASSWORD" <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Veritabanı başarıyla oluşturuldu!${NC}"
else
    echo -e "${RED}Veritabanı oluşturulurken hata oluştu!${NC}"
    exit 1
fi

# SQL dosyasını içe aktar
echo -e "${YELLOW}Veritabanı şeması yükleniyor...${NC}"
mysql -u root -p"$MYSQL_ROOT_PASSWORD" $DB_NAME < database.sql

if [ $? -eq 0 ]; then
    echo -e "${GREEN}Veritabanı şeması başarıyla yüklendi!${NC}"
else
    echo -e "${RED}Veritabanı şeması yüklenirken hata oluştu!${NC}"
    exit 1
fi

# Config dosyasını güncelle
echo -e "${YELLOW}Konfigürasyon dosyası güncelleniyor...${NC}"

cat > config/database.php <<EOF
<?php
/**
 * Veritabanı Konfigürasyonu
 */

return [
    'host' => 'localhost',
    'dbname' => '$DB_NAME',
    'username' => '$DB_USER',
    'password' => '$DB_PASSWORD',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
EOF

echo -e "${GREEN}Konfigürasyon dosyası güncellendi!${NC}"

echo ""
echo -e "${GREEN}=================================="
echo "Veritabanı kurulumu tamamlandı!"
echo "==================================${NC}"
echo ""
echo "Veritabanı Bilgileri:"
echo "  Veritabanı: $DB_NAME"
echo "  Kullanıcı: $DB_USER"
echo "  Şifre: ********"
echo ""
echo "Varsayılan Admin Girişi:"
echo "  Kullanıcı Adı: admin"
echo "  Şifre: admin123"
echo ""
echo -e "${YELLOW}GÜVENLİK UYARISI: Admin şifresini mutlaka değiştirin!${NC}"
