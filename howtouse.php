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
        
        <div class="background-gray">
          <h2>☆質問ページ</h2>
          <p>
          質問を投げることができます。<br>
          ただし回答はYesかNoでしか返ってきません。<br>
          過去に答えた質問を閲覧することもできます。<br>
          </p>
          <img src="./img/userpage.png">
          <a href="./user.php?user_id=sca1l">（参考）sca1lのユーザーページ</a>
        </div>
        
        <div class="background-gray">
          <h2>☆マイページ</h2>
          <p>
          質問が来ていた場合回答することができます。<br>
          ただし回答するときはYesかNoでしか答えることができません。<br>
          </p>
          <img src="./img/mypage.png">
        </div>
        
        <div class="background-gray">
          <h2>☆アカウント作成</h2>
          <p>
          アカウントの作成ができます。<br>
          <a href="./signup.php">アカウント作成ページはこちら！</a><br>
          </p>
          <img src="./img/signuppage.png">
          
        </div>
        
        <div class="background-gray">
          <h2>☆Twitterで回答を共有</h2>
          <p>
          回答した質問をTwitterで共有することができます。<br>
          </p>
          <img src="./img/sharepage.png">
          <img src="./img/tweet.png">
          
        </div>
        
        <div class="background-gray">
          <h2>☆アカウント設定</h2>
          <p>
          プロフィールの変更やユーザーIDの変更、パスワードの変更ができます。<br>
          </p>
          <img src="./img/settingspage.png">
          
        </div>
        
      </section>
      
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>