(function(){
    var video = document.getElementById('video');
        overlay = document.getElementById('overlay');
        overlay2 = document.getElementById('overlay2');
        
        print = document.getElementById('print');
        save = document.getElementById('save');
        bow = document.getElementById('bow');
        heart = document.getElementById('heart');
        beast = document.getElementById('beast');
        starwars = document.getElementById('starwars');
        cat = document.getElementById('cat');
        ghost = document.getElementById('ghost');

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
        
        print.addEventListener('click', function(){
            overlay.src = "uploads/filters/print.png";
        });
        heart.addEventListener('click', function(){
            overlay.src = "uploads/filters/heart.png";
        });
        beast.addEventListener('click', function(){
            overlay.src = "uploads/filters/meme-removebg-preview.png";
        });
        starwars .addEventListener('click', function(){
            overlay2.src = "uploads/filters/starwars.png";
        });
        cat.addEventListener('click', function(){
            overlay2.src = "uploads/filters/cat.png";
        });
        ghost.addEventListener('click', function(){
            overlay2.src = "uploads/filters/ghost.png";
        });

        var canvas = document.getElementById('canvas');
            baseimage = canvas.getContext('2d');
            canvasOverlay = document.getElementById('canvasOverlay');
            overlayimage = canvasOverlay.getContext('2d');

        document.getElementById('capture').addEventListener('click', function(){
            baseimage.drawImage(video, 0, 0, 400, 300);
            overlayimage.clearRect(0, 0, 100, 100);
            overlayimage.drawImage(overlay, 0, 0, 100, 100);
            // overlay.src = "uploads/filters/print.png";
        });

        save.addEventListener('click', function(){
            var saved = canvas.toDataURL('image/png');
            
            
           // console.log(saved);

            const url = "camera_process.php";
            var xhttp = new XMLHttpRequest();
            var values = "baseimage="+saved;
            xhttp.open("POST", url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.onreadystatechange = function(){
                if(xhttp.readyState == 4 && xhttp.status == 200){
                    var response = xhttp.responseText;
                    //console.log(response);
                }
            }
            //console.log("PHP");
            xhttp.send(values);
        });
})();