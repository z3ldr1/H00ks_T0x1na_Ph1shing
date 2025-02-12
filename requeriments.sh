#!/bin/bash

# Variáveis
c="clear"
i="sudo apt-get install -y"
p="sudo pip3 install"
b="--break-system-package"
px="pipx install xyz"

echo "[**Atualizando arquivos...**]"
sudo apt-get update -y && sudo apt-get full-upgrade -y
echo "[**Atualização completa**] "
$c

echo "[**Baixando dependências do Ameba...**]"

# Instalação de pacotes
$i python3-pip
$i docker-cli
$i apksigner
$i python2
$i python3
$i golang-go
$i git
$i nmap
$i nikto
$i whatweb
$i nuclei
$i subfinder
$i gobuster
$i wpscan
$i sherlock
$i proxychains4
$i ettercap-text-only
$i wget
$i whois
$i veil
$i hashcat
$i wordlists
$i set
$i httpx
$i pipx
$i systemctl
$i postgresql
$i apache2
$i tor
$i apktool
$i metagoofil
$i make
$c

# Instalando Cloudflare
echo "[**Baixando CloudFlare**]"
wget https://github.com/cloudflare/cloudflared/releases/download/2025.1.0/cloudflared-fips-linux-amd64 -O cloudflare
chmod +x cloudflare
sudo mv cloudflare /usr/local/bin/
echo "[**CloudFlare baixado!!**]"

# Instalando uro
echo "[**Fazendo o download do uro.**]"
$p uro $b
echo "[**Download do uro finalizado!**]"

# Instalando ferramentas de Phishing
echo "[**Fazendo download de ferramentas de Phishing**]"
$p maxphisher $b
$p colorama $b
$p sockets $b
$p requests $b
$p katana $b
$p waybackurls $b
$p hakrawler $b
$px
$c

# Clonando repositório NucleiFuzzer e executando instalação
if [ ! -d "NucleiFuzzer" ]; then
  echo "[**Clonando NucleiFuzzer**]"
  sudo git clone https://github.com/0xKayala/NucleiFuzzer.git
  cd NucleiFuzzer || exit
  sudo chmod +x install.sh && ./install.sh
  cd ..
else
  echo "[**Repositório NucleiFuzzer já clonado e pronto para funcionar!**]"
fi
$c

echo "[**Dependências baixadas com sucesso**]"
$c

# Movendo Toxina Phishing para o Apache2
echo "[**Movendo o Toxina Ph1shing para o Apache2**]"
sudo mkdir -p /var/www/html/page
sudo cp -r * /var/www/html/page
touch arquivo.exe
sudo systemctl start apache2
sudo systemctl enable apache2
echo "[**Toxina Ph1shing movido com sucesso!**]"
$c

# Atualização final e limpeza do sistema
sudo apt-get update -y && sudo apt-get full-upgrade -y
sudo apt-get autoremove -y
$c

echo "[**A atualização foi concluída com sucesso**]"
