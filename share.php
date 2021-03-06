<?php
  
  //セッション
  session_start();
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  $exist = false;
  
  if( isset($_GET['user_id']) & isset($_GET['qid']) ){
    try{
      $user_id = $_GET['user_id'];
      $user_id = htmlspecialchars($user_id,ENT_QUOTES,'UTF-8');
      $qid = $_GET['qid'];
      $qid = htmlspecialchars($qid,ENT_QUOTES,'UTF-8');
      
      $dsn = Database::dsn();
      $user = Database::db_user();
      $password = Database::db_password();
      
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      $sql = 'SELECT * FROM user INNER JOIN question ON user.code = question.user_code WHERE user.user_id = ? AND question.code = ?';
      
      $stmt = $dbh->prepare($sql);
      $data[] = $user_id;
      $data[] = $qid;
      $stmt->execute($data);
      
      $dbh = null;
      
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      $exist = !!$rec;
    } catch(Exception $e) {
      print Database::db_errormessage($e);
      exit();
    }
  }
  
  
  session_regenerate_id(true);
  
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>「<?php if($exist){ print $rec['question'];} ?>」に回答しました！ - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
    <?php if($exist){ ?>
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@sca1l" /> 
    <meta property="og:url" content="https://exactly.sca1l.net/share.php?user_id=<?php print $_SESSION['user_id'];?>&qid=<?php print $qid; ?>" /> 
    <meta property="og:title" content="「<?php if($exist){ print $rec['question'];} ?>」に回答しました！ - EXACTLY箱" /> 
    <meta property="og:description" content="YesかNoだけ、EXACTLY箱" /> 
    <meta property="og:image" content="https://exactly.sca1l.net/img/answer<?php print $rec['answer']; ?>.png" />
    <?php } ?>
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        
        <?php if($exist){ ?>
          
          <?php 
            //回答した本人かどうかで表示変更
            if( isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id){ 
          ?>
            <h2>質問に回答しました！</h2><br>
          <?php }else{?>
            <h2><?php print $rec['user_name']; ?>さんが質問に回答しました！</h2><br>
          <?php }?>
          
          <div class="question">
            <div class="question_text">
              <?php print $rec['question']; ?>
            </div>
            <div class="question_answer">
              →<img src="./img/answer<?php print $rec['answer']; ?>.png">
            </div>
            <div class="question_update">
              answer:<?php print $rec['answer_date']; ?>
            </div>
          </div>
          
          <br>
          
          <?php 
            //回答した本人かどうかで表示変更
            if( isset($_SESSION['user_id']) && $_SESSION['user_id'] == $user_id){ 
          ?>
            <h3>内容をツイートする！</h3><br>
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false" data-text="「<?php if($exist){ print $rec['question'];} ?>」に回答しました！ #EXACTLY箱 #YesかNoのみで答える質問サービス" data-url="https://exactly.sca1l.net/share.php?user_id=<?php print $_SESSION['user_id'];?>&qid=<?php print $qid; ?>" data-lang="ja">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script><br>
            <br>
            
            <h3><a href="./mypage.php">マイページに戻る</a></h3>
          <?php }else{?>
            
            <h3><a href="./user.php?user_id=<?php print $user_id; ?>"><?php print $rec['user_name']; ?>さんの質問ページへ</a></h3>
          <?php }?>
          
          <br>
          
        <?php }else{ ?>
          <h2>存在しない質問です。</h2><br>
          <h3><a href="./top.php">トップページに戻る</a></h3>
          <br>
        <?php }?>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>