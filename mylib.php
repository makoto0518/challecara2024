<?php
ini_set("display_errors", "On");
header("Content-type: text/html; charset=utf-8");
include "array.php";
$servername = "localhost";
$username = "root";
$dsn = "mysql:host=localhost;dbname=connecre";
$password = "";
$dbname = "connecre";

date_default_timezone_set('Asia/Tokyo');
$currentDateTime = date('Y-m-d H:i:s'); // 現在の時刻を取得
$creatorTag = ["作曲", "イラスト", "動画制作", "ボーカル"];

// MySQLサーバーに接続
try {
    $pdo = new PDO($dsn, $username, $password);
    // エラーが発生した場合に例外をスローするように設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーの設定
    // echo "データベースに接続しました<br><br>";
} catch (PDOException $e) {
    echo "接続エラー: " . $e->getMessage();
}

// htmlヘッダ、ページメニュー部分を返す
function printHeader($pageName, $menuName)
{

    return "<!DOCTYPE html>
    <html lang=\"ja\">
    <head>
        <meta charset=\"UTF-8\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        <link rel=\"stylesheet\" href=\"page_main.css\">
        <script src=\"page_main.js\"></script>
        <title>" . $pageName . "</title>
    </head>
    <body>
        <header class=\"header1\">
            <ul>
                <li class=\"ConneCre\" style=\"border-right: 1px black solid;\"><a href=\"index.php\">ConneCre</a></li>
                <li class=\"pageName\"> " . $menuName . "</li>
                <li class=\"myProfile\" style=\"border-left: 1px black solid;\"><a href=\"myProfile.php\">プロフィール</a></li>
            </ul>
        </header>

        <header class=\"header2\">
            <ul>
                <li>
                    <a>募集▼</a>                    
                    <ul class=\"others\">
                        <li style=\"border-bottom: 1px black solid; border-top: 1px black solid;\"><a href=\"recruit.php\">作成</a></li>
                        <li style=\"border-bottom: 1px black solid;\"><a href=\"index.php\">探す</a></li>
                    </ul>
                </li>
                <li>
                    <a>ジャムマッチング▼</a>                    
                    <ul class=\"others\">
                        <li style=\"border-bottom: 1px black solid; border-top: 1px black solid; border-left: 1px black solid;\"><a href=\"CreateJam\">開催する</a></li>
                        <li style=\"border-bottom: 1px black solid; border-left: 1px black solid;\"><a href=\"displayJam\">探す</a></li>
                        <li style=\"border-bottom: 1px black solid; border-left: 1px black solid;\"><a href=\"checkJam\">ジャム管理</a></li>
                    </ul>
                </li>
                <li><a href=\"checkApplication.php\">応募確認</a></li>
                <li><a href=\"checkRecruit.php\">募集確認</a></li>
                <li><a href=\"displayChatRoom.php\">チャット</a></li>
                <li>
                    <a>その他▼</a>
                    <ul class=\"others\">
                        <li style=\"border: 1px black solid;\"><a href=\"login.php\">logout</a></li>
                    </ul>
                </li>
            </ul>
        </header>";
}

