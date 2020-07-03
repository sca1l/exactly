<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>アカウント設定 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>アカウント設定</h2><br>
        
        <p>
        <a href="./change_profile.php">プロフィールの変更</a>
        </p>
        
        <p>
        <a href="./change_userid.php">ユーザーIDの変更</a>
        </p>
        
        <p>
        <a href="./change_password.php">パスワードの変更</a>
        </p>
        
        <p>
        <a href="./delete_account.php">アカウントの削除</a>
        </p>
        
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>