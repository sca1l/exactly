<?php
  //セッション
  session_start();
  session_regenerate_id(true);
  
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ログイン結果 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        
        <?php 
          if(isset($_SESSION['signin'])){
        ?>
          
          <h2>ログインに成功しました。</h2><br>
          
          <p><?php print $_SESSION['user_name']; ?>さん、おひさ！</p>
          
          <a href="./mypage.php">マイページへ行く</a>
          
        <?php }else{ ?>
          
          <h2>ログインに失敗しました。</h2><br>
          <a href="./signin.php">ログインページに戻る</a>
          
        <?php } ?>
        
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
