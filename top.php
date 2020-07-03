<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>トップページ</h2>
        
        <p>
          こちら、最強最高ミニマル質問WEBアプリ「そのとおりでございます」のトップページです。<br>
          うほほん<br>
        </p>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>