<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  //セッション
  session_start();
  
  if( isset($_POST['new_user_id']) && isset($_POST['password']) && isset($_POST['token']) ){
    
    try{
      $new_user_id = $_POST['new_user_id'];
      $new_user_id = htmlspecialchars($new_user_id,ENT_QUOTES,'UTF-8');
      
      $user_password = $_POST['password'];
      
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
        header('Location: ./change_userid.php');
        exit();
      }elseif (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        header('Location: ./change_userid.php');
        exit();
        
      }elseif($new_user_id==''){
        $_SESSION['error_message'] = 'エラー：ユーザーIDが空です。';
        header('Location: ./change_userid.php');
        exit();
        
      }else{
        
        //idが重複しないか確認
        $sql = 'SELECT code,user_id,password,user_name FROM user WHERE user_id = ?';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $new_user_id;
        $stmt->execute($data);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($rec){
          $_SESSION['error_message'] = 'エラー：既に使われているユーザーIDです。';
          header('Location: ./change_userid.php');
          exit();
        }
        
        $sql = 'UPDATE user SET user_id = ? WHERE user_id = ? ';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $new_user_id;
        $data[] = $user_id;
        $stmt->execute($data);
        
        $dbh = null;
        
        //セッション内のuser_idも更新
        $_SESSION['user_id'] = $new_user_id;
        
        $_SESSION['success_message'] = 'アカウント名の変更に成功しました！';
        header('Location: ./change_userid.php');
        exit();
      }
    } catch(Exception $e) {
      print Database::db_errormessage($e);
      exit();
    }
  }
  
  session_regenerate_id(true);
  
?>
