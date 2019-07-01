$(document).ready(function(){

var save = $('#save'); result = $('#result'); content = $('#content');
$(save).hide(); $(result).hide();

$(save).on('click', function(){
    dataUrl = canvas.toDataURL('image/jpeg');
    //alert(dataUrl);return false;
    url = 'post.php';

    $.ajax({
        url: url,
        type: 'POST',
        data: 'pic='+encodeURIComponent(dataUrl),
        dataType: 'JSON',
        success: function(msg)
        {
            if (msg.success)
            {
                $(content).hide();
                $(result).fadeIn(1000).html('<img src="'+msg.img+'" /><br><p>Image sauvegardée!</p>');
            }
            else
            {
                alert('Une erreur est survenue');
            }
        }
    });
});

var canvas = document.getElementById('canvas');
context = canvas.getContext('2d');
video = document.getElementById('video');

document.getElementById('snap').addEventListener('click', function(){
    context.drawImage(video, 0, 0, 450, 350);
    $(save).fadeIn();
});

errorCallback = function(error){
    console.log(error.code);
}

if(navigator.webkitGetUserMedia){
    navigator.webkitGetUserMedia({video: true}, function(stream){
      video.srcObject = stream;
      video.play();
    }, errorCallback);
  } 
 
});