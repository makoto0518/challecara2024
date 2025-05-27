<?php
include "mylib.php";

main();


$debug_mode = false;
if ($debug_mode) {
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
}



function main()
{
    session_start();

    if (empty($_SESSION['isLogin'])) {    // 非ログイン状態である時、ログインページにリダイレクト
        header("Location:login.php");
        exit();
    } else {
        //$htmlに画面情報を入れていく
        $html = printHeader("ConneCre", "募集を探す");  //サイト名、今何の画面か
        $html .= pageMain();  //ここで募集の検索画面情報(page_mainの中身)を代入 

        if (isset($html)) {
            print ($html);
        }
        executeJamMatching($_SESSION['userId']);
    }
}

// メインページの表示 
function pageMain()
{
    include "array.php";
    $debug_mode = false;

    include "page_main.php";

    if ($debug_mode) {
        echo "<pre>POST:" . print_r($_POST, true) . "</pre>";
    }

    //3つの検索欄に情報を入れていた場合
    if (isset($_GET['searchWord']) && isset($_GET['role']) && isset($_GET['searchOp'])) {
        $searchWord = $_GET['searchWord'];
        $recruitRole = $_GET['role'];
        $searchOp = $_GET['searchOp'];
        //検索して取得

        //2024 10/22 $recruitTagを$recruitRoleに変更
        $result = getRecruit($recruitRole, $searchWord, $searchOp);
    } else {

        //最近の募集内容を取得
        //recruitテーブルの全ての要素を取ってくる
        $result = getRecentRecruit();
    }

    if ($debug_mode) {
        echo "<pre>" . print_r($result, true) . "</pre>";
    }

    if ($result == "error") {
        echo "ERROR!";
    }



    //募集がない場合
    if ($result == null) {
        // 2024 11.25 ここをもう少しきれいに表示したい page_main.phpをindex.phpに統合させると作りやすいかも
        $html .= "<div>
                    <h2 style=\"text-align:center; padding-top:20px;\">募集がありません。</h2>
                  </div>";
    } else {
        $icon = "";
        $html .= "<div class=\"container\">";
        $html .= "<div class=\"Box\">";

        for ($i = 0; $i < count($result); $i++) {
            $row = $result[$i];
            $icon = getIconFromId($row['recruiter_id']);
            $modalId = "modal-" . $i;

            // 簡易表示部分
            $html .= "<div class=\"Recruitment\""; /*1つ1つの募集内容を入れる */
            // $html .= "style=\"border: 2px #658db449 solid; margin:10px; padding:10px; background-color: white; border-radius: 10px;\"";
            $html .= ">";

            //アイコンとタイトルを入れる箱を定義
            $html .= "<div class='profile-container'>
                            <a href='" . ($row['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['recruiter_id']) . "'>
                                <img src='" . $icon . "' alt='プロフィール画像' class='profile-icon'>
                            </a>
                            <h3 class='profile-title'>" . $row['recruit_title'] . "</h3>
                        </div>"; //profile-containerの終了


            // $html .= "<h4>" . $row['recruit_title'] . "</h4>";
            // $html .= "<a href='" . ($row['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['recruiter_id']) . "'>
            //     <img src='" . $icon . "' alt='プロフィール画像' style=\"width: 50px; height: 50px; border-radius: 50%;\"></a>";
            // $html .= "Name　　：<a href='" . ($row['recruiter_id'] === $_SESSION['userId'] ? "myProfile.php" : "othersProfile.php?recruiter_id=" . $row['recruiter_id']) . "'>" . $row['recruiter_id'] . "</a><br>";
            $html .= "募集本文：" . $row['recruit_content'] . "<br>";
            $html .= "募集役割：" . $role[$row['recruit_role']] . "<br>";
            $html .= "募集人数:"  . $row['recruit_capacity'] . "<br>";
            $html .= "<div style=\"text-align:center;\"><button onclick=\"openModal('$modalId')\" class=\"apply_btn\">詳細を見る</button></div>";
            $html .= "</div>";  /*Recruitmentの終了 */

            // モーダル部分
            $html .= "
            <div id=\"$modalId\" class=\"modal\">
                <div class=\"modal-content\">
                    <span class=\"close\" onclick=\"closeModal('$modalId')\">&times;</span>
                    <h2>" . $row['recruit_title'] . "</h2>
                    <p>名前　　：" . $row['recruiter_id'] . "</p>
                    <p>日時　　：" . $row['recruit_datetime'] . "</p>
                    <p>募集役割：" . $role[$row['recruit_role']] . "</p>
                    <p>募集人数：" . $row['recruit_capacity'] . "名</p>
                    <p>本文　　：" . $row['recruit_content'] . "</p>
                    <p>場所　　：" . $location[$row['recruit_location']] . "</p>";

                    if ($row['edited'] == 1) {
                        $html .= "<p>(編集済み)</p>";
                    }

                    if (strcmp($row['recruiter_id'], $_SESSION['userId']) !== 0) {
                        $html .= "
                        <form action=\"apply.php\" method=\"post\">
                            <input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">
                            <div style=\"text-align:center;\">
                            <button type=\"submit\" class=\"apply_btn\">応募する</button>
                            </div>
                            </form>";
                    } else {
                        // $html .= "<form action=\"updateRecruit.php\" method=\"post\">";
                        // $html .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">"; //どの募集か一意に特定する
                        // $html .= "<input type=\"hidden\" name=\"recruitTitle\" value=\"" . htmlspecialchars($row['recruit_title']) . "\">"; //募集タイトル
                        // $html .= "<input type=\"hidden\" name=\"recruitRole\" value=\"" . htmlspecialchars($row['recruit_role']) . "\">";   //募集役割
                        // $html .= "<input type=\"hidden\" name=\"recruitCapacity\" value=\"" . htmlspecialchars($row['recruit_capacity']) .  "\">"; //募集人数
                        // $html .= "<input type=\"hidden\" name=\"recruitLocation\" value=\"" . htmlspecialchars($row['recruit_location']) .   "\">";//募集場所 
                        // $html .= "<input type=\"hidden\" name=\"recruitContent\" value=\"" . htmlspecialchars($row['recruit_content']) . "\">";//募集内容
                        // $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"編集する\">";
                        // $html .= "</form>";
                        $html .= "<div class=\"form_group\">";  //ボタン3つを囲み、それらにスタイルを適応させてボタンを横に並べる
        $html .= "<form action=\"applicant.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"応募者確認\">";
        $html .= "</form>";

        $html .= "<form action=\"checkRecruit.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruit_num\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"募集を終了\">";
        $html .= "</form>";

        // 2024/11/13 募集のチャット画面に遷移するための仮ボタン
        $html .= "<form action=\"recruitChat.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">";
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"チャット\">";
        $html .= "</form>";
        
        //2024 10/29 情報が全て表示されるようにした。
        $html .= "<form action=\"updateRecruit.php\" method=\"post\">";
        $html .= "<input type=\"hidden\" name=\"recruitNum\" value=\"" . $row['recruit_num'] . "\">"; //どの募集か一意に特定する
        $html .= "<input type=\"hidden\" name=\"recruitTitle\" value=\"" . htmlspecialchars($row['recruit_title']) . "\">"; //募集タイトル
        $html .= "<input type=\"hidden\" name=\"recruitRole\" value=\"" . htmlspecialchars($row['recruit_role']) . "\">";   //募集役割
        $html .= "<input type=\"hidden\" name=\"recruitCapacity\" value=\"" . htmlspecialchars($row['recruit_capacity']) .  "\">"; //募集人数
        $html .= "<input type=\"hidden\" name=\"recruitLocation\" value=\"" . htmlspecialchars($row['recruit_location']) .   "\">";//募集場所 
        $html .= "<input type=\"hidden\" name=\"recruitContent\" value=\"" . htmlspecialchars($row['recruit_content']) . "\">";//募集内容
        $html .= "<input class=\"apply_btn\" type=\"submit\" value=\"編集\">";
        $html .= "</form>";


                    }

            $html .= "</div>"; //modal-contentを終わる
            $html .= "</div>"; //modalを終わる
        }
        $html .= "</div>"; //Boxを終わる
        $html .= "</div>"; //containerを終わる
    }


    return $html;
}
?>