function addSup(el) {
    var imageSrc = el.src;
    var sup = document.getElementById('supImage');
    sup.setAttribute('src', imageSrc);
    document.getElementById('capture').disabled = false;
}

(function() {
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        sup = document.getElementById('supImage'),
        vendorUrl = window.URL || window.webkitURL;

    navigator.getMedia =    navigator.getUserMedia ||
                            navigator.webkitGetUserMedia ||
                            navigator.mozGetUserMedia;

    navigator.getMedia({
        video: true,
        audio: false
    }, function(stream) {
        video.src = vendorUrl.createObjectURL(stream);
        video.play();
    }, function(error) {

    });
    document.getElementById('capture').addEventListener('click', function() {
        context.drawImage(video, 0, 0, 640, 480);
        context.drawImage(sup, 0, 0, 640, 480);
        var element = document.getElementById('picture');
        var img = canvas.toDataURL('image/jpeg');
        element.value = img;
        document.getElementById('capture-form').submit();
    })
})();