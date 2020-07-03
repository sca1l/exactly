<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ログイン - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>ログイン</h2><br>
        
        <br>
        <form method="post" action="signin_check.php">
          ユーザーID<br>
          <input type="text" name="user_id" style="width: 200px"><br>
          パスワード<br>
          <input type="password" name="password" style="width: 200px"><br>
          <br>
          <input type="submit" value="ログイン">
        </form>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
