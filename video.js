(function(){
    var canvas = document.getElementById('canvas');
        context = canvas.getContext('2d');
        video = document.getElementById('video');
        image = document.getElementById('image');
        save = document.getElementById('save');
        vendorUrl = window.URL || window.webkitURL;

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

        video.addEventListener('play', function() {
          //  draw(this, context, 400, 300);
        }, false);


        document.getElementById('capture').addEventListener('click', function(){
            context.drawImage(video, 0, 0, video.width, video.height);
            image.setAttribute('src', canvas.toDataURL('image/png'));
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