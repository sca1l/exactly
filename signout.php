<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
  
  //もしログイン済みならログイン情報を消す
  if(isset($_SESSION['signin'])){
    //cookie削除
    if(isset($_COOKIE[session_name()])){
      setcookie(session_name(), '', time()-42000, '/');
    }
    
    $_SESSION['signin'] = null;
    session_destroy();
    
    //ログアウト成功
    $signout_success = true;
    
  }else{
    //まだログインしていないならすでにログアウト済みを表示
    $signout_success = false;
    
  }
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ログアウト - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        
        <?php 
          if($signout_success){
        ?>
          
          <h2>ログアウトに成功しました。</h2><br>
          
          <p>またきてね！</p>
          
        <?php }else{ ?>
          
          <h2>すでにログアウト済みです。</h2><br>
          
          <p><a href="./signin.php">ログインする</a></p>
          
        <?php } ?>
        
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
