<?php
class Database {
    
    public static function dsn(){
        //ダミー
        return 'mysql:dbname=yes_no_exactly;host=localhost;charset=utf8';
    }
    
    public static function db_user(){
        //ダミー
        return 'root';
    }
    
    public static function db_password(){
        //ダミー
        return '';
    }
    
    public static function db_errormessage($e){
        //必要であればエラーメッセージの表示をする。
        //本番環境はなんか危なそうなのでやめておく。
        
        return 'データベース処理でエラーが発生しました。<br>1分ほどお待ちいただき再度お試しください。<br>';
    }
    
}
?>
