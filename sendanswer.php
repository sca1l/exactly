<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  //タイムゾーンの設定
  date_default_timezone_set('Asia/Tokyo');
  
  //セッション
  session_start();
  
  //セッションIDリジェネレート前に実行
  if (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
    header('Content-Type: text/plain; charset=UTF-8', true, 400);
    die('CSRF validation failed.');
    //元々ログインしていた可能性が低いのでトップページへリダイレクトでも良いかもしれん
    
  }elseif( isset($_POST['user_code']) && isset($_POST['user_id']) && isset($_POST['question_code']) && $_POST['question_text'] ){
    try{
      $user_code = $_POST['user_code'];
      $user_code = htmlspecialchars($user_code,ENT_QUOTES,'UTF-8');
      $user_id = $_POST['user_id'];
      $user_id = htmlspecialchars($user_id,ENT_QUOTES,'UTF-8');
      $question_code = $_POST['question_code'];
      $question_code = htmlspecialchars($question_code ,ENT_QUOTES,'UTF-8');
      $question_text = $_POST['question_text'];
      $question_text = htmlspecialchars($question_text ,ENT_QUOTES,'UTF-8');
      
      if( isset($_POST['ans_yes']) || isset($_POST['ans_no']) || isset($_POST['defer']) || isset($_POST['reset']) ){
        
        $dsn = Database::dsn();
        $user = Database::db_user();
        $password = Database::db_password();
        
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        //UPDATEする
        $sql = 'UPDATE question SET status = ?, answer = ?, answer_date = ? WHERE code = ? AND user_code = ? ';
        $stmt = $dbh->prepare($sql);
        if(isset($_POST['ans_yes'])){
          $data[] = 1;//回答済み
          $answer = 1;//Yes
        }elseif(isset($_POST['ans_no'])){
          $data[] = 1;//回答済み
          $answer = 0;//No
        }elseif(isset($_POST['defer'])){
          $data[] = 2;//保留へ
          $answer = -1;//回答データなし
        }elseif(isset($_POST['reset'])){
          $data[] = 0;//未回答へ
          $answer = -1;//回答データなし
        }
        $data[] = $answer;
        $data[] = date('Y-m-d H:i:s', time());
        $data[] = $question_code;
        $data[] = $user_code;
        
        $stmt->execute($data);
        
        $dbh = null;
        
        //memo:YESかNOのときだけshare.phpへ リダイレクト
        //deferのときはmypgae.phpへ リダイレクト
        if( isset($_POST['defer']) || isset($_POST['reset']) ){
          header('Location:mypage.php');
          exit();
        }else{
          header('Location:share.php?user_id='.$user_id.'&qid='.$question_code);
          exit();
        }
        
        
      }elseif( isset($_POST['delete']) ){
        //削除
        $dsn = Database::dsn();
        $user = Database::db_user();
        $password = Database::db_password();
        
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = 'DELETE FROM question WHERE code = ? AND user_code = ? ';
        $stmt = $dbh->prepare($sql);
        
        $data[] = $question_code;
        $data[] = $user_code;
        
        $stmt->execute($data);
        
        $dbh = null;
        
        header('Location:mypage.php');
        exit();
        
      }
      
    }catch(Exception $e){
      print Database::db_errormessage($e);
      exit();
    }
    
  }
  
  
  
  //トークンのチェックが終わってからリジェネレート
  session_regenerate_id(true);
  
  
  
?>
