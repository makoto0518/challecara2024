<?php
include "mylib.php";
if(!isset($_SESSION)) {
    session_start();
}
print (printHeader("チャット", "チャット"));

$result;
$table = "recruit";
$html = "";

if ( !isset($_POST["recruitNum"]) ) {
    echo "募集情報の取得に失敗しました。";
} else {
    $applicant = [];
    $tmp = getRecruitApplicant($_POST['recruitNum']);
    for($i = 0; $i < count($tmp); $i++) {
        $applicant[$i] = $tmp[$i]['applicant_id'];
    }

    $tmp = getRecruitFromNum($_POST['recruitNum']);
    $recruiter = $tmp[0]['recruiter_id'];

    // 参加者の一覧表示
    // $html = "<div class='applicantContainer'><h3>参加者一覧</h3>";
    // $html .= "<div class='rec'>";
    // $icon = getIconFromId($recruiter);
    // if( strcmp($recruiter, $_SESSION['userId']) == 0) {
    //     $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //     $html .= "<a href='myProfile.php'>" . $_SESSION['userId'] . "</a></div>";
    // } else {
    //     $html .= "<a href='othersProfile.php?recruiter_id=" . $recruiter . "' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //     $html .= "<a href='othersProfile.php?recruiter_id=" . $recruiter . "'>" . $recruiter . "</a></div>";
    // }

    // if($applicant != null) {
    //     for($i = 0; $i < count($applicant); $i++){
    //         $icon = getIconFromId($applicant[$i]);
    //         if( strcmp($applicant[$i], $_SESSION['userId']) == 0) {
    //             $html .= "<div class='app" . $i . "'>";
    //             $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //             $html .= "<a href='myProfile.php'>" . $_SESSION['userId'] . "</a></div>";
    //         } else {
    //             $html .= "<div class='app" . $i . "'>";
    //             $html .= "<a href='othersProfile.php?recruiter_id=" . $applicant[$i] . "' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\"></a>";
    //             $html .= "<a href='othersProfile.php?recruiter_id=" . $applicant[$i] . "'>" . $applicant[$i] . "</a></div>";
    //         }
    //     }
    // }
    // $html .= "</div>";
    $html .= "<div class=\"chatContainer\" id=\"scroll-inner\">"; //メッセージ、入力欄全体を囲む
    $html .= "  <div class=\"chatMessages\">"; //メッセージの塊だけを囲む

    $roomNum = getRoomNum($table, $_POST["recruitNum"]);   // 募集番号からチャットルーム番号を取得

    // チャットの送信処理
    if( isset($_POST["chatContent"]) ) {
        sendChat($table, $_SESSION["userId"], $_POST["chatContent"], $roomNum);
    }

    $result = getChat($table, $roomNum);

    for( $i = count($result)-1; $i >= 0 ; $i-- ) {
        $row = $result[$i];
        $icon = getIconFromId($row['sender_id']);

        if( strcmp($row['sender_id'], $_SESSION['userId']) == 0){    // 自分のチャットであるとき
            $html .= "<div class=\"chatMessage myChatMessage\">"; //1つのチャット(名前、時間、アイコン、送信内容すべてを含む)を入れる箱 これは縦並びを指定
            // $html .= "<div class=\"myMessage\">";
            $html .= "<div class=\"chatSubItem\">"; //ここに、ユーザー名、投稿時間を入れる箱を作る
            $html .= "<a href=\"myProfile.php\">" . $row['sender_id'] . "</a>　　" . $row["chat_datetime"]; //名前、送信時間をセット
            $html .= "</div>"; //chatSubItemを閉じる

            $html .= "<div class=\"IconAndContentBox\">"; //アイコンと送信内容を入れる箱を作る これは横並び
            //プロフィールアイコンをセット
            // $html .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        } else {    // 他人のチャットであるとき
            $html .= "<div class=\"chatMessage\">"; //1つのチャット(名前、時間、アイコン、送信内容すべてを含む)を入れる箱 これは縦並びを指定
            // $html .= "<div class=\"otherMessage\">";
            $html .= "<div class=\"chatSubItem\">"; //ここに、ユーザー名、投稿時間を入れる箱を作る
            $html .= "<a href=\"othersProfile.php?recruiter_id=" . $row['sender_id'] . "\">" . $row['sender_id'] . "</a>　　" . $row["chat_datetime"]; //名前、送信時間をセット
            $html .= "</div>";  //chatSubItemを閉じる

            $html .= "<div class=\"IconAndContentBox\">"; //アイコンと送信内容を入れる箱を作る これは横並び
            //プロフィールアイコンをセット
            $html .= "<a href=\"othersProfile.php?recruiter_id=" . $row['sender_id'] . "\"><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        }

        $html .= "<div class=\"chatContentBox\">" .  $row["chat_content"] . "</div>";
        
        if ( strcmp($row['sender_id'], $_SESSION['userId']) == 0) {
            $html  .= "<a href='myprofile.php' ><img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
        }
        $html .= "         </div>"; //IconAndContentBoxを閉じる
        // $html .= "      </div>"; //my or other Messageを閉じる
        $html .= "     </div>";  //chatMessageを閉じる
    }
        $html .= "    </div>"; //Chatmessagesを閉じる



        $html .= "</div>"; //chatContainerを閉じる

        $html .= "
        <div class=\"fixedForm\">
            <div class=\"fixedFormContentBox\" style=\"margin-right:5px; display:flex;\">
                <div class=\"memberButton\">
                    <button onclick=\"openModal('memberModal')\" class=\"apply_btn\">メンバー</button>
                </div>
            </div>
            <div class=\"fixedFormButtonBox\">
                <div>
                    <form action='recruitChat.php' method='post' >
                        <input type=\"text\" name='chatContent' class=\"fixedTextForm\" placeholder='メッセージを入力' required>
                        <input type='hidden' name='recruitNum' value='" . htmlspecialchars($_POST['recruitNum']) . "'>
                        <button type='submit' class=\"fixedButton\">送信</button>
                    </form>
                </div>
            <div>
        </div>

        <div id=\"memberModal\" class=\"modal\">
            <div class=\"modal-content\">
                <span class=\"close\" onclick=\"closeModal('memberModal')\">&times;</span>
                <h2>メンバー<h2>   
                <div class='modal-members'>";
        
        // 募集者を表示
        $icon = getIconFromId($recruiter);
        $html .= "<div class='modal-member'>
            <a href='" . (strcmp($recruiter, $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $recruiter) . "'>
                <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\">
            </a>
            <a href='" . (strcmp($recruiter, $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $recruiter) . "'>" . $recruiter . "</a>
          </div>";

        // 参加者一覧を表示
        if ($applicant != null) {
            for ($i = 0; $i < count($applicant); $i++) {
                $icon = getIconFromId($applicant[$i]);
                $html .= "<div class='modal-member'>
                            <a href='" . (strcmp($applicant[$i], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $applicant[$i]) . "'>
                            <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 30px; height: 30px; border-radius: 50%;\">
                            </a>
                            <a href='" . (strcmp($applicant[$i], $_SESSION['userId']) == 0 ? "myprofile.php" : "othersProfile.php?recruiter_id=" . $applicant[$i]) . "'>" . $applicant[$i] . "</a>
                         </div>";
            }
        }

        $html .= "
                        </div> <!-- modal-members -->
                </div> <!-- modal-content -->
            </div> <!-- memberModal -->
";

    echo $html;
}
?>