<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="submitRecruit.php" method="post">
        <table>
            <tr>
                <th>募集タイトル：</th>
                <td>
                    <?php echo htmlspecialchars($recruitTitle); ?>
                    <input type="hidden" name="recruit_title" value="<?php echo htmlspecialchars($recruitTitle); ?>">
                </td>
            </tr>
            <tr>
                <th>募集ジャンル：</th>
                <td>
                    <?php echo $creatorTag[$genre]; ?>
                    <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
                </td>
            </tr>
            <tr>
                <th>招待URL：</th>
                <td>
                    <?php echo htmlspecialchars($invitationUrl); ?>
                    <input type="hidden" name="invitation_url" value="<?php echo htmlspecialchars($invitationUrl); ?>">
                </td>
            </tr>
            <tr>
                <th>募集内容：</th>
                <td>
                    <?php echo nl2br(htmlspecialchars($recruitContent)); ?>
                    <input type="hidden" name="recruit_content"
                        value="<?php echo htmlspecialchars($recruitContent); ?>">
                </td>
            </tr>
        </table>
        <br>以上の内容で投稿しますか？<br>
        <input type="submit" value="投稿する" class="btn">
    </form>

    <form action="recruit.php" method="post">
        <input type="hidden" name="recruit_title" value="<?php echo htmlspecialchars($recruitTitle); ?>">
        <input type="hidden" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
        <input type="hidden" name="invitation_url" value="<?php echo htmlspecialchars($invitationUrl); ?>">
        <input type="hidden" name="recruit_content" value="<?php echo htmlspecialchars($recruitContent); ?>">
        <input type="submit" value="修正する" class="btn">
    </form>

</body>

</html>