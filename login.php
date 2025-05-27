<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        #title {
            text-align: center;
            background-color: #f0f0f0;
            padding: 20px 0;
        }
        #title h1 {
            margin: 0;
        }
        #content {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            border: 2px #658db449 solid;
            background-color: white;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: inline-block;
            width: 100px;
            text-align: right;
            margin-right: 10px;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 200px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .btn {
            border-radius: 5px;
            background-color: lightblue;
            padding: 10px 20px;
            text-decoration: none;
            color: black;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .register-link {
            margin-top: 15px;
        }
        .register-link a {
            color: #658db4;
            text-decoration: none;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div id="title">
        <h1>ConneCreにログイン</h1>
    </div>
    <div id="content">
        <form method="POST">
            <div class="form-group">
                <label for="id">ID</label>
                <input type="text" id="id" name="id">
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password">
            </div>
            <input type="submit" name="btn" value="ログイン" class="btn">
        </form>
        <?php
        include "mylib.php";
        session_start();
        $_SESSION['isLogin'] = false;

        if(isset($_POST['id']) && isset($_POST['password'])){
            $result = login($_POST['id'], $_POST['password']);
            if($result){
                $_SESSION['userId'] = $_POST['id'];
                $_SESSION['isLogin'] = true;
                header("Location:index.php");
                exit();
            } else {
                echo "<p class='error-message'>IDとパスワードが正しくありません。</p>";
            }
        }
        ?>
        <div class="register-link">
            <a href="register.php">新規登録</a>
        </div>
    </div>
</body>
</html>