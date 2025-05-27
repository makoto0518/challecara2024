<?php
include "mylib.php";
print(printHeader("ConneCre", "作品を投稿"));
if (!isset($_SESSION)) {
    session_start();
}

?>
<form action="confirmSubmitWorks.php" method="post">
            作品タイトル<input type="text" name="work_title" class="work_title" value="<?php echo isset($_POST['work_title']) ? htmlspecialchars($_POST['work_title']) : ''; ?>">
            <br>作品URL<input type="text" name="work_url" class="work_url" value="<?php echo isset($_POST['work_url']) ? htmlspecialchars($_POST['work_url']) : ''; ?>">
            <br>作品紹介<br>
            <textarea name="work_discription" class="work_discription" cols="50" rows="10"><?php echo isset($_POST['work_discription']) ? htmlspecialchars($_POST['work_discription']) : ''; ?></textarea><br>
            <input type="submit" value="投稿する" class="btn">
        </form>
    </div>
</body>
</html>