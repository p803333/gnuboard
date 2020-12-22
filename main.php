<?php
    include "Snoopy.class.php";
    $snoopy = new Snoopy;

    
    $domain = 'http://HOMEPAGE.com/'; /*사이트주소*/
    $board_name = 'notice'; /*게시판이름 ex bo_table=?????*/
    $ID = 'admin' /*아이디*/
    $PW = '12345' /*비번*/
    $title = 'hihi'; /*제목*/
    $contents = 'its work' /*내용*/

    
    

    /*로그인 부분*/
    $snoopy->referer = $domain.'bbs/login.php';
    $snoopy->agent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)";
    $snoopy->rawheaders["Pragma"] = "application/x-www-form-urlencoded";
    $submit_url = $domain."bbs/login_check.php";
    $submit_vars["mb_id"] = "$ID";
    $submit_vars["mb_password"] = "PW";
    $snoopy->submit($submit_url,$submit_vars);
    
    
    /*그누보드 5.XX버전부터 write token이 생김*/
    /*write_token.php에 게시판 이름을 post하면 토큰이 나오는데 이부분을 get*/
    $snoopy->fetch($domain."bbs/write.php?bo_table=".$board_name);
    $uri = $domain.'bbs/write_token.php';
    $snoopy->setcookies();
    $snoopy->httpmethod = "post";
    $board['bo_table'] = $board_name;
    $snoopy->submit($uri, $board);
    $gettoken = $snoopy->results;
    
    /*token에 쓸대없는 부분 제거*/
    $settoken = substr($gettoken, 21, 32);
    
    
    /*위에서 받은 토큰, 게시물 정보를 post해서 게시물 등록*/
    $snoopy->referer = $domain."bbs/write.php?bo_table=".$board_name;
    $snoopy->agent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)";
    $snoopy->rawheaders["Pragma"] = "application/x-www-form-urlencoded";
    
    
    $postform = $domain.'bbs/write_update.php';
    $vars = array( 
    "token"=>$settoken, /*토큰*/
    "bo_table"=>$board_name,  /*게시판 이름*/ 
    "wr_id"=>"0", /*건드리지말것*/ 
    "html"=>"html2", /*내용을 html형식으로 올릴경우(ex: G사나 N사의 백링크를 위해) html2 그냥 글자형식으로 올릴거면 html1 */
    "wr_subject"=>$title, /*제목*/
    "wr_content"=>$contents, /*내용*/
    );
    
    $snoopy->submit($postform,$vars); 
    
    echo "finish";
?>
