<?php
include "mylib.php";
if (!isset($_SESSION)) {
    session_start();
}

// echo "<pre>" . print_r($_POST, true) . "</pre>";

print (printHeader("ジャムマッチング確認", "ジャムマッチング確認"));


if (isset($_POST["hackasonName"]) && isset($_POST["jamLocation"]) && isset($_POST["jamLocation"]) && isset($_POST["maxCapacity"]) && isset($_POST["minCapacity"]) && isset($_POST["endDate"]) && isset($_POST["jamPassword"])) {

} else {
    echo "エラー";
}


$flag = false;

?>

<div class="confirmRecruitBox">
    <table>
        <tr>
            <th>
                タイトル
            </th>
            <td>
                <?php echo htmlspecialchars($_POST["hackasonName"]); ?>
                <input type="hidden" name="hackasonName"
                    value="<?php echo htmlspecialchars($_POST["hackasonName"]); ?>">
            </td>
        </tr>

        <tr>
            <th>開催場所</th>
            <td>
                <?php echo $location[$_POST["jamLocation"]]; ?>
                <input type="hidden" name="jamLocation" value="<?php echo htmlspecialchars($_POST["jamLocation"]); ?>">
            </td>
        </tr>

        <tr>
            <th>
                1チームの最少人数
            </th>
            <td>
                <?php echo $_POST["minCapacity"]; ?>
                <input type="hidden" name="minCapacity" value="<?php echo htmlspecialchars($_POST["minCapacity"]); ?>">
            </td>
        </tr>

        <tr>
            <th>
                1チームの最大人数
            </th>
            <td>
                <?php echo $_POST["maxCapacity"]; ?>
                <input type="hidden" name="maxCapacity" value="<?php echo htmlspecialchars($_POST["maxCapacity"]); ?>">
            </td>
        </tr>

        <tr>
            <th>募集終了時間</th>
            <td>
                <?php echo $_POST["endDate"]; ?>
                <input type="hidden" name="endDate" value="<?php echo htmlspecialchars($_POST["endDate"]); ?>">
            </td>
        </tr>

        <tr>
            <th>参加用パスワード</th>
            <td>
                <?php echo $_POST["jamPassword"]; ?>
                <input type="hidden" name="jamPassword" value="<?php echo htmlspecialchars($_POST["jamPassword"]); ?>">
            </td>
        </tr>
    </table>
        <?php
            // 値のバリデーション
            if($_POST["maxCapacity"] - $_POST["minCapacity"] >= 2 && strtotime($_POST['endDate']) - strtotime($currentDateTime) > 0) {
                echo "<h2>以上の内容でジャムマッチングを開始しますか？</h2>";
                $flag = true;
            }
            if($_POST['maxCapacity'] - $_POST['minCapacity'] < 2) {
                echo "<h2>最大人数と最少人数の差は2人以上になるようにしてください。</h2>";
            }
            if(strtotime($_POST['endDate']) - strtotime($currentDateTime) <= 0) {
                echo "<h2>募集終了時間は現在時刻より後にしてください。</h2>";
            }
        ?>
    <div class="confirmButtons">
        <!-- 修正するボタンのフォーム -->
        <form action="createJam.php" method="post" style="display: inline;">
            <input type="hidden" name="hackasonName" value="<?php echo htmlspecialchars($_POST["hackasonName"]); ?>">
            <input type="hidden" name="jamLocation" value="<?php echo htmlspecialchars($_POST["jamLocation"]); ?>">
            <input type="hidden" name="maxCapacity" value="<?php echo htmlspecialchars($_POST["maxCapacity"]); ?>">
            <input type="hidden" name="minCapacity" value="<?php echo htmlspecialchars($_POST["minCapacity"]); ?>">
            <input type="hidden" name="endDate" value="<?php echo htmlspecialchars($_POST["endDate"]); ?>">
            <input type="hidden" name="jamPassword" value="<?php echo htmlspecialchars($_POST["jamPassword"]); ?>">
            <input type="submit" value="修正する" class="reset_btn">
        </form>
        <?php 

            if ($flag) {
                echo "
                <form action=\"submitJam.php\" method=\"post\" style=\"display: inline;\">
                    <input type=\"hidden\" name=\"hackasonName\" value=\"" . $_POST["hackasonName"] . "\">
                    <input type=\"hidden\" name=\"jamLocation\" value=\"" . $_POST["jamLocation"] . "\">
                    <input type=\"hidden\" name=\"maxCapacity\" value=\"" . $_POST["maxCapacity"] .  "\">
                    <input type=\"hidden\" name=\"minCapacity\" value=\"" . $_POST["minCapacity"] .  "\">
                    <input type=\"hidden\" name=\"endDate\" value=\" " . $_POST["endDate"] . "\">
                    <input type=\"hidden\" name=\"jamPassword\" value=\"" . $_POST["jamPassword"] . "\">
                    <input type=\"submit\" value=\"投稿する\" class=\"apply_btn\">
                </form>";
            }
        ?>

    </div>
</div>

</body>

</html>