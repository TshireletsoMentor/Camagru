(function(){
    var canvas = document.getElementById('canvas');
        context = canvas.getContext('2d');
        video = document.getElementById('video');
        image = document.getElementById('image')
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
            draw(this, context, 400, 300);
        }, false);

        document.getElementById('capture').addEventListener('click', function(){
            context.drawImage(video, 0, 0, 400, 300);
            image.setAttribute('src', canvas.toDataURL('image/png'));
        });
})();