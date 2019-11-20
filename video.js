
    var video = document.getElementById('video');
        overlay = document.getElementById('overlay');
        // overlay2 = document.getElementById('overlay2');
    let canvasOverlay = document.getElementById('canvasOverlay');
    
        // print = document.getElementById('print');
        // save = document.getElementById('save');
        // bow = document.getElementById('bow');
        // heart = document.getElementById('heart');
        // beast = document.getElementById('beast');
        // starwars = document.getElementById('starwars');
        // cat = document.getElementById('cat');
        // ghost = document.getElementById('ghost');

        //vendorUrl = window.URL || window.webkitURL;

        navigator.getMedia =    navigator.getUserMedia ||
                                navigator.webkitGetUserMedia ||
                                navigator.mozGetUserMedia ||
                                navigator.msGetUserMedia;
        navigator.getMedia({
            video: true,
            audio: false,
        }, function(stream){
            video.srcObject = stream;
            video.play();
        }, function(error){
            // an error occured
        });
  

        // video.addEventListener('play', function() {
        //   //  draw(this, context, 400, 300);
        // }, false);
        video.addEventListener('canplay', function(){
            canvasOverlay.width = 400;
            canvasOverlay.height = 300;
            // canvasOverlay.height = video.offsetHeight;
        })
        function addSticker(stickerid){
           document.getElementById(stickerid);
           stickerobj = new Image;
           stickerobj.src = "uploads/filters/"+stickerid+".png";
           if(stickerid = ghost){
                canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 400, 300);
           }
           else if (stickerid = starwars){
               canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 100, 100);
           }
           else if (stickerid = ghost){
            canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 100, 100);
            }
            else if (stickerid = ghost){
                canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 100, 100);
            }
            else if (stickerid = ghost){
                canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 100, 100);
            }
            else{
                canvasOverlay.getContext('2d').drawImage(stickerobj, 0, 0, 100, 100);
            }
        }

        var canvas = document.getElementById('canvas');
            baseimage = canvas.getContext('2d');

        document.getElementById('capture').addEventListener('click', function(){
            baseimage.drawImage(video, 0, 0, 400, 300);
        });

        save.addEventListener('click', function(){
            var saved = canvas.toDataURL('image/png');
            let stickerURL = canvasOverlay.toDataURL('image/png');
            const url = "camera_process.php";
            var xhttp = new XMLHttpRequest();
            var values = "baseimage="+saved+"&stickerURL="+stickerURL;
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function(){
                if(xhttp.status == 200){
                    console.log(this.responseText);
                }
            }
            xhttp.send(values);
        });