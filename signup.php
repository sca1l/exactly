<?php 
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>アカウントの作成 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
    <script type="text/javascript" src="./js/PasswordValidator.js"></script>
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>アカウントの作成</h2><br>
        
        <?php 
          if( isset($_SESSION['error_message']) ){
        ?>
          <div class="error_message">
            <p>
              <?php 
                print $_SESSION['error_message'];
                unset($_SESSION['error_message']);
              ?>
            </p>
          </div>
        <?php } ?>
        
        <br>
        <form method="post" action="signup_check.php">
          <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
          
          ユーザーID<br>
          <input type="text" name="user_id" style="width: 300px"><br>
          <div class="small">半角英数字とアンダーバーからなる2文字以上15文字以下のユーザーIDでお願いします。</div><br>
          
          パスワード<br>
          <input id="password_textbox" type="password" name="password" style="width: 300px"><br>
          <div class="small">半角英数字をそれぞれ1種類以上含む8文字以上64文字以下のパスワードでお願いします。</div><br>
          
          パスワード（再入力）<br>
          <input type="password" name="password2" style="width: 300px"><br>
          <div class="small">確認用です。</div><br>
          
          アカウント名<br>
          <input type="text" name="user_name" style="width: 300px"><br>
          <div class="small">いわゆるニックネームです。</div><br>
          <br>
          <input type="submit" value="アカウント作成">
        </form>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
