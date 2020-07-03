<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
  
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ログアウト確認 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        
        <form  method="post" action="./signout.php">
          <h2>本当にログアウトしますか？</h2><br>
          <p>いつでもログインし直せます。</p>
          
          <input type="submit" value="ログアウトする！">
          <br>
        </form>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
