<!DOCTYPE html>
<html>
    <head>
        <title>JQuery HTML5 QR Code Scanner using Instascan JS Example - ItSolutionStuff.com</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    </head>
    <body>

        <h1>QR Code Scanner</h1>

        <video id="preview" width="600px"></video>
        <script type="text/javascript">
            let scanner = new Instascan.Scanner({video: document.getElementById('preview')});

            scanner.addListener('scan', function (content) {
                alert(content);
            });

            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('No cameras found.');
                }
            }).catch(function (e) {
                alert('No cameras found.');
            });
        </script>

    </body>
</html>