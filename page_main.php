<?php
/* 募集を探す検索フォームが入ってるよ！*/
$html = <<<EOF


    <div class="searchForm">
        <form method="GET" action="index.php">
            <div class="keyword_input_form">
                <label class="keyword">キーワード</label>
                <input type="text" name="searchWord" placeholder="キーワードを入力" class="keyword_input">
            </div>

            <div class="genre_select_form">
                <label class="Genre">役割</label>
                <select name="role" class="select_genre">
                    <option value="-1">指定なし</option>
                    <option value="0">バックエンド</option>
                    <option value="1">フロントエンド</option>
                    <option value="2">フルスタック</option>
                </select>
            </div>

            <div class="searchMethod_select_form">
                <label class="Search_Method">検索方法</label>
                <select name="searchOp" class="select_genre">
                    <option value="0">OR検索</option>
                    <option value="1">AND検索</option>
                </select>
            </div>

            <div class="kensakubtnBox">
                <button type="submit" class="kensaku_btn">検索</button>
            </div>
        </form>
    </div>

    <!-- 募集が入った箱を入れる入れ物 -->
    <!--  <div class="container"> -->
    
    <!-- 募集を入れる箱 -->
    <!-- <div class="Box"> -->
EOF; 
?>