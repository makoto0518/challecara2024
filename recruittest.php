<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>募集画面テスト</title>
</head>

<body>
    <div class="recruit">
        <form action="confirmRecruit.php" method="post">
            <table>
                <tr>
                    <th>タイトル</th>
                    <td><input type="text" name="recruit_title" class="recruit_title"
                            style="width:350px"><?php echo isset($_POST['recruit_title']) ? htmlspecialchars($_POST['recruit_title']) : ''; ?>
                    </td>
                </tr>

                <tr>
                    <th>ジャンル</th>
                    <td>
                        <select name="genre" class="select_genre">
                            <option value="0" <?php echo isset($_POST['genre']) && $_POST['genre'] == '0' ? 'selected' : ''; ?>>音楽制作</option>
                            <option value="1" <?php echo isset($_POST['genre']) && $_POST['genre'] == '1' ? 'selected' : ''; ?>>イラスト</option>
                            <option value="2" <?php echo isset($_POST['genre']) && $_POST['genre'] == '2' ? 'selected' : ''; ?>>動画制作</option>
                            <option value="3" <?php echo isset($_POST['genre']) && $_POST['genre'] == '3' ? 'selected' : ''; ?>>ボーカル</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th>招待URL</th>
                    <td><input type="text" name="invitation_url" class="invitation_url"
                            value="<?php echo isset($_POST['invitation_url']) ? htmlspecialchars($_POST['invitation_url']) : ''; ?>"
                            style="width:350px">
                    </td>
                </tr>

                <tr>
                    <th>募集内容</th>
                    <td><textarea name="recruit_content" class="recruit_content" cols="50"
                            rows="10"><?php echo isset($_POST['recruit_content']) ? htmlspecialchars($_POST['recruit_content']) : ''; ?></textarea>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="reset" value="リセット">
                        <input type="submit" value="確認する">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>

</html>