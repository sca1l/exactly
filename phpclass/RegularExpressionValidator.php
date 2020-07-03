<?php
class RegularExpressionValidator {
    
    const PASSWORD_ERROR = '半角英数字をそれぞれ1種類以上含む8文字以上64文字以下のパスワードでお願いします。';
    
    const USERID_ERROR = '半角英数字とアンダーバーからなる3文字以上15文字以下のユーザーIDでお願いします。';
    
    public static function validatePassword($password){
        //正規表現：半角英数字をそれぞれ1種類以上含む8文字以上64文字以下のパスワード
        return preg_match('/\A(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,64}+\z/i', $password);
    }
    
    public static function validateUserID($user_id){
        //正規表現：半角英数字2文字以上15文字以下のユーザーID
        return preg_match('/\A[a-zA-Z0-9_]{2,15}+\z/i', $user_id);
    }
    

}
?>
