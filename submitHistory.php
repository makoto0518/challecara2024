<?php
include "mylib.php";
print (printHeader("ConneCre", "作品を投稿"));
if (!isset($_SESSION)) {
    session_start();
}

?>
<div class="recruitFormBox">
    <form action="confirmSubmitHistory.php" method="post" class="recruitForm">
        <div class="formGroup">
            <label for="1">作品タイトル</label>
            <input id="1" type="text" name="history_title" class="history_title"
            value="<?php echo isset($_POST['history_title']) ? htmlspecialchars($_POST['history_title']) : ''; ?>">
        </div>

        <div class="formGroup">
            <label for="2">作品紹介</label>
            <textarea id="2"  name="history_discription" class="history_discription" cols="50"rows="10"><?php echo isset($_POST['history_discription']) ? htmlspecialchars($_POST['history_discription']) : ''; ?></textarea><br>
        </div>

        <div class="formButtons">
            <input type="submit" value="投稿する" class="btn">
        </div>
    </form>
</div>
</body>

</html>