<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}
print (printHeader("募集内容確認", "募集内容確認"));

$html = "";

if (isset($_POST["recruitTitle"]) && isset($_POST["role"]) && isset($_POST["loc"]) && isset($_POST["capacity"]) && isset($_POST["content"])) {
    $recruitTitle = $_POST["recruitTitle"];
    $recruitRole = $_POST["role"];
    $recruitLocation = $_POST["loc"];
    $recruitCapacity = $_POST["capacity"];
    $recruitContent = $_POST["content"];


    // すでに募集されているときのエラー処理
    if(!empty(getRecruitNum($_SESSION['userId']))){
        $html .= "<div class=\"confirmRecruitBox\" style=\"text-align:center;\">";
        $html .= "<h4>一度に作成できる募集は1つまでです。新しく募集するには、過去の募集を終了してください。</h4>";
        $html .= "</div>";

        echo $html;
        // echo "一度に作成できる募集は1つまでです。新しく募集するには、過去の募集を終了してください。";
        exit();
    }

} else {
    header("Location: recruit.php");
    exit();
}

// echo "<pre>" . print_r($_POST, true) . "</pre>";

?>

    <!-- 10/23 全てのデータをsubmitrecruitに渡せるよう修正 -->
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
    <h2>以上の内容で募集を投稿しますか？</h2>
    <div class="confirmButtons">
        <!-- 修正するボタンのフォーム -->
        <form action="recruit.php" method="post" style="display: inline;">
            <input type="hidden" name="recruitTitle" value="<?php echo htmlspecialchars($recruitTitle); ?>">
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($recruitRole); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($recruitLocation); ?>">
            <input type="hidden" name="capacity" value="<?php echo htmlspecialchars($recruitCapacity); ?>">
            <input type="hidden" name="content" value="<?php echo htmlspecialchars($recruitContent); ?>">
            <input type="submit" value="修正する" class="reset_btn">
        </form>
        <!-- 投稿するボタンのフォーム -->
        <form action="submitRecruit.php" method="post" style="display: inline;">
            <input type="hidden" name="recruitTitle" value="<?php echo htmlspecialchars($recruitTitle); ?>">
            <input type="hidden" name="role" value="<?php echo htmlspecialchars($recruitRole); ?>">
            <input type="hidden" name="location" value="<?php echo htmlspecialchars($recruitLocation); ?>">
            <input type="hidden" name="capacity" value="<?php echo htmlspecialchars($recruitCapacity); ?>">
            <input type="hidden" name="recruitContent" value="<?php echo htmlspecialchars($recruitContent); ?>">
            <input type="submit" value="投稿する" class="apply_btn">
        </form>
    </div>
</div>
<?php  ?>
</body>

</html>