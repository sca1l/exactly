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
          YesかNoだけ、質問SNS「EXACTLY箱」のトップページです。
        </p>
        
      </section>
      
      <section class="background-gray">
        <h2>何ができる？使い方は？</h2>
        <p>質問を受け付ける人はアカウントを作成し、質問したい人はユーザーページへアクセスして質問を投げます。<br>
質問が届いた人は質問に対してYesかNoで回答することができます。<br>
また届いた質問を削除したり、届いた質問を保留フォルダに移すこともできます。<br>
質問に回答したあとは、Twitterに回答を共有することもできます。</p>
        <img src="./img/nagare.png">
        
        <p>
        詳しい説明やスクリーンショットは使い方ページに載せています。
        <a href="./howtouse.php">使い方</a>
        </p>
        
      </section>
      
      
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>