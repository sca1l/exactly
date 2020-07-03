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
  
  
  if( isset($_POST['new_password']) && isset($_POST['new_password2']) && isset($_POST['password']) && isset($_POST['token']) ){
    
    try{
      $new_password = $_POST['new_password'];
      $new_password2 = $_POST['new_password2'];
      $user_password = $_POST['password'];
      $hash_pass = password_hash($new_password, PASSWORD_DEFAULT);
      
      //セッションにuser_idがなければリダイレクト
      if(!isset($_SESSION['user_id'])){
        header('Location: ./top.php');
        exit();
      }
      
      $user_id = $_SESSION['user_id'];
      
      //パスワードが一致するか確認
      $dsn = Database::dsn();
      $user = Database::db_user();
      $password = Database::db_password();
      
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = 'SELECT code,user_id,password,user_name FROM user WHERE user_id = ?';
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $user_id;
      $stmt->execute($data);
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      
      //$dbh = null;
      
      if(!password_verify($user_password, $rec['password'])){
        $_SESSION['error_message'] = 'エラー：パスワードが正しくありません。';
        header('Location: ./change_password.php');
        exit();
      }elseif (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        header('Location: ./change_password.php');
        exit();
        
      }elseif($new_password!=$new_password2){
        $_SESSION['error_message'] = 'エラー：再入力したパスワードが一致しません。';
        header('Location: ./change_password.php');
        exit();
        
      }elseif( !RegularExpressionValidator::validatePassword($new_password) ){
        $_SESSION['error_message'] = 'エラー：'.RegularExpressionValidator::PASSWORD_ERROR;
        header('Location: ./signup.php');
        exit();
        
      }else{
        
        $sql = 'UPDATE user SET password = ? WHERE user_id = ? ';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $hash_pass;
        $data[] = $user_id;
        $stmt->execute($data);
        
        $dbh = null;
        
        $_SESSION['success_message'] = 'パスワードの変更に成功しました！';
        
        header('Location: ./change_password.php');
        exit();
      }
    } catch(Exception $e) {
      print Database::db_errormessage($e);
      exit();
    }
  }
  
  session_regenerate_id(true);
  
?>
