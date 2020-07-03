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
    <title>ユーザーIDの変更 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>ユーザーIDの変更</h2><br>
        
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
        <?php 
          if( isset($_SESSION['success_message']) ){
        ?>
          <div class="success_message">
            <p>
              <?php 
                print $_SESSION['success_message'];
                unset($_SESSION['success_message']);
              ?>
            </p>
          </div>
        <?php } ?>
        
        <br>
        <form method="post" action="change_userid_check.php">
          <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
          
          新ユーザーID<br>
          <input type="text" name="new_user_id" style="width: 300px"><br>
          <div class="small">希望のユーザーIDを入力してください。</div><br>
          
          パスワード<br>
          <input type="password" name="password" style="width: 300px"><br>
          <div class="small">本人確認用にパスワードをお願いします。</div><br>
          
          <input type="submit" value="送信">
        </form>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
