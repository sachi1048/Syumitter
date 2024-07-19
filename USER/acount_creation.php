<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/main.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.file__label input[type=file]').on('change', function () {
                var file = $(this).prop('files')[0];
                $('.file__none').text(file.name);
            });

            $('#passwordForm').on('submit', function(event) {
                var password1 = $('#password1').val();
                var password2 = $('#password2').val();
                var icon = $('input[name="aikon"]').val();
                var errorMessage = $('#errorMessage');

                if (password1 !== password2) {
                    event.preventDefault(); // フォームの送信を止める
                    errorMessage.text('パスワードが一致しません。').show(); // エラーメッセージを表示する
                } else if (icon === '') {
                    event.preventDefault(); // フォームの送信を止める
                    errorMessage.text('アイコンを選択してください。').show(); // エラーメッセージを表示する
                } else {
                    errorMessage.hide(); // エラーメッセージを非表示にする
                }
            });
        });
    </script>
    <title>アカウント新規作成画面</title>
</head>
<body>
    <h1 class="h1-1">Syumitter</h1>
    <div class="frame">
        <h2>アカウント新規作成</h2>

        <?php
        if (isset($_GET['error'])) {
            echo '<p id="errorMessage" style="color:#FF0000;">' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') . '</p>';
        } else {
            echo '<p id="errorMessage" style="color:#FF0000; display:none;"></p>';
        }
        ?>

        <form id="passwordForm" action="acount_done.php" method="POST" enctype="multipart/form-data">
            <div class="aikon">
                アイコン
                <input type="file" name="aikon">
            </div>
            <table style="margin:auto;">
                <tr>
                    <td>ユーザー名</td>
                    <td><input type="text" name="username"></td>
                </tr>
                <tr>
                    <td>名前</td>
                    <td><input type="text" name="name"></td>
                </tr>
                <tr>
                    <td>プロフィール</td>
                    <td><textarea class="textbox" name="profile"></textarea></td>
                </tr>
                <tr>
                    <td>アドレス</td>
                    <td><input type="text" name="email"></td>
                </tr>
                <tr>
                    <td>パスワード</td>
                    <td><input type="password" id="password1" name="password1"></td>
                </tr>
                <tr>
                    <td>確認パスワード</td>
                    <td><input type="password" id="password2" name="password2" required></td>
                </tr>
            </table>
            
            <!-- Add hidden input to capture selected options -->
            <input type="hidden" name="selectedOptions" id="selectedOptions">
            <button class="nextbutton" type="submit">作成</button>
        </form>
        <button class="back-button"># 趣味を選び直す</button>
    </div>
    
    <script>
        // Update hidden input with selected options
        var selectedOptions = [];
        document.querySelectorAll('input[type="checkbox"]:checked').forEach(function(checkbox) {
            selectedOptions.push(checkbox.value);
        });
        document.getElementById('selectedOptions').value = selectedOptions.join(',');
    </script>
</body>
</html>
