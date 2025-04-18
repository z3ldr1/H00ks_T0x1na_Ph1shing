#!/bin/bash

# Variáveis
c="clear"
i="sudo apt-get install -y"
p="sudo pip3 install"
b="--break-system-packages"  # Corrigido o nome da opção

echo "[**Atualizando arquivos...**]"
sudo apt-get update -y
echo "[**Atualização completa**]"
$c

# Instalação de pacotes básicos
$i python3-pip
$i php-curl
$i python2
$i python3
$i wget
$i apache2


# Instalando Cloudflared (mantido como no original)
echo "[**Baixando Cloudflared**]"
wget https://github.com/cloudflare/cloudflared/releases/download/2025.1.0/cloudflared-fips-linux-amd64 -O cloudflared
chmod +x cloudflared
sudo mv cloudflared /usr/local/bin/
echo "[**Cloudflared baixado e instalado com sucesso!!**]"

# Instalando e configurando BeEF
echo "[**Baixando BeEF**]"
mkdir -p /API-BEEF && cd /API-BEEF
git clone https://github.com/beefproject/beef.git
cd beef
sudo ./install
echo "[**BeEF baixado e instalado**]"

# Configurando BeEF (assumindo que config.yaml já existe no diretório pai)
echo "[**Configurando o BeEF**]"
cd /API-BEEF
chmod +x config.yaml
sudo cp -r config.yaml beef/
echo "[**BeEF configurado com sucesso**]"

# Atualização final e limpeza do sistema
sudo apt-get update -y && sudo apt-get full-upgrade -y
sudo apt-get autoremove -y
$c

echo "[**A atualização foi concluída com sucesso**]"
