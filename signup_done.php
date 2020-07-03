<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
  if( isset($_SESSION['signin']) ){
    $user_id = $_SESSION['user_id'];
    $user_name = $_SESSION['user_name'];
  }else{
    header('Location: ./signup.php');
    exit();
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>アカウントの作成 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>アカウントを作成しました！</h2><br>
        <br>
        
        <p>
          ようこそ！
        </p>
        
        <p>
          ユーザーID：<?php print $user_id; ?><br>
          アカウント名：<?php print $user_name; ?><br>
        </p>
        
        <br>
        <a href="./mypage.php"><?php print $user_name; ?>さんのマイページへ</a><br>
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
