<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  $dsn = Database::dsn();
  $user = Database::db_user();
  $password = Database::db_password();
  
  
  //セッション
  session_start();
  
  //あとでGETで取れるか確認if文を入れる
  //というかリファクタリングしなきゃダメそう、すぱげてー
  
  $user_id = $_GET['user_id'];
  $user_id = htmlspecialchars($user_id,ENT_QUOTES,'UTF-8');
  
  if( isset($_POST['user_code']) && isset($_POST['user_name']) && isset($_POST['user_id']) && isset($_POST['question_text']) && isset($_GET['user_id']) ){
    try{
      $quser_id_post = $_POST['user_id'];
      $quser_id_post = htmlspecialchars($quser_id_post,ENT_QUOTES,'UTF-8');
      $quser_code = $_POST['user_code'];
      $quser_code = htmlspecialchars($quser_code,ENT_QUOTES,'UTF-8');
      $quser_name = $_POST['user_name'];
      $quser_name = htmlspecialchars($quser_name,ENT_QUOTES,'UTF-8');
      $question_text = $_POST['question_text'];
      $question_length = mb_strlen($question_text);
      $question_text = htmlspecialchars($question_text,ENT_QUOTES,'UTF-8');
      
      if (!CsrfValidator::validate(filter_input(INPUT_POST, 'token'))) {
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        //一回リダイレクトすることによってPOSTを全部切る
        header('Location: ./user.php?user_id='.$user_id);
        exit();
        
      }elseif($question_text == ''){
        $_SESSION['error_message'] = 'エラー：質問が空です。';
        //一回リダイレクトすることによってPOSTを全部切る
        header('Location: ./user.php?user_id='.$user_id);
        exit();
        
      }elseif($user_id != $quser_id_post){
        $_SESSION['error_message'] = 'エラー：値が不正です。';
        //一回リダイレクトすることによってPOSTを全部切る
        header('Location: ./user.php?user_id='.$user_id);
        exit();
        
      }elseif($question_length > 40){
        $_SESSION['error_message'] = 'エラー：文字数が40を超えています。';
        //一回リダイレクトすることによってPOSTを全部切る
        header('Location: ./user.php?user_id='.$user_id);
        exit();
        
      }else{
        
        $dbh = new PDO($dsn,$user,$password);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sql = 'INSERT INTO question(user_code, question, question_date, status, answer) VALUES (?,?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $quser_code;
        $data[] = $question_text;
        $data[] = date('Y-m-d H:i:s', time());
        $data[] = 0;//未回答
        $data[] = -1;//未回答
        $stmt->execute($data);
        
        $dbh = null;
        
        $_SESSION['success_message'] = '質問の送信に成功しました！';
        //一回リダイレクトすることによってPOSTを全部切る
        header('Location: ./user.php?user_id='.$user_id);
        exit();
      }
      
    }catch(Exception $e){
      $_SESSION['error_message'] = 'エラー：データベースエラー';
      //一回リダイレクトすることによってPOSTを全部切る
      header('Location: ./user.php?user_id='.$user_id);
      exit();
      
    }
    
  }
  
  session_regenerate_id(true);
  
  try{
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
    $sql = 'SELECT code,oauth_token,oauth_verifier,user_name,user_id,biography FROM user WHERE user_id=?';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = $user_id;
    $stmt->execute($data);
    $dbh = null;
    
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if(isset($rec['code'])){
      $user_code = $rec['code'];
      $user_name = $rec['user_name'];
      $user_id = $rec['user_id'];
      $biography = $rec['biography'];
      
      //答え済みの質問の取得
      
      //$dsn = 'mysql:dbname=yes_no_exactly;host=localhost;charset=utf8';
      //$user = 'root';
      //$password = '';
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      $sql = 'SELECT * FROM user INNER JOIN question ON user.code = question.user_code WHERE user.user_id = ?';
      
      $stmt = $dbh->prepare($sql);
      $data = array();
      $data[] = $user_id;
      $stmt->execute($data);
      
      $dbh = null;
      
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      while($rec){
        //status、[0=>未回答,1=>回答済み,2=>保留]で振り分け
        if($rec['status']==1){
          //回答済みのみ配列へ
          $question_data[] = $rec;
        }
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      }
      
      if(!empty($question_data)){
        //配列のソート
        $sort = array();//一応
        foreach ((array)$question_data as $key => $value) {
          $datetmp = date_create_from_format("Y-m-d H:i:s", $value['answer_date']);
          $unixtime = (int)$datetmp->format('U');
          //逆順にしたいので符号反転
          $unixtime = -$unixtime;
          $sort[$key] = $unixtime;
        }
        array_multisort($sort, SORT_ASC, $question_data);
      }
      //print_r($sort);
      
    }else{
      //これいる？
    }
  }catch(Exception $e){
    print Database::db_errormessage($e);
    exit();
  }
  
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php 
          if(isset($user_name)){
            print $user_name;
          }else{
            print "?";
          }
        ?> さんに質問する - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        <h2>
        <?php 
          if(isset($user_name)){
            print $user_name;
          }else{
            print "？";
          }
        ?> 
        さんに質問する</h2><br>
        
        <?php 
          if(isset($user_name)){
        ?>
        
        <p><?php print $biography;?></p>
        
        <br>
        
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
        <form method="post" action="user.php?user_id=<?php print $user_id?>">
          <input type="hidden" name="user_code" value="<?php print $user_code; ?>">
          <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
          <input type="hidden" name="user_id" value="<?php print $user_id; ?>">
          <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
          
          <textarea name="question_text" placeholder="質問をここに書こう！"></textarea><br>
          <div class="small">頑張って40文字に収めてください。</div>
          <input type="submit" value="送信">
          
          
        </form>
        <br>
        <br>
        
        
        
        <br>
        
        <h2>回答済みの質問</h2>
        
        <?php if(!empty($question_data)){?>
            
            <?php 
              //拡張for文、使い回せるので
              foreach($question_data as $qtmp){
              
            ?>
              <br>
              <div class="question">
                  <div class="question_text"><?php print $qtmp['question']; ?></div>
                  <div class="question_answer">→<img src="./img/answer<?php print $qtmp['answer']; ?>.png"></div>
                  <div class="question_update">answer:<?php print $qtmp['answer_date']; ?></div>
                  
              </div>
              <br>
            <?php }?>
            
        <?php }else{ 
        //質問が来てない時
        ?>
            <p>回答済みの質問はありません。</p><br><br>
        <?php } ?>
        
        
        
        
        <?php 
          }else{
        ?>
        <!-- user_idが存在しないとき -->
        <p>
          ERROR：存在しないユーザーIDです。<br>
          <br>
        </p>
        <?php 
          }
        ?>
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>