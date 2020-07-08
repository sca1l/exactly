<?php
  //CSRF対策用クラス
  //[https://qiita.com/mpyw/items/8f8989f8575159ce95fc]
  //@mpyw様 ありがとうございます。
  require(dirname(__FILE__).'/phpclass/CsrfValidator.php');
  
  //DB用
  require(dirname(__FILE__).'/phpclass/Database.php');
  
  session_start();
  session_regenerate_id(true);
  
  if(isset($_SESSION['signin'])){
    
    try{
      //ユーザ宛の質問取り出し
      $user_id = $_SESSION['user_id'];
      
      $dsn = Database::dsn();
      $user = Database::db_user();
      $password = Database::db_password();
      
      $dbh = new PDO($dsn,$user,$password);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
      $sql = 'SELECT * FROM user INNER JOIN question ON user.code = question.user_code WHERE user.user_id = ?';
      
      $stmt = $dbh->prepare($sql);
      $data[] = $user_id;
      $stmt->execute($data);
      
      $dbh = null;
      
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      while($rec){
        //status、[0=>未回答,1=>回答済み,2=>保留]で振り分け
        $question_data[$rec['status']][] = $rec;
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
      }
      
      if(!empty($question_data)){
        //配列のソート
        $question_data_size = count($question_data);
        for($i=0; $i<$question_data_size; $i++){
          if(!empty($question_data[$i])){
            $sort = array();//一応
            foreach ((array)$question_data[$i] as $key => $value) {
              $datetmp = date_create_from_format("Y-m-d H:i:s", $value['answer_date']);
              $unixtime = (int)$datetmp->format('U');
              //逆順にしたいので符号反転
              $unixtime = -$unixtime;
              $sort[$key] = $unixtime;
            }
            array_multisort($sort, SORT_ASC, $question_data[$i]);
          }
        }
      }
      
    }catch(Exception $e){
      print Database::db_errormessage($e);
      exit();
    }
  }
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php print $_SESSION['user_name'] ; ?>さんに来た質問 - EXACTLY箱</title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Muli&display=swap" rel="stylesheet"> 
  </head>
  
  <body>
    <?php include('./global_header.php'); ?>
    
    <main>
      <section>
        
        <?php if(isset($_SESSION['signin'])){ ?>
          <h2><?php print $_SESSION['user_name'] ; ?> さんのマイページ</h2><br>
          
          <br>
          <a href="./user.php?user_id=<?php print $_SESSION['user_id']; ?>">https://exactly.sca1l.net/user.php?user_id=<?php print $_SESSION['user_id']; ?></a><br>
          
          あなたの質問ページです。<br>
          SNS等で共有して質問してもらいましょう。<br>
          
          <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false" data-text="質問受付中です！ #EXACTLY箱 #YesかNoのみで答える質問サービス" data-url="https://exactly.sca1l.net/user.php?user_id=<?php print $_SESSION['user_id'];?>" data-lang="ja">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script><br>
          
          <br>
          
          
          
          <h3>未回答の質問</h3>
          
          <?php if( !empty($question_data[0]) ){?>
            
            <?php 
              //拡張for文、使い回せるので
              foreach($question_data[0] as $qtmp){
            ?>
              <br>
              <form class="ansform_unanswered" method="post" action="./sendanswer.php">
                <input type="hidden" name="user_code" value="<?php print $qtmp['user_code']; ?>">
                <input type="hidden" name="user_id" value="<?php print $qtmp['user_id']; ?>">
                <input type="hidden" name="question_code" value="<?php print $qtmp['code']; ?>">
                <input type="hidden" name="question_text" value="<?php print $qtmp['question']; ?>">
                <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
                
                <div class="question">
                  <div class="question_text"><?php print $qtmp['question']; ?></div>
                  
                  <div class="question_answer">→<img src="./img/answer<?php print $qtmp['answer']; ?>.png"></div>
                  <div class="question_update">question:<?php print $qtmp['question_date']; ?></div>
                  
                  <!-- 回答の更新ボタン、普段は折りたたんでおくとよさそう -->
                  <div class="button_menu">
                    <div class="button_left">
                      <input class="ans_yes" type="submit" name="ans_yes" value="yesで答える">
                      <input class="ans_no"  type="submit" name="ans_no"  value="noで答える">
                    </div>
                    
                    <div class="button_right">
                      <input class="ans_reset"  type="submit" name="reset"  value="未回答にする">
                      <input class="ans_defer"  type="submit" name="defer"  value="保留にする">
                      <input class="ans_delete" type="submit" name="delete" value="削除する">
                    </div>
                  </div>
                  
                </div>
              </form>
              <br>
            <?php }?>
            
          <?php }else{ 
          //質問が来てない時
          ?>
            <p>未回答の質問はありません。</p><br><br>
          <?php } ?>
          
          <br>
          
          <h3>回答済みの質問</h3>
          
          <?php if( !empty($question_data[1]) ){?>
            
            <?php 
              //拡張for文、使い回せるので
              foreach($question_data[1] as $qtmp){
              
            ?>
              <br>
              <form class="ansform_answered"  method="post" action="./sendanswer.php">
                <input type="hidden" name="user_code" value="<?php print $qtmp['user_code']; ?>">
                <input type="hidden" name="user_id" value="<?php print $qtmp['user_id']; ?>">
                <input type="hidden" name="question_code" value="<?php print $qtmp['code']; ?>">
                <input type="hidden" name="question_text" value="<?php print $qtmp['question']; ?>">
                <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
                
                <div class="question">
                  <div class="question_text"><?php print $qtmp['question']; ?></div>
                  
                  <!-- 
                  ステータス：<?php print $question_data[1][$i]['status']; ?><br>
                  質問日付：
                  <h2><?php print $question_data[1][$i]['question_date']; ?></h2><br> -->
                  
                  <div class="question_answer">→<img src="./img/answer<?php print $qtmp['answer']; ?>.png"></div>
                  <div class="question_update">answer:<?php print $qtmp['answer_date']; ?></div>
                  
                  <!-- 回答の更新ボタン、普段は折りたたんでおくとよさそう -->
                  <div class="button_menu">
                    <div class="button_left">
                      <input class="ans_yes" type="submit" name="ans_yes" value="yesで答える">
                      <input class="ans_no"  type="submit" name="ans_no"  value="noで答える">
                    </div>
                    
                    <div class="button_right">
                      <input class="ans_reset"  type="submit" name="reset"  value="未回答にする">
                      <input class="ans_defer"  type="submit" name="defer"  value="保留にする">
                      <input class="ans_delete" type="submit" name="delete" value="削除する">
                    </div>
                  </div>
                  
                </div>
              </form>
              <br>
            <?php }?>
            
          <?php }else{ 
          //質問が来てない時
          ?>
            <p>回答済みの質問はありません。</p><br><br>
          <?php } ?>
          
          <br>
          
          <h3>保留にした質問</h3>
          
          <?php if( !empty($question_data[2]) ){?>
            
            <?php 
              //拡張for文、使い回せるので
              foreach($question_data[2] as $qtmp){
              
            ?>
              <br>
              
              <form class="ansform_deferred" method="post" action="./sendanswer.php">
                <input type="hidden" name="user_code" value="<?php print $qtmp['user_code']; ?>">
                <input type="hidden" name="user_id" value="<?php print $qtmp['user_id']; ?>">
                <input type="hidden" name="question_code" value="<?php print $qtmp['code']; ?>">
                <input type="hidden" name="question_text" value="<?php print $qtmp['question']; ?>">
                <input type="hidden" name="token" value="<?php print CsrfValidator::generate(); ?>">
                
                <div class="question">
                  <div class="question_text"><?php print $qtmp['question']; ?></div>
                  
                  <div class="question_answer">→<img src="./img/answer<?php print $qtmp['answer']; ?>.png"></div>
                  <div class="question_update">question:<?php print $qtmp['question_date']; ?></div>
                  
                  <!-- 回答の更新ボタン、普段は折りたたんでおくとよさそう -->
                  <div class="button_menu">
                    <div class="button_left">
                      <input class="ans_yes" type="submit" name="ans_yes" value="yesで答える">
                      <input class="ans_no"  type="submit" name="ans_no"  value="noで答える">
                    </div>
                    
                    <div class="button_right">
                      <input class="ans_reset"  type="submit" name="reset"  value="未回答にする">
                      <input class="ans_defer"  type="submit" name="defer"  value="保留にする">
                      <input class="ans_delete" type="submit" name="delete" value="削除する">
                    </div>
                  </div>
                  
                </div>
              </form>
              <br>
            <?php }?>
            
          <?php }else{ 
          //質問が来てない時
          ?>
            <p>保留にした質問はありません。</p><br><br>
          <?php } ?>
          
          
          <br>
          
        <?php }else{ ?>
          
          <p>ログインしてください</p>
          
        <?php } ?>
        
      </section>
    </main>
    
    <?php include('./global_footer.php'); ?>
  </body>
</html>