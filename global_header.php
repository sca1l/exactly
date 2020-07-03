    <header>
      <!-- <h1>そのとおりでございます</h1> -->
      <div class="border">
        <nav class="column">
          <div class="title">
            EXACTLY
          </div>
          
          <div class="menu">
          <?php 
            if( isset($_SESSION['signin']) ){
              //ログイン済み
          ?>
            <a href="./mypage.php">マイページ</a>
            <a href="./settings.php">アカウント設定</a>
            <a href="./signout_check.php">ログアウト</a>
          <?php 
            }else{
              //ログインしていない場合
          ?>
            <a href="./howtouse.php">使い方</a>
            <a href="./signup.php">アカウント作成</a>
            <a href="./signin.php">ログイン</a>
          <?php 
            }
          ?>
          </div>
        </nav>
      </div>
    </header>