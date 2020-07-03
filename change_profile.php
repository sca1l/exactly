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
    <title>プロフィールの変更 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>プロフィールの変更</h2><br>
        
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
        <form method="post" action="change_profile_check.php">
          <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
          
          アカウント名<br>
          <input type="text" name="new_user_name" style="width: 300px" value="<?php print $_SESSION['user_name']; ?>"><br>
          <div class="small">いわゆるニックネームです。</div><br>
          
          自己紹介<br>
          <input type="text" name="new_biography" style="width: 300px" value="<?php print $_SESSION['biography']; ?>"><br>
          <div class="small">質問ページに表示されます。</div><br>
          
          
          <input type="submit" value="送信">
        </form>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>
