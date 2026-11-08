#!/usr/bin/env bash
set -euo pipefail

PANEL_DIR="/var/www/pterodactyl"
REPO_URL="https://github.com/firllyfikaa/NEOBRUTALIS-Pterodactyl/archive/refs/heads/main.zip"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

echo "======================================"
echo "   NEOBRUTALIS Auto-Installer v1.0"
echo "======================================"

if [ "$EUID" -ne 0 ]; then
  echo "❌ Harap jalankan script ini sebagai root (sudo ./install.sh)"
  exit 1
fi

if [ ! -d "$PANEL_DIR" ]; then
  echo "❌ Pterodactyl tidak ditemukan di $PANEL_DIR"
  exit 1
fi

echo "[1/6] Memeriksa dependensi (Node.js, Yarn)..."
if ! command -v node >/dev/null 2>&1; then
    echo "Node.js tidak ditemukan, menginstal..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi

if ! command -v yarn >/dev/null 2>&1; then
    echo "Yarn tidak ditemukan, menginstal..."
    npm install -g yarn
fi

echo "[2/6] Mengunduh dan mengekstrak tema..."
cd /tmp
rm -rf NEOBRUTALIS-Pterodactyl-main main.zip
wget -q "$REPO_URL" -O main.zip
unzip -q main.zip
THEME_DIR="/tmp/NEOBRUTALIS-Pterodactyl-main"

echo "[3/6] Membackup dan menyalin file tema..."
cd "$PANEL_DIR"
# Backup
BACKUP_DIR="storage/neo-backups/$(date +%Y%m%d-%H%M%S)"
mkdir -p "$BACKUP_DIR"
cp -r resources "$BACKUP_DIR/"
cp -r public "$BACKUP_DIR/"

# Salin file
cp -r "$THEME_DIR/resources/"* resources/
cp -r "$THEME_DIR/public/"* public/
cp -r "$THEME_DIR/app/Http/Controllers/Admin/Theme" app/Http/Controllers/Admin/

echo "[4/6] Menambal Database (Membuat tabel & kolom theme)..."
# Memanggil theme_patch.php yang sekarang berada di folder yang sama dengan script ini
php artisan tinker "$SCRIPT_DIR/theme_patch.php"

echo "[5/6] Membangun Frontend (Build)..."
export NODE_OPTIONS=--openssl-legacy-provider
yarn install
yarn build:production

echo "[6/6] Membersihkan Cache..."
php artisan optimize:clear

echo "======================================"
echo "✅ INSTALASI NEOBRUTALIS SELESAI!"
echo "======================================"
echo "Silakan buka panel Anda dan lakukan Hard Refresh (Ctrl + Shift + R)."
echo "Backup file lama Anda tersimpan di: $PANEL_DIR/$BACKUP_DIR"
