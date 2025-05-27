<?php
include "mylib.php";
print(printHeader("ConneCre", "応募者一覧"));
if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['applyNum'], $_POST['action'])) {
    $applyNum = $_POST['applyNum'];
    $action = $_POST['action'];
    if ($action == 'accept') {
        acception(true, $applyNum);
    } elseif ($action == 'reject') {
        acception(false, $applyNum);
    }
}

$recruitNum = $_POST['recruit_num'];
$result = getApplication($recruitNum);
$recruit = getRecruitFromNum($_POST['recruit_num']);

// echo "<pre>result:" . print_r($result, true) . "</pre>";
// echo "<pre>recruit:" . print_r($recruit, true) . "</pre>";

$html = "";
if(!empty($recruit)){
    foreach($recruit as $row){
        $html .= "<div class=\"confirmRecruitBox\">
                    <table>
                        <tr>
                            <th>タイトル</th>
                            <td>" . $row['recruit_title'] . "</td>
                        </tr>
                        <tr>
                            <th>投稿時間</th>
                            <td>" . $row['recruit_datetime'] . "</td>
                        </tr>
                        <tr>
                            <th>募集者</th>
                            <td>" . $row['recruiter_id'] . "</td>
                        </tr>
                        <tr>
                            <th>募集役割</th>
                            <td>" . $role[$row['recruit_role']] . "</td>
                        </tr>
                        <tr>
                            <th>場所</th>
                            <td>" . $location[$row['recruit_location']] . "</td>
                        </tr>
                        <tr>
                            <th>募集人数</th>
                            <td>" . $row['recruit_capacity'] . "人</td>
                        </tr>
                        <tr>
                            <th>本文</th>
                            <td>" . $row['recruit_content'] . "</td>
                        </tr>
                    </table>
                  </div>";
    }
}


if ($result == null) {
    $html .=  "<h3 style=\"text-align:center;\">この募集への新規応募がありません。</h3>";
} else {
    $html .= "<div class=\"container\">";
    $html .= "  <div class=\"Box\">";  //募集者を2列に並べる
    for ($i = 0; $i < count($result); $i++) {
        $row = $result[$i];
        $icon = getIconFromId($row['applicant_id']);
        $html .= "<div class=\"Recruitment\">";
                        //アイコンとタイトルを入れる箱を定義
        $html .= "<div class='profile-container'>
                        <a href='" . ($row['applicant_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['applicant_id']) . "'>
                            <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                        </a>
                        <h3 class='profile-title'>" . $row['applicant_id'] . "</h3>
                      </div>"; //profile-containerの終了
        $html .=  "メッセージ：" . htmlspecialchars($row['apply_content']) . "<br>";
        $html .=  "応募時間  ：" . htmlspecialchars($row['apply_datetime']) . "<br>";
        $html .=  "<form action=\"\" method=\"post\" style=\"display:inline;\">";
        $html .=  "<input type=\"hidden\" name=\"applyNum\" value=\"" . htmlspecialchars($row['apply_num']) . "\">";
        $html .=  "<input type=\"hidden\" name=\"recruit_num\" value=\"" . htmlspecialchars($_POST['recruit_num']) . "\">";
        $html .= "<div style=\"text-align:center;\">";
        $html .=  "<button type=\"submit\" name=\"action\" value=\"accept\" class=\"apply_btn\" style=\"margin: 5px; margin-right:20px;\">承認</button>";
        $html .=  "<button type=\"submit\" name=\"action\" value=\"reject\" class=\"apply_btn\" style=\"margin: 5px;\">拒否</button>";
        $html .=  "</form>";
        $html .= "</div>";
        $html .=  "</div>";
    }
    $html .= "  </div>
            </div>";
}


echo $html;
?>
</body>
</html>