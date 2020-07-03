<?php
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  
  //セッション
  session_start();
  session_regenerate_id(true);
  
  try{
    $user_id = $_POST['user_id'];
    $user_id = htmlspecialchars($user_id,ENT_QUOTES,'UTF-8');
    
    $user_password = $_POST['password'];
    
    $dsn = Database::dsn();
    $user = Database::db_user();
    $password = Database::db_password();
    
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = 'SELECT code,user_id,password,user_name,biography FROM user WHERE user_id = ?';
    $stmt = $dbh->prepare($sql);
    $data[] = $user_id;
    $stmt->execute($data);
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $dbh = null;
    
    $user_name = $rec['user_name'];
    $user_code = $rec['code'];
    $biography = $rec['biography'];
    
    if(password_verify($user_password, $rec['password'])){
      //print "ユーザーID : ".$user_id."<br>";
      //print "ユーザー名 : ".$user_name."<br>";
      //print "<br>";
      //print "正常にアカウントを認証しました（？）";
      
      $_SESSION['signin'] = 1;
      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_name'] = $user_name;
      $_SESSION['biography'] = $biography;
      header('Location:signin_result.php');
      exit();
      
    }else{
      //print "認証に失敗しました。";
      
      header('Location:signin_result.php');
      exit();
      
    }
    
    
  } catch(Exception $e) {
    print Database::db_errormessage($e);
    exit();
  }
  
?>

