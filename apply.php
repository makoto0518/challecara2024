<?php
include "mylib.php";

date_default_timezone_set('Asia/Tokyo');
$currentDateTime = date('Y-m-d H:i:s'); // 現在の時刻を取得
$flag = false;
$message = "";

$recruitNum = isset($_POST['recruitNum']) ? $_POST['recruitNum'] : null;
if (!isset($_SESSION)) {
    session_start();
}

// echo "<pre>POST:" . print_r($_POST, true) . "</pre>";


$debug_mode = false;
if ($debug_mode) {
    echo "<pre>SESSION:" . print_r($_SESSION) . "</pre>";
    echo "<pre>POST:" . print_r($_POST, true) . "</pre>";
}


if ($recruitNum && isset($_POST["applyContent"])) {
    if (strcmp(isAppDuplicate($recruitNum, $_SESSION['userId']), "error") == 0) {
        $message = "クエリ実行エラー";
    } else if (!(isAppDuplicate($recruitNum, $_SESSION['userId']))) {
        $flag = true;
        if (setApplication($recruitNum, $_SESSION['userId'], $_POST['applyContent'])) {
            $message = "応募を投稿しました。";
        } else {
            $message = "応募の投稿に失敗しました。";
        }
    } else {
        $flag = true;
        $message = "この募集への応募は既に行っています。";
    }
}
print (printHeader("ConneCre", "応募フォーム"));
// $creatorTag = ["作曲", "イラスト", "動画制作", "ボーカル"];
$result = getRecruitFromNum($_POST['recruitNum']);

// echo "<pre>result:" . print_r($result, true) . "</pre>";

$html = "";

//2024/10/28 内容の表示はクリア 
for ($i = 0; $i < count($result); $i++) {    // $result(募集内容)の内容を全て表示する
    $row = $result[$i];
    // $html .= "<div style=\"border: 2px #658db449 solid; margin:10px; padding:10px;\">";
    $html .= "<div class=\"confirmRecruitBox\" style=\"max-width:800px;\">";
    // $html .= "<h3 style=\"text-align:center;\">募集内容</h3>";
    $html .= "<table>
                <tr>
                    <th>タイトル</th>
                    <td>" . $row['recruit_title'] . "</td>
                <tr>
                <tr>
                    <th>募集者ID</th>
                    <td>" . $row['recruiter_id'] . "</td>
                </tr>
                <tr>
                    <th>募集役割</th>
                    <td>" . $role[$row['recruit_role']] . "</td>
                <tr>
                <tr>
                    <th>場所</th>
                    <td>" . $location[$row['recruit_location']] . "</td>
                </tr>
                <tr>
                    <th>人数</th>
                    <td>" . $row['recruit_capacity'] . "</td>
                </tr>
                <tr>
                    <th>本文</th>
                    <td>" . $row['recruit_content'] . "</td>
                </tr>
            </table>
            </div>";
}

$html .= "<div class=\"recruitFormBox\">";
if ($flag) {
    $html .= "<h4 style=\"text-align:center;\">" . $message . "</h4>";
} else {
    $html .= "<form  class=\"recruitForm\" method=\"post\">
                <div class=\"formGroup\">
                    <label for=\"1\" style=\"text-align: center;\">メッセージ</label>
                    <input type=\"text\" id=\"1\" name=\"applyContent\" class=\"recruitContent\" rows=\"5\" required>
                    <input type=\"hidden\" name=\"recruitNum\" value=\"" . $recruitNum . "\">
                </div>
                <div class=\"formButtons\">
                    <input type=\"submit\" value=\"送信する\" class=\"apply_btn\">
                </div>";
   $html .= "</form>
         </div>";
}
echo $html;
?>

<!-- 投稿を送信したときの画面動作(応募しました。)が出来ていない -->
<!-- <div class="recruitFormBox">
    <form  class="recruitForm" method="post" >
        <div class="formGroup">
            <label for="1" style="text-align: center;">メッセージ</label>
            <input type="text" id="1" name="applyContent" class="recruitContent" rows="5" required>
            <input type="hidden" name="recruitNum" value="<?php echo htmlspecialchars($recruitNum); ?>">
        </div>
        <div class="formButtons">
            <input type="submit" value="送信する" class="apply_btn">
        </div>
    </form>
</div> -->

</body>

</html>