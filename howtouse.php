<?php
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>使い方 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    
    <main>
      <section>
        <h2>使い方</h2><br>
        
        <h3>☆質問ページ</h3>
        <p>
        質問を投げることができます。<br>
        ただし回答はYesかNoでしか返ってきません。<br>
        過去に答えた質問を閲覧することもできます。<br>
        </p>
        <br>
        
        <h3>☆マイページ</h3>
        <p>
        Twitterアカウントでログインするとマイページが見れるようになります。<br>
        質問が来ていた場合回答することができます。<br>
        ただし回答するときはYesかNoでしか答えることができません。<br>
        </p>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>