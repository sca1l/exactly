<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  //正規表現チェック用
  require(dirname(__FILE__).'/phpclass/RegularExpressionValidator.php');
  
  //セッション
  session_start();
  
  
  if( isset($_POST['user_id']) && isset($_POST['password']) && isset($_POST['user_name']) && isset($_POST['token']) ){
    
    
    
    try{
      $user_id = $_POST['user_id'];
      $user_id = htmlspecialchars($user_id,ENT_QUOTES,'UTF-8');
      
      $user_password = $_POST['password'];
      $hash_pass = password_hash($user_password, PASSWORD_DEFAULT);
      
      $user_password2 = $_POST['password2'];
      
      $user_name = $_POST['user_name'];
      $user_name = htmlspecialchars($user_name,ENT_QUOTES,'UTF-8');
      
      
      
      
      //セッションIDリジェネレート前に実行
      if (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        header('Location: ./signup.php');
        exit();
        
      }elseif($user_password != $user_password2){
        $_SESSION['error_message'] = 'エラー：再入力したパスワードが一致しません。';
        header('Location: ./signup.php');
        exit();
        
      }elseif( !RegularExpressionValidator::validatePassword($user_password) ){
        $_SESSION['error_message'] = 'エラー：'.RegularExpressionValidator::PASSWORD_ERROR;
        header('Location: ./signup.php');
        exit();
        
      }elseif( !RegularExpressionValidator::validateUserID($user_id) ){
        $_SESSION['error_message'] = 'エラー：'.RegularExpressionValidator::USERID_ERROR;
        header('Location: ./signup.php');
        exit();
        
      }elseif($user_name==''){
        $_SESSION['error_message'] = 'エラー：アカウント名が空です。';
        header('Location: ./signup.php');
        exit();
        
      }else{
        
        //memo:ここに後で既に使われているIDじゃないかチェックしておく
        
        $dsn = Database::dsn();
        $user = Database::db_user();
        $password = Database::db_password();
      
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'SELECT * FROM user WHERE user_id=?';
        $stmt = $dbh->prepare($sql);
        $data[] = $user_id;
        $stmt->execute($data);
        //$dbh = null;
        
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //idの重複が無い場合のみINSERT
        if(!$rec){
          $biography = "何でも質問してください。";
          
          $dbh = new PDO($dsn,$user,$password);
          $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          $sql = 'INSERT INTO user(user_id, password, site_id, oauth_token ,oauth_verifier, user_name, biography) VALUES (?,?,?,?,?,?,?)';
          $stmt = $dbh->prepare($sql);
          $data = array();
          $data[] = $user_id;
          $data[] = $hash_pass;
          $data[] = 0; //他サイトOAuthなし、0を指定
          $data[] = "";
          $data[] = "";
          $data[] = $user_name;
          $data[] = $biography;
          $stmt->execute($data);
          
          $dbh = null;
          
          //成功,ログイン状態にしておく
          $_SESSION['signin'] = 1;
          $_SESSION['user_id'] = $user_id;
          $_SESSION['user_name'] = $user_name;
          $_SESSION['biography'] = $biography;
          
          header('Location: ./signup_done.php');
          exit();
        }else{
          $_SESSION['error_message'] = 'エラー：既に使われているユーザーIDです。';
          header('Location: ./signup.php');
          exit();
        }
        
      }
    } catch(Exception $e) {
      print Database::db_errormessage($e);
      exit();
    }
  }
  
  session_regenerate_id(true);
  
?>
