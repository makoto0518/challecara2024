<?php
include "mylib.php";
if(!isset($_SESSION)){
    session_start();
}
print(printHeader("募集変更内容確認", "募集変更内容確認"));

// $creatorTag = ["作曲", "イラスト", "動画制作", "ボーカル"];

if(isset($_POST["recruitNum"]) && isset($_POST["recruitTitle"]) && isset($_POST["recruitRole"]) &&  isset($_POST["recruitLocation"]) && isset($_POST["recruitCapacity"]) && isset($_POST["recruitContent"])){
    $recruitNum = $_POST["recruitNum"];
    $recruitTitle = $_POST["recruitTitle"];
    $recruitRole = $_POST["recruitRole"];
    $recruitLocation = $_POST["recruitLocation"];
    $recruitCapacity = $_POST["recruitCapacity"];
    $recruitContent = $_POST["recruitContent"];
} else {
    header("Location: checkRecruit.php");
    exit();
}
?>

<!-- 2024/10/28 データベースに合うよう修正 -->
<div class="confirmRecruitBox">
<table>
        <tr>
            <th>タイトル</th>
            <td><?php echo htmlspecialchars($recruitTitle); ?></td>
        </tr>
        <tr>
            <th>募集役割</th>
            <td><?php echo $role[$recruitRole]; ?></td>
        </tr>
        <tr>
            <th>開催場所</th>
            <td><?php echo $location[$recruitLocation]; ?></td>
        </tr>
        <tr>
            <th>募集人数</th>
            <td><?php echo $recruitCapacity; ?></td>
        </tr>
        <tr>
            <th>募集内容</th>
            <td><?php echo nl2br(htmlspecialchars($recruitContent)); ?></td>
        </tr>
    </table>
    
    <h2>以上の内容で募集を変更しますか？</h2>
    <div class="confirmButtons">
        <!-- 修正するボタンのフォーム -->
        <form action="updateRecruit.php" method="post" style="display: inline;">
            <input type="hidden" name="recruitNum" value="<?php echo htmlspecialchars($recruitNum); ?>">
            <input type="hidden" name="recruitTitle" value="<?php echo htmlspecialchars($recruitTitle); ?>">
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($recruitRole); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($recruitLocation); ?>">
            <input type="hidden" name="recruitCapacity" value="<?php echo htmlspecialchars($recruitCapacity); ?>">
            <input type="hidden" name="recruitContent" value="<?php echo htmlspecialchars($recruitContent); ?>">
            <input type="submit" value="修正する" class="reset_btn">
        </form>
        <!-- 投稿するボタンのフォーム -->
        <form action="submitUpdateRecruit.php" method="post" style="display: inline;">
            <input type="hidden" name="recruitNum" value="<?php echo htmlspecialchars($recruitNum); ?>">
            <input type="hidden" name="recruitTitle" value="<?php echo htmlspecialchars($recruitTitle); ?>">
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($recruitRole); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($recruitLocation); ?>">
            <input type="hidden" name="capacity" value="<?php echo htmlspecialchars($recruitCapacity); ?>">
            <input type="hidden" name="recruitContent" value="<?php echo htmlspecialchars($recruitContent); ?>">
            <input type="submit" value="投稿する" class="apply_btn">
        </form>
</body>
</html>