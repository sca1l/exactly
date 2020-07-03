<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  
  //セッション
  session_start();
  
  
  if( isset($_POST['new_user_name']) && isset($_POST['new_biography']) && isset($_POST['token']) ){
    
    try{
      $new_user_name = $_POST['new_user_name'];
      $new_user_name = htmlspecialchars($new_user_name,ENT_QUOTES,'UTF-8');
      
      $new_biography = $_POST['new_biography'];
      $new_biography = htmlspecialchars($new_biography,ENT_QUOTES,'UTF-8');
      
      //セッションにuser_idがなければリダイレクト
      if(!isset($_SESSION['user_id'])){
        header('Location: ./top.php');
        exit();
      }
      
      $user_id = $_SESSION['user_id'];
      
      /*
      //パスワードが一致するか確認
      $dsn = Database::dsn();
      $user = Database::db_user();
      $password = Database::db_password();
      
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = 'SELECT code,user_id,password,user_name FROM user WHERE user_id = ?';
      $stmt = $dbh->prepare($sql);
      $data[] = $user_id;
      $stmt->execute($data);
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      
      $dbh = null;
      
      if(!password_verify($user_password, $rec['password'])){
        $_SESSION['error_message'] = 'エラー：パスワードが正しくありません。';
        header('Location: ./change_profile.php');
        exit();
      }*/
      if (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        header('Location: ./change_profile.php');
        exit();
        
      }elseif($new_user_name==''){
        $_SESSION['error_message'] = 'エラー：アカウント名が空です。';
        header('Location: ./change_profile.php');
        exit();
        
      }else{
        
        $dsn = 'mysql:dbname=yes_no_exactly;host=localhost;charset=utf8';
        $user = DB::db_user();
        $password = DB::db_password();
        
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = 'UPDATE user SET user_name = ?, biography = ? WHERE user_id = ? ';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $new_user_name;
        $data[] = $new_biography;
        $data[] = $user_id;
        $stmt->execute($data);
        
        $dbh = null;
        
        //セッション情報も更新
        $_SESSION['user_name'] = $new_user_name;
        $_SESSION['biography'] = $new_biography;
        
        $_SESSION['success_message'] = 'プロフィールの変更に成功しました！';
        header('Location: ./change_profile.php');
        exit();
      }
    } catch(Exception $e) {
      print Database::db_errormessage($e);
      exit();
    }
  }
  
  session_regenerate_id(true);
  
?>
