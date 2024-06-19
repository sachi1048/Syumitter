
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Cropper</title>
    <link rel="stylesheet" href="js/cropper.min.css" />
    <link rel="stylesheet" href="CSS/main.css" />
    <style>
        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
        .img-container {
            width: 200px;
            height: 200px;
            position: relative;
        }
        img {
            max-width: 100%;
        }
        .result {
            margin-top: 20px;
            width: 200px;
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 50%;/*プレヴュー画像をアイコン型に切り抜く*/
        }
    </style>
</head>
<body>

        <div class="header">
            <h1>Syumitter</h1>
        </div>

    <div class="frame">
        <div class="container">
            <div class="img-container">
                <img id="image" src="icon.jpg" alt="Image">
            </div>
            <p>ズーム、移動が可能です。</p>
            <img id="result-img" class="result">
            <p>プレビュー↑</p>
            <p><button onclick="location.href='newgroup.php'">決定</button></p>
        </div>
    </div>
    <script src="js/cropper.min.js"></script>
    <script>
    /*画像切り抜きcropper.js*/
        document.addEventListener('DOMContentLoaded', function () {
            var image = document.getElementById('image');
            var cropper = new Cropper(image, {
                //トリミング部の初期設定
                viewMode: 3,
                dragMode: 'move',
                aspectRatio: 1,
                autoCropArea: 1,
                responsive: true,
                cropBoxResizable: false,
            });

             function cropp() {
                /*画像切り取り処理*/
                var resultImgUrl = cropper.getCroppedCanvas().toDataURL();
                var result = document.getElementById('result-img');
                result.src = resultImgUrl;
            }

            setInterval(cropp, 0.5);

            
        });
    </script>
</body>