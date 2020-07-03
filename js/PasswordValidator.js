/*

onloadで読み込む（id=""）
イベントkeyup
正規表現でチェック（メソッド作って投げる）
	パスワードは☆アルファベット含む☆数字含む☆7文字以上
表示位置に追加、あと書き換え


*/


function isOver8Chars(password){
    //8文字以上
    var re = /.{8,}/;
    return re.test(password);
}
function isWithin64Chars(password){
    //64文字以下
    re = /.{0,64}/;
    return re.test(password);
}

function isIncludeAlphabet(password){
    //アルファベットを含む
    re = /.*?[a-z]/i;
    return re.test(password);
}

function isIncludeNumber(password){
    //数字を含む
    re = /.*?[0-9]/;
    return re.test(password);
}


window.onload=function(){
    var password_textbox = document.getElementById("password_textbox");
    
    var lengthMsg = document.createElement('p');
    lengthMsg.className = 'small';
    
    var alphabetMsg = document.createElement('p');
    alphabetMsg.className = 'small';
    
    var numberMsg = document.createElement('p');
    numberMsg.className = 'small';
    
    var br = document.createElement('br');
    
    password_textbox.parentNode.insertBefore(numberMsg, password_textbox.nextElementSibling);
    password_textbox.parentNode.insertBefore(alphabetMsg, password_textbox.nextElementSibling);
    password_textbox.parentNode.insertBefore(lengthMsg, password_textbox.nextElementSibling);
    password_textbox.parentNode.insertBefore(br, password_textbox.nextElementSibling);
    
    password_textbox.addEventListener('keyup', function(){
        //console.log(event.keyCode);
        var password = password_textbox.value;
        if( isOver8Chars(password) && isWithin64Chars(password) ){
            lengthMsg.innerHTML = '<span class="green">パスワードの文字数8文字以上64文字以下 : OK!</span><br>';
            
        }else{
            lengthMsg.innerHTML = '<span class="red">パスワードの文字数8文字以上64文字以下 : NG...</span><br>';
        }
        if( isIncludeAlphabet(password) ){
            alphabetMsg.innerHTML = '<span class="green">アルファベットを一文字文字以上含む : OK!</span><br>';
        }else{
            alphabetMsg.innerHTML = '<span class="red">アルファベットを一文字文字以上含む : NG...</span><br>';
        }
        if( isIncludeNumber(password) ){
            numberMsg.innerHTML = '<span class="green">数字を一文字以上含む : OK!</span><br>';
        }else{
            numberMsg.innerHTML = '<span class="red">数字を一文字以上含む : NG...</span><br>';
        }
    });
    
    password_textbox.addEventListener('focusout', function(){
        lengthMsg.innerHTML = '';
        alphabetMsg.innerHTML = '';
        numberMsg.innerHTML = '';
    });
    
}
