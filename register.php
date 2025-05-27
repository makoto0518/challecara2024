<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録</title>
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
            max-width: 500px;
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
            width: 180px;
            text-align: right;
            margin-right: 10px;
        }
        .form-group input[type="text"],
        .form-group input[type="password"],
        .form-group select {
            width: 250px;
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
        .login-link {
            margin-top: 15px;
        }
        .login-link a {
            color: #658db4;
            text-decoration: none;
        }
        .message {
            margin-bottom: 15px;
        }
        .error-message {
            color: red;
        }
        .success-message {
            color: green;
        }
    </style>
</head>
<body>
    <div id="title">
        <h1>新規登録</h1>
    </div>
    <div id="content">
        <form method="POST">
            <div class="form-group">
                <label for="id">ID：</label>
                <input type="text" id="id" name="id">
            </div>
            <div class="form-group">
                <label for="password">パスワード：</label>
                <input type="password" id="password" name="password">
            </div>
            <div class="form-group">
                <label for="name">ユーザー名：</label>
                <input type="text" id="name" name="name">
            </div>
            <div class="form-group">
                <label for="introduce">自己紹介文：</label>
                <input type="text" id="introduce" name="introduce">
            </div>
            <div class="form-group">
                <label for="role">役割：</label>
                <select id="role" name="role">
                    <option value="0">バックエンド</option>
                    <option value="1">フロントエンド</option>
                    <option value="2">フルスタック</option>
                </select>
            </div>
            <input type="submit" name="btn" value="登録" class="btn">
        </form>
        
        <?php
        include "mylib.php";

        if(isset($_POST['id']) && isset($_POST['password']) && isset($_POST['name']) && isset($_POST['introduce']) && isset($_POST['role'])){
            $cont = setUser($_POST['id'], $_POST['password'], $_POST['name'], $_POST['role'], $_POST['introduce']);
            if($cont){
                echo "<p class='message success-message'>登録が完了しました。</p>";
            } else if(!$cont){
                echo "<p class='message error-message'>このIDは既に使われています。</p>";
            }
        }
        ?>
        
        <div class="login-link">
            <a href="login.php">ログインする</a>
        </div>
    </div>
</body>
</html>