// 募集を投稿する
function setRecruiting($userID, $recruitTitle, $recruitRole, $recruitLocation, $recruitCapacity, $recruitContent)
{
    global $pdo, $currentDateTime;
    try {

        //2024/10/22  recruit_statusが1になるようにした
        $sql = "INSERT INTO recruit (recruit_datetime, recruit_title, recruiter_id, recruit_role, recruit_location, recruit_capacity,recruit_content, recruit_status, edited) 
            VALUES (:recruitDatetime, :recruitTitle, :recruiterId, :recruitRole, :recruitLocation, :recruitCapacity, :recruitContent, 1, 0)";

        $sth = $pdo->prepare($sql);

        $sth->bindValue(":recruitDatetime", $currentDateTime);
        $sth->bindValue(":recruitTitle", $recruitTitle);
        $sth->bindValue(":recruiterId", $userID);
        $sth->bindValue(":recruitRole", $recruitRole);
        $sth->bindValue(":recruitLocation", $recruitLocation);
        $sth->bindValue(":recruitCapacity", $recruitCapacity);
        $sth->bindValue(":recruitContent", $recruitContent);
        $sth->execute();

        echo "募集をDBに保存しました。";

    } catch (PDOException $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
}

// 応募を投稿する
function setApplication($recruitNum, $applicantId, $applyContent)
{
    global $pdo, $currentDateTime;
    try {
        date_default_timezone_set('Asia/Tokyo');
        $currentDateTime = date('Y-m-d H:i:s'); // 現在の時刻を取得

        $sql = "INSERT INTO apply (recruit_num, apply_datetime, applicant_id, apply_content)
                    VALUES (:recruitNum, :currentDateTime, :applicantId, :applyContent)";

        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recruitNum", $recruitNum);
        $sth->bindValue(":currentDateTime", $currentDateTime);
        $sth->bindValue(":applicantId", $applicantId);
        $sth->bindValue(":applyContent", $applyContent);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// 新規ユーザー登録
function setUser($id, $pw, $name, $role, $profile)
{
    global $pdo;
    try {
        $sql = "INSERT INTO userinfo (user_id, user_pw, user_name, user_role, user_profile)
                    VALUES (:id, :pw, :name, :role, :profile)";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":id", $id);
        $sth->bindValue(":pw", $pw);
        $sth->bindValue(":name", $name);
        $sth->bindValue(":role", $role);
        $sth->bindValue(":profile", $profile);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}

// ユーザーの募集番号を取得する （不要になったよ～）
function getRecruitNum($userId)
{
    global $pdo;
    try {
        $sql = "SELECT recruit_num FROM recruit WHERE recruiter_id = :userId AND recruit_status = 1";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        if (count($result) > 0) {
            $recruitNum = $result[0]['recruit_num'];
            return $recruitNum;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
}

// 募集内容を番号から取得する
function getRecruitFromNum($recruitNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM recruit WHERE recruit_num = :recruitNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recruitNum", $recruitNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
    return null;
}

// 募集内容を取得する (検索機能)
//2024 10/22 $recruitTagを$recruitRoleに変更
function getRecruit($recruitRole, $searchWord, $searchOp)
{    // $searchOp: 0...OR 検索 1...AND検索
    global $pdo;
    $searchOpSub = ["OR", "AND"];

    if ($searchOp != 0 && $searchOp != 1) {   // $searchOpが0か1でないとき、errorを返す
        return "error";
    }

    // 検索ワードをスペースで分割する
    $searchWordReplaced = str_replace('　', ' ', $searchWord);  // 全角スペースを半角スペースに置換する
    $wordArray = array();
    $wordArray = explode(' ', $searchWordReplaced);             // 半角スペースで分割し、wordArray配列に入れる

    // 検索ワードの配列をSQLの形に変形
    $wordNum = count($wordArray);
    $sqlWordTitle = $sqlWordContent = "";

    for ($i = 0; $i < $wordNum; $i++) {
        $sqlWordTitle .= "recruit_title LIKE :title" . $i . ' ' . $searchOpSub[$searchOp] . ' ';
        $sqlWordContent .= "recruit_content LIKE :content" . $i . ' ' . $searchOpSub[$searchOp] . ' ';
    }

    $sqlWordTitle = substr($sqlWordTitle, 0, -4);
    $sqlWordContent = substr($sqlWordContent, 0, -4);

    try {
        // 2024 10/24 募集役割「指定なし」の場合を追加
        if ($recruitRole == -1) {
            $sql = "SELECT * FROM recruit WHERE recruit_status = 1" . " AND (" . $sqlWordTitle . " OR " . $sqlWordContent . ') ORDER BY recruit_num DESC';
            $sth = $pdo->prepare($sql);
            for ($i = 0; $i < $wordNum; $i++) {
                $sth->bindValue(":title{$i}", '%' . $wordArray[$i] . '%');
                $sth->bindValue(":content{$i}", '%' . $wordArray[$i] . '%');
            }
            $sth->execute();
            $result = $sth->fetchALL(PDO::FETCH_ASSOC);       // fetchALL関数で$resultに,クエリを実行して得られたデータを入れる

            if (count($result) < 1) {     // $resultに何もデータが入っていない時
                return null;            // nullを返す
            } else {
                return $result;
            }
        } else {
            //2024 10/22 recruit_status = 1という条件を加えた
            $sql = "SELECT * FROM recruit WHERE recruit_role = :recruitRole AND recruit_status = 1" . " AND (" . $sqlWordTitle . " OR " . $sqlWordContent . ') ORDER BY recruit_num DESC';
            $sth = $pdo->prepare($sql);
            $sth->bindValue(":recruitRole", $recruitRole);
            for ($i = 0; $i < $wordNum; $i++) {
                $sth->bindValue(":title{$i}", '%' . $wordArray[$i] . '%');
                $sth->bindValue(":content{$i}", '%' . $wordArray[$i] . '%');
            }
            $sth->execute();
            $result = $sth->fetchALL(PDO::FETCH_ASSOC);       // fetchALL関数で$resultに,クエリを実行して得られたデータを入れる

            if (count($result) < 1) {     // $resultに何もデータが入っていない時
                return null;            // nullを返す
            } else {
                return $result;
            }
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
}

// ユーザーIDから募集内容を取得
function getRecruitFromUserId($userId, $status)
{ // $status 0:募集終了 1:募集中 2:指定なし
    global $pdo;
    if ($status == 2) {
        try {
            $sql = "SELECT * FROM recruit WHERE recruiter_id = :userId AND recruit_status = :status";
            $sth = $pdo->prepare($sql);
            $sth->bindValue(":userId", $userId);
            $sth->bindValue(":status", $status);
            $sth->execute();

            $result = $sth->fetchALL(PDO::FETCH_ASSOC);

            if (count($result) < 1) {
                return null;
            } else {
                return $result;
            }
        } catch (PDOExcception $e) {
            echo "クエリ実行エラー: " . $e->getMessage();
        }
    }
    try {
        $sql = "SELECT * FROM recruit WHERE recruiter_id = :userId AND recruit_status = {$status}";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();

        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOExcception $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
}

// 最新の募集内容を取得
function getRecentRecruit()
{
    global $pdo;
    try {
        $sql = "SELECT * FROM recruit WHERE recruit_status = 1 ORDER BY recruit_num DESC ;";    // recruit_status...0:募集終了 1:募集中 2:指定なし
        $sth = $pdo->prepare($sql);
        $sth->execute();

        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー: " . $e->getMessage();
    }
}

// 招待URLを取得する
function getInvitation($recruitNum)
{
    global $pdo;
    try {
        $sql = "SELECT invitation_url FROM recruit WHERE recruit_num = :recruitNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recruitNum", $recruitNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result[0]['invitation_url'];
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// 応募状況で応募内容を検索する
function getApplicationByStatus($applicantId, $applyStatus)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM apply WHERE applicant_id = :applicantId AND apply_status = :applyStatus";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":applicantId", $applicantId);
        $sth->bindValue(":applyStatus", $applyStatus);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// 特定の募集に対する応募内容を取得する
function getApplication($recruitNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM apply WHERE recruit_num = :recruitNum AND apply_status = 1";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recruitNum", $recruitNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// 特定のユーザーの応募を取得する
function getUserApplication($userNum)
{
    global $pdo;
    try {
        if (isset($_SESSION['userId'])) {
            $sql = "SELECT * FROM apply WHERE applicant_id = :userId";
            $sth = $pdo->preapre($sql);
            $sth->bindValue(":userId", $_SESSION['userId']);
            $sth->execute();
            $result = $sth->fetchALL(PDO::FETCH_ASSOC);

            if (count($result) < 1) {
                return null;
            } else {
                return $result;
            }
        } else {
            echo "ユーザー情報の取得に失敗しました。";
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// ユーザー情報を取得する
function getUserInfo($userNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM userinfo WHERE num = :userNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userNum", $userNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

//ユーザーidからアイコン情報を得る関数
//2024 10/22 新しいデータベースに合うよう編集
function getIconFromId($userId)
{
    global $pdo;
    try {
        $sql = "SELECT user_icon FROM userinfo WHERE user_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchColumn();
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return "error";
    }

    if ($result) {
        return $result;
    } else {
        return "error";
    }
}

// ユーザーidからユーザー情報を取得する
function getUserInfoFromId($userId)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM userinfo WHERE user_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// ログイン処理
function login($userId, $pw)
{
    if (!isset($_SESSION)) {
        session_start();
    }
    global $pdo;
    try {
        $sql = "SELECT user_id, user_name FROM userinfo WHERE user_id = :userId AND user_pw = :pw";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->bindValue(":pw", $pw);
        $sth->execute();

        $result = $sth->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return false;
        } else {
            $_SESSION['userId'] = $result['user_id'];
            $_SESSION['userName'] = $result['user_name'];
            return true;
        }
    } catch (PDOException $e) {
        return 'error';
    }
}

// 応募を承認/拒否
function acception($ac, $applyNum)
{
    if ($ac) {
        $status = 2;    // 承認
    } else {
        $status = 0;    // 拒否
    }
    global $pdo;

    try {
        $sql = "UPDATE apply SET apply_status = {$status} WHERE apply_num = {$applyNum}";
        $sth = $pdo->prepare($sql);
        $sth->execute();
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// 募集終了する
function endRecruit($recruitNum)
{
    global $pdo;
    try {
        $sql = "UPDATE recruit SET recruit_status = 0 WHERE recruit_num = {$recruitNum}";
        $sth = $pdo->prepare($sql);
        $sth->execute();
    } catch (PDOException $e) {
        echo "クエリ実行エラー：" . $e->getMessage();
    }
}

// 過去作品を追加する
function setHistory($userId, $historyTitle, $historyDisc)
{
    global $pdo;
    try {
        $sql = "INSERT INTO history ( user_id, history_title,  history_discription) 
            VALUES (:userId, :historyTitle, :historyDisc)";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->bindValue(":historyTitle", $historyTitle);
        $sth->bindValue(":historyDisc", $historyDisc);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// 過去作品を取得する
function getHistory($userId)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM history WHERE user_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }
    } catch (PDOException $e) {
        return false;
    }
}

// ユーザー情報を更新する
function updateUserInfo($pw, $name, $role, $profile, $icon, $userId)
{
    global $pdo;
    try {
        $sql = "UPDATE userinfo SET user_pw = :user_pw, user_name = :user_name, user_role = :user_role, user_profile = :user_profile, user_icon = :user_icon
            WHERE user_id = :user_id";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":user_pw", $pw);
        $sth->bindValue(":user_name", $name);
        $sth->bindValue(":user_role", $role);
        $sth->bindValue(":user_profile", $profile);
        $sth->bindValue(":user_icon", $icon);
        $sth->bindValue(":user_id", $userId);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        echo $e->getMessage();
        return false;
    }
}


// 募集情報を更新する
function updateRecruit($recruit_num, $title, $recruitRole, $capacity, $location, $content) {
    global $pdo, $currentDateTime;
    try {
        $sql = "UPDATE recruit SET recruit_title = :title, recruit_role = :role, recruit_capacity = :capacity, recruit_location = :location, recruit_content = :content, recruit_datetime = :recruit_datetime, edited = 1 WHERE recruit_num = :recruit_num";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recruit_datetime", $currentDateTime); // 日時をバインド
        $sth->bindValue(":title", $title);
        $sth->bindValue(":role", $recruitRole);
        $sth->bindValue(":content", $content);
        $sth->bindValue(":capacity", $capacity);
        $sth->bindValue(":location", $location);
        $sth->bindValue(":recruit_num", $recruit_num); // 募集番号をバインド
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

// 応募を削除する
function deleteApplication($apply_num)
{
    global $pdo;
    try {
        $sql = "DELETE FROM apply WHERE apply_num = " . $apply_num;
        $sth = $pdo->prepare($sql);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        return false;
    }
}

//9/26 既に同じ募集に応募してないか確かめる 引数は募集番号(recruit_num)とユーザーID(applicant_id)
//重複してたらtrue
function isAppDuplicate($recruit_num, $applicantId)
{
    global $pdo;
    try {
        //recruit_numとapplicant_idで、その人が応募した案件を特定し、その中から承認待ちもしくは承認済みのものを数え上げる
        $sql = "SELECT COUNT(*) FROM apply WHERE recruit_num = :recruit_num AND applicant_id = :applicant_id 
            AND (apply_status = 1 OR apply_status = 2)";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":applicant_id", $applicantId, PDO::PARAM_INT);
        $sth->bindValue(":recruit_num", $recruit_num, PDO::PARAM_INT);
        $sth->execute();
        $result = $sth->fetchColumn();
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return "error";
    }

    if ($result > 0) {
        return true;
    } else {
        return false;
    }
}

// --------チャット用の関数----------
// 各関数の引数 $table は募集かジャムかを識別するためのもの "recruit"か"jam"が入る。

// メッセージを送る
function sendChat($table, $senderId, $chatContent, $roomNum)
{
    global $pdo, $currentDateTime;
    try {
        // プレースホルダーを使って値をバインド
        $sql = "INSERT INTO " . $table . "_chat (sender_id, chat_datetime, chat_content, room_num) VALUES (:sender_id, :chat_datetime, :chat_content, :room_num)";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":sender_id", $senderId);
        $sth->bindValue(":chat_datetime", $currentDateTime);
        $sth->bindValue(":chat_content", $chatContent);
        $sth->bindValue(":room_num", $roomNum);
        $sth->execute();

        return true;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

// チャットの部屋を作る
function createChatRoom($table, $num)
{
    global $pdo;
    try {
        $sql = "INSERT INTO " . $table . "_chat_room (" . $table . "_num, active) VALUES (:num, " . "1)";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":num", $num);
        $sth->execute();
        return true;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

// チャット内容を取得する
function getChat($table, $roomNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM " . $table . "_chat WHERE room_num = :roomNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":roomNum", $roomNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

// チャットルーム番号を取得する
function getRoomNum( $table, $num ) {
    global $pdo;
    try {
        $sql = "SELECT room_num FROM " . $table . "_chat_room WHERE " . $table . "_num = :num";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":num", $num);
        $sth->execute();
        $result = "";
        if(strcmp($table, "recruit") == 0) {
            $result = $sth->fetchColumn();
        } else {
            $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        }
        return $result;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

//最後のチャット情報を得る
function getLastChatDetail($chatArray)
{    // 引数はgetChat関数の返り値をそのまま入れる
    if (count($chatArray) < 1) {
        return null;
    }

    // $key = array_key_last($chatArray);
    $lastChat = $chatArray[0];
    if (isset($lastChat)) {
        return [
            'senderId' => $lastChat['sender_id'],
            'chatContent' => $lastChat['chat_content'],
            'time' => $lastChat['chat_datetime'],
        ];
    }
    return null;
}

// ジャム番号からメンバーIDを取得する
function getJamMember($roomNum)
{
    global $pdo;
    try {
        $sql = "SELECT member_id FROM jam_member WHERE room_num = :roomNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":roomNum", $roomNum);
        $sth->execute();
        $tmp = $sth->fetchALL(PDO::FETCH_ASSOC);
        $result = [];
        for ($i = 0; $i < count($tmp); $i++) {
            $result[$i] = $tmp[$i]['member_id'];
        }
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

//2024  11/28 ジャムマッチング主催者がジャムマッチングを投稿する
function setJam($hackasonName, $jamLocation, $userId, $maxCapacity, $minCapacity, $endDate, $jamPassword)
{
    global $pdo, $currentDateTime;
    try {
        $sql = "INSERT INTO jam (hackason_name, hackason_location, host_id, team_max, team_min, jam_start_time, jam_period_time, jam_password)
        VALUE (:hackasonName, :jamLocation, :hostId, :max, :min, :startDate, :endDate, :jamPassword)";

        $sth = $pdo->prepare($sql);
        $sth->bindValue(":hackasonName", $hackasonName);
        $sth->bindValue(":jamLocation", $jamLocation);
        $sth->bindValue(":hostId", $userId);
        $sth->bindValue(":max", $maxCapacity);
        $sth->bindValue(":min", $minCapacity);
        $sth->bindValue(":startDate", $currentDateTime);
        $sth->bindValue(":endDate", $endDate);
        $sth->bindValue(":jamPassword", $jamPassword);

        $sth->execute();
        return true;
    } catch (PDOException $e) {
        echo "クエリ実行エラー" . $e->getMessage();
        return false;
    }
}

//2024 11/14 今行われているジャムマッチングを表示
function getActiveJam()
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam WHERE active = 1";
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);

        if (count($result) < 1) {
            return null;
        } else {
            return $result;
        }

    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
    }
}

// 2024 11/28 更新 ジャムに参加する
function applyJam($userId, $jamNum, $role, $password) {
    global $pdo;
    try {
        $sqlpw = "SELECT * FROM jam WHERE jam_num = :jam_num";
        $sthpw = $pdo->prepare($sqlpw);
        $sthpw->bindValue(":jam_num", $jamNum);
        $sthpw->execute();
        $tmp = $sthpw->fetchALL(PDO::FETCH_ASSOC);
        $jamPassword = $tmp[0]['jam_password'];

        if(strcmp($password, $jamPassword) == 0 ) {
            // エントリが存在しない場合は新規挿入
            $sqlInsert = "INSERT INTO jam_apply (jam_num, applicant_id, applicant_role) 
                        VALUES (:jam_num, :applicant_id, :applicant_role)";
            $sthInsert = $pdo->prepare($sqlInsert);
            $sthInsert->bindValue(":jam_num", $jamNum);
            $sthInsert->bindValue(":applicant_id", $userId);
            $sthInsert->bindValue(":applicant_role", $role);
            $sthInsert->execute();
            return true;
        } else {
            return false;
        }
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return "SQL_ERROR";
    }
}

// 2024 11/15 ジャム番号からジャム情報を取得する
function getJam($jamNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam WHERE jam_num = :jam_num";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jam_num", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PCOException $e) {
        echo "エラー：" . $e->getMessage();
    }
}

// 2024 11/15 ジャムを終了する
function endJam($jamNum)
{
    global $pdo;
    try {
        $sql = "UPDATE jam SET active = 0 WHERE jam_num = :jam_num";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jam_num", $jamNum);
        $sth->execute();
        return true;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

// 2024 11/16追加
// ジャムに参加している人のうち、ロール毎のIDを取得する
function getJamApplicantRole($jamNum, $role)
{
    global $pdo;
    try {
        //参加人数カウント
        $sql = "SELECT applicant_id FROM jam_apply WHERE jam_num = :jamNum AND applicant_role = :role";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jamNum", $jamNum);
        $sth->bindValue(":role", $role);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// ジャムに参加している人の人数を取得する
function getJamApplicantAmount($jamNum)
{
    global $pdo;
    try {
        //参加人数カウント
        $sql = "SELECT COUNT(applicant_id) AS applicant_amount FROM jam_apply WHERE jam_num = :jamNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jamNum", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// ジャムのチーム最小人数、最大人数を返す
function getJamMinMax($jamNum)
{
    global $pdo;
    try {
        $sql = "SELECT team_min, team_max FROM jam WHERE jam_num = :jamNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jamNum", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// nチームのジャムのチャット(チームを作る)
function createJamTeam($jamNum, $teamNum)
{
    global $pdo;
    try {
        $sql = "INSERT INTO jam_chat_room(jam_num, active) VALUES";
        for ($i = 0; $i < $teamNum; $i++) {
            $sql .= "(" . $jamNum . ", 1), ";
        }
        $sql = rtrim($sql, ", "); // 末尾の ", " を削除
        $sth = $pdo->prepare($sql);
        $sth->execute();
        $result = true;
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}
// 部屋番号を取得
function getJamRoomNum($jamNum)
{
    global $pdo;
    try {
        $sql = "SELECT room_num FROM jam_chat_room WHERE jam_num = :jamNum ORDER BY room_num ASC";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jamNum", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// n番目の部屋に参加者を割り振る
function assignChatRoom($userId, $roomNum) {
    global $pdo;
    try {
        $sqlInsert = "INSERT INTO jam_member(member_id, room_num) VALUES(:member_id, :room_num)";
        $sthInsert = $pdo->prepare($sqlInsert);
        $sthInsert->bindValue(":member_id", $userId);
        $sthInsert->bindValue(":room_num", $roomNum);
        $sthInsert->execute();
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// 2024 11/22 ユーザーがそのジャムに参加しているかどうかをbool型で返す
function jamApplyExists($userId, $jamNum)
{
    global $pdo;
    try {
        $sql = "SELECT COUNT(*) AS num FROM jam_apply WHERE applicant_id = :applicant_id AND jam_num = :jam_num";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":applicant_id", $userId);
        $sth->bindValue(":jam_num", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        if ($result[0]['num'] == 0) {
            return false;
        } else {
            return true;
        }
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// マッチングの関数
// マッチングの関数
// 2024 11/22 ユーザーがそのジャムに参加しているかどうかをbool型で返す
function jamMatching($jamNum)
{
    $jamNum = (int) $jamNum;
    global $pdo;

    // チームの最小、最大人数を取得
    $team = getJamMinMax($jamNum);
    $min = $team[0]['team_min'];
    $max = $team[0]['team_max'];
    $teamCapa = (int) (($max + $min) / 2);    // チームの人数の目安を（最大+最小）/ 2で定義

    // 総応募者数を取得
    $applicantNumTmp = getJamApplicantAmount($jamNum);
    $applicantNum = $applicantNumTmp[0]['applicant_amount'];

    // 応募者数が足りてない時の処理
    if ($applicantNum < $min) {
        return "APPLICANT_SHORTAGE_ERROR";
    }

    // 総チーム数を計算
    $teamNum = round((float) $applicantNum / (float) $teamCapa);
    if ($teamNum == 0) {
        $teamNum = 1;
    }
    // チーム数の最小を計算
    $teamNumMin = ceil($applicantNum / $max);
    // チーム数の最大を計算
    $teamNumMax = floor($applicantNum / $min);

    // 各役割のユーザIDを取得
    $back = [];
    $front = [];
    $full = [];

    $backTmp = getJamApplicantRole($jamNum, 0);
    foreach ($backTmp as $applicant) {
        $back[] = $applicant['applicant_id'];
    }

    $frontTmp = getJamApplicantRole($jamNum, 1);
    foreach ($frontTmp as $applicant) {
        $front[] = $applicant['applicant_id'];
    }

    $fullTmp = getJamApplicantRole($jamNum, 2);
    foreach ($fullTmp as $applicant) {
        $full[] = $applicant['applicant_id'];
    }

    // 各役割の人数を集計
    $backNum = count($back);
    $frontNum = count($front);
    $fullNum = count($full);

    // var_dump($back);
    // var_dump($front);
    // var_dump($full);

    // var_dump($backNum);
    // var_dump($frontNum);
    // var_dump($fullNum);

    // var_dump($teamNum);

    // フロントエンドの方が少ない場合
    if ($backNum >= $frontNum) {
        if ($frontNum + $fullNum < $teamNum) {   // フロントエンドとフルスタックを足した人数がチーム数よりも少ない場合
            if ($teamNumMin <= $frontNum + $fullNum && $frontNum + $fullNum <= $teamNumMax) {
                $teamNum = $frontNum + $fullNum;      // チーム数をフロントエンドとフルスタックの合計人数に合わせる
            } else if ($frontNum + $fullNum < $teamNumMin) {
                $teamNum = $teamNumMin;         // チーム数の最小値で合わせる
            } else if ($frontNum + $fullNum > $teamNumMax) {
                $teamNum = $teamNumMax;         // チーム数の最大値で合わせる
            }
            createJamTeam($jamNum, $teamNum);   // 上で算出したチーム数のチャットルームを作成する
            $room = getJamRoomNum($jamNum);     // 部屋番号を取得 昇順で並べ替え済み
            $i = 0;

            // 各役割ごとにメンバーをチームに割り振る
            foreach ($front as $tmp) {
                assignChatRoom($tmp, $room[$i]['room_num']);
                if ($i + 1 == $teamNum) {
                    $i = 0;
                } else {
                    $i++;
                }
            }
            foreach ($full as $tmp) {
                assignChatRoom($tmp, $room[$i]['room_num']);
                if ($i + 1 == $teamNum) {
                    $i = 0;
                } else {
                    $i++;
                }
            }
            foreach ($back as $tmp) {
                assignChatRoom($tmp, $room[$i]['room_num']);
                if ($i + 1 == $teamNum) {
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {    // フロントエンドとフルスタックを足した人数がチーム数よりも多い場合
            // フロントエンド+フルスタック、バックエンドの順番でチームを割り振る
            // var_dump($teamNum);
            createJamTeam($jamNum, $teamNum);   // 上で算出したチーム数のチャットルームを作成する
            $room = getJamRoomNum($jamNum);     // 部屋番号を取得 昇順で並べ替え済み
            // var_dump($room);
            $i = 0;

            // 各役割ごとにメンバーをチームに割り振る
            if ($frontNum > 0) {
                foreach ($front as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
            if ($fullNum > 0) {
                foreach ($full as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
            if ($backNum > 0) {
                foreach ($back as $tmp) {
                    // var_dump($room);
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
        }

        // バックエンドの方が少ない場合
    } else if ($frontNum > $backNum) {
        if ($backNum + $fullNum < $teamNum) {   // バックエンドとフルスタックを足した人数がチーム数よりも少ない場合
            if ($teamNumMin <= $backNum + $fullNum && $backNum + $fullNum <= $teamNumMax) {
                $teamNum = $backNum + $fullNum;      // チーム数をフロントエンドとフルスタックの合計人数に合わせる
            } else if ($backNum + $fullNum < $teamNumMin) {
                $teamNum = $teamNumMin;         // チーム数の最小値で合わせる
            } else if ($backNum + $fullNum > $teamNumMax) {
                $teamNum = $teamNumMax;         // チーム数の最大値で合わせる
            }
            createJamTeam($jamNum, $teamNum);   // 上で算出したチーム数のチャットルームを作成する
            $room = getJamRoomNum($jamNum);     // 部屋番号を取得 昇順で並べ替え済み
            $i = 0;

            // 各役割ごとにメンバーをチームに割り振る
            if ($backNum > 0) {
                foreach ($back as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
            foreach ($fullNum as $tmp) {
                assignChatRoom($tmp, $room[$i]['room_num']);
                if ($i + 1 == $teamNum) {
                    $i = 0;
                } else {
                    $i++;
                }
            }
            foreach ($frontNum as $tmp) {
                assignChatRoom($tmp, $room[$i]['room_num']);
                if ($i + 1 == $teamNum) {
                    $i = 0;
                } else {
                    $i++;
                }
            }
        } else {    // フロントエンドとフルスタックを足した人数がチーム数よりも多い場合
            // フロントエンド+フルスタック、バックエンドの順番でチームを割り振る
            createJamTeam($jamNum, $teamNum);   // 上で算出したチーム数のチャットルームを作成する
            $room = getJamRoomNum($jamNum);     // 部屋番号を取得 昇順で並べ替え済み
            // var_dump($room);
            $i = 0;

            // 各役割ごとにメンバーをチームに割り振る
            if ($backNum > 0) {
                foreach ($back as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
            if ($fullNum > 0) {
                foreach ($full as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
            if ($frontNum > 0) {
                foreach ($front as $tmp) {
                    assignChatRoom($tmp, $room[$i]['room_num']);
                    if ($i + 1 == $teamNum) {
                        $i = 0;
                    } else {
                        $i++;
                    }
                }
            }
        }
    }
    return true;
}
// ユーザーの技術力レベルを1~5の指標で入力させて、それをもとにマッチングできるとなお良し

// 2024 11/19 jam_memberテーブルを参照してユーザーIDに対するジャムチーム番号を取得
function getJamRoomNumFromUserId($userId)
{
    global $pdo;
    try {
        $sql = "SELECT room_num FROM jam_member WHERE member_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// 2024 11/19 jam_chat_roomテーブルを参照してジャムルーム番号からジャム番号を取得
function getJamNum($roomNum)
{
    global $pdo;
    try {
        $sql = "SELECT jam_num FROM jam_chat_room WHERE room_num = :roomNum";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":roomNum", $roomNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// 2024 11/21
// ユーザIDから応募しているジャム番号を取得
function getApplyJamNum($userId)
{
    global $pdo;
    try {
        $sql = "SELECT jam_num FROM jam_apply WHERE applicant_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// まだ終了していないジャムの情報を取得
function getActiveJamInfo($jamNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam WHERE jam_num = :jam_num AND active = 1";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":jam_num", $jamNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
    }
}

// 自分がホストかつアクティブ状態のジャムを取得
function getMyJamByStatus($userId, $status)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam WHERE host_id = :userId AND active = :stat";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->bindValue(":stat", $status);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PCOException $e) {
        echo "エラー：" . $e->getMessage();
    }
}

// 時間になったらジャムマッチングを開始する関数
function executeJamMatching($userId)
{
    global $currentDateTime;
    $jamPeriodTime = "";
    $jamNum = null;
    $tmp = getApplyJamNum($userId); // ユーザーID→ジャム番号(jam_applyテーブル)
    if ($tmp != null) {
        $jamNum = $tmp[0]['jam_num'];
        $jamInfo = getActiveJamInfo($jamNum);   // ジャム番号→ジャム終了時間(jamテーブル)
        if ($jamInfo != null) {
            $jamPeriodTime = $jamInfo[0]['jam_period_time'];

            if (strtotime($currentDateTime) >= strtotime($jamPeriodTime)) {  // ジャム終了時間と現在時刻を比較してjamMatching関数を実行
                jamMatching($jamNum);
                endJam($jamNum);
                // echo "マッチングしたよ";
            } else {
                // echo "まだマッチングの時間じゃないよ";
            }
        } else {
            // echo "ジャムに応募してないよ";
        }
    } else {
        // echo "ジャムに応募してないよ";
    }

    // 以下、自分が開催したジャムについても調べる
    $jamInfo = getMyJamByStatus($userId, 1);
    // var_dump($jamInfo);
    if ($jamInfo != null) {
        for ($i = 0; $i < count($jamInfo); $i++) {
            $jamPeriodTime = $jamInfo[$i]['jam_period_time'];
            $jamNum = $jamInfo[$i]['jam_num'];
            if (strtotime($currentDateTime) >= strtotime($jamPeriodTime)) {  // ジャム終了時間と現在時刻を比較してjamMatching関数を実行
                // var_dump($jamNum);
                jamMatching($jamNum);
                endJam($jamNum);
                // echo "マッチングしたよ";
            } else {
                // echo "まだマッチングの時間じゃないよ";
            }
        }
    }
}
// ユーザーが参加している、アクティブ/非アクティブ状態のジャムを取得
function getJamByStatus($userId, $active)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam WHERE jam_num = (SELECT jam_num FROM jam_apply WHERE applicant_id = :userId) AND active = :active";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->bindValue(":active", $active);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー：" . $e->getMessage();
        return false;
    }
}

//特定のジャムを探す
function searchJam($hackasonName, $hackasonLocation)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM jam where (hackason_name = :hackasonName OR hackason_location = :hackasonLocation) AND active = 1";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":hackasonName", $hackasonName);
        $sth->bindValue(":hackasonLocation", $hackasonLocation);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}

// 募集の参加者を取得する
function getRecruitApplicant($recNum)
{
    global $pdo;
    try {
        $sql = "SELECT * FROM apply WHERE recruit_num = :recNum AND apply_status = 2";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":recNum", $recNum);
        $sth->execute();
        $result = $sth->fetchALL(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}


// ユーザーIDから役割を取得する
function getRole($userId)
{
    global $pdo;
    try {
        $sql = "SELECT applicant_role FROM jam_apply WHERE applicant_id = :userId";
        $sth = $pdo->prepare($sql);
        $sth->bindValue(":userId", $userId);
        $sth->execute();
        $tmp = $sth->fetchALL(PDO::FETCH_ASSOC);
        $result = $tmp[0]['applicant_role'];
        return $result;
    } catch (PDOException $e) {
        echo "エラー:" . $e->getMessage();
        return false;
    }
}
?>