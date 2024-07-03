error_reporting(0); 
session_start();
echo "\e[38;5;208m ------- Support VIPIG -------\e[38;5;208m \n";
$xuong = "\n";
 $do="\033[1;91m";
 $maufulldo= "\e[1;47;31m"; 
$maunenhong= "\033[1;41;33m"; 
$white= "\033[1;37m";
$red="\033[1;31m";
$pink="\e[1;35m"; 
$green="\e[1;32m"; 
$yellow="\e[1;33m";
$white= "\033[0;37m"; 
$cyan= "\e[1;36m"; 
$blue="\e[1;34m"; 
$cam= "\e[38;5;208m";
$TIME='date "+%H:%M"';date_default_timezone_set("Asia/Ho_Chi_Minh");
$useragent="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36";
$_SESSION['fck'] = file_exists("VIPIG.txt");
if ($_SESSION['fck'] == '1'){
  unlink("VIPIG.txt");
}
$_SESSION['check'] = file_exists("logVIPIG.txt");
$_SESSION['checklistcc'] = file_exists("ListccVIPIG.txt");
$nhaplaicc = false;
if ($_SESSION['check'] =='1'){
  luachon:
  echo "\n";
echo$white." Nháº­p$cam Enter$white Ä‘á»ƒ vÃ o tool! $xuong Nháº­p$red No$white Ä‘á»ƒ Ä‘Äƒng nháº­p láº¡i VIPIG : ";
$_SESSION['nhap'] = trim(fgets(STDIN));
if ($_SESSION['nhap'] !='no' and $_SESSION['nhap'] != 'No' and $_SESSION['nhap'] !=''){
echo $red."Sai Äá»‹nh Dáº¡ng\n";
GOTO luachon;
}
if ($_SESSION['nhap'] =='no' or $_SESSION['nhap'] =='No'){
$my = fopen("logVIPIG.txt", "w+");
echo $white." â© ".$green."TÃ i khoáº£n VIPIG: ";
$username =trim(fgets(STDIN));
echo $white." â© ".$green."Máº­t Kháº©u VIPIG : ";
$password =trim(fgets(STDIN));
$arr = array("username"=> $username, "password"=> $password);
fwrite($my,json_encode($arr));
    $my = file("logVIPIG.txt");
$bb = file_get_contents('logVIPIG.txt');
$cc =json_decode($bb);
$_SESSION['username']= $cc->{"username"};
$_SESSION['password']= $cc->{"password"};
}
if ($_SESSION['nhap'] == ''){
$bb = file_get_contents("logVIPIG.txt");
$cc =json_decode($bb);
$_SESSION['username']= $cc->{"username"};
$_SESSION['password']= $cc->{"password"};
}
} else {
  login:
$my = fopen("logVIPIG.txt","w+");
echo "\n";
echo $white." â© ".$green."TÃ i khoáº£n VIPIG : ";
$_SESSION["username"]=trim(fgets(STDIN));
echo $white." â© ".$green."Máº­t Kháº©u VIPIG : ";
$_SESSION['password']=trim(fgets(STDIN));
$arr = array("username"=> $_SESSION["username"], "password"=> $_SESSION['password']);
fwrite($my,json_encode($arr));
}
$user = $_SESSION['username'];
$pass = $_SESSION['password'];
$source = getcookieVIPIG($user,$pass,$useragent);
$sou= strlen($source);
if ($sou < 10 ){
  echo $white." âœ… ".$green."ÄÄƒng nháº­p VIPIG thÃ nh cÃ´ng\n";
  // get xu
  $url = "https://vipig.net/home.php";
  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_PORT => "443",
  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_SSL_VERIFYPEER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_COOKIEFILE => "VIPIG.txt"
  ));
  $data = curl_exec($curl);
  curl_close($curl);
  preg_match('#id="soduchinh">(.+?)<#is', $data, $sd);
  $xu = $sd["1"];
  $_SESSION['xu'] = $xu;
}else{
	echo $red." âŒ ".$red."Sai Username hoáº·c Password\n";
	 GOTO login;
}

  choosetr:
  echo "\n";
if($_SESSION['checklistcc'] =='1'){
  echo$white." Nháº­p$cam Enter$white Ä‘á»ƒ dÃ¹ng list cookies Ä‘Ã£ lÆ°u! $xuong Nháº­p$red 1$white Ä‘á»ƒ nháº­p láº¡i list cookie : ";
$_SESSION['nhapcc'] = trim(fgets(STDIN));
if ($_SESSION['nhapcc'] !='1' and $_SESSION['nhapcc'] !=''){
echo $red."Sai lá»±a chá»n\n";
GOTO choosetr;
}else if($_SESSION['nhapcc'] ==''){
	$mangcookie =[];
	$listccdaluu = explode("\n",file_get_contents("ListccVIPIG.txt"));
	for($i=0;$i<count($listccdaluu); $i++){
		$access = cookie($listccdaluu[$i],$useragent);
		
		$text = '{"has_phone_number"'.explode('"has_phone_number"',$access)[1];
		
		preg_match('/\{(?:[^{}]|(?R))*\}/', $text, $matches, PREG_OFFSET_CAPTURE);
		$configdata = json_decode($matches[0][0],true);
		
		// $configdata = json_decode(trim(explode('}}',explode('["XIGSharedData",[],', $access)[1])[0].'}}'),true);
		if ($configdata !== null && $configdata['username'] !== null)
		{
				array_push($mangcookie,$listccdaluu[$i]);

		}
		
		//$configdata = json_decode(trim(explode('}}',explode('["XIGSharedData",[],', $access)[1])[0].'}}'),true);
		//if ($configdata !== null && $configdata['raw'] !== null)
		//{
				array_push($mangcookie,$listccdaluu[$i]);

		//}
	}
	$luong = count($mangcookie);
}else if($_SESSION['nhapcc'] =='1'){
	$nhaplaicc = true;
}
}else{
	$nhaplaicc = true;
}
if($nhaplaicc == true){
unlink("ListccVIPIG.txt");
choose:
echo $white." âœ ".$blue."Nháº­p sá»‘ nÃ­ch INSTA muá»‘n cháº¡y: ";
$luong=trim(fgets(STDIN));
if ($luong<1 || $luong >2000){
  echo $red."\033[1;37m~\033[1;31m Ãt nháº¥t lÃ  1 vÃ  nhiá»u nháº¥t lÃ  2000 mÃ¡ Æ°iii !!!\n";
  GOTO choose;
  }
$c=1;$thu=1;
$mangcookie =[];
for($b=1;$b<=$luong;$b++){
    echo $white." + ".$green."Nháº­p Cookie Thá»© ".$thu.":$white ";
    $cooki[$c]=trim(fgets(STDIN));
    // $ch=curl_init();
    $cookie=$cooki[$c];
$access = cookie($cookie,$useragent);

$text = '{"has_phone_number"'.explode('"has_phone_number"',$access)[1];
		
		preg_match('/\{(?:[^{}]|(?R))*\}/', $text, $matches, PREG_OFFSET_CAPTURE);
		$configdata = json_decode($matches[0][0],true);
		
		// $configdata = json_decode(trim(explode('}}',explode('["XIGSharedData",[],', $access)[1])[0].'}}'),true);
		if ($configdata !== null && $configdata['username'] !== null)
		{
        array_push($mangcookie,$cookie);
		file_put_contents("ListccVIPIG.txt",$cookie."\n",FILE_APPEND);
	             $c++;
	            $thu++;
}else{echo $white." â›” ".$red."Cookie die rá»“i, thá»­ láº¡i Ä‘i \n";$b--;}
}
}

if($luong==1){
  echo $white." â© ".$blue."Háº¿t nhiá»‡m vá»¥ hoáº·c lá»—i thÃ¬ dá»«ng bao lÃ¢u? : $white ";
  $dl=trim(fgets(STDIN));
  $doi = 99999;
}else{
	nhiemvu:
$dl = 150;
echo $white." â© ".$blue."Sau bao nhiÃªu nhiá»‡m vá»¥ thÃ¬ Ä‘á»•i cáº¥u hÃ¬nh : $white ";
$doi=trim(fgets(STDIN));
if ($doi<1){
  echo $red."\033[1;37m~\033[1;31m Lá»±a chá»n khÃ´ng há»£p lá»‡ !\n";
  GOTO nhiemvu;
}
}



$listnv = [];
  Job:
  echo $yellow." â© ".$blue."Cháº¿ Ä‘á»™ Follow$pink (on/off): $white";
  $chon1=trim(fgets(STDIN));
  if ($chon1 == 'on' or $chon1 == 'On' or $chon1 == 'ON'){
    array_push($listnv,'sub');
	Time_nv:
  echo $yellow." â© ".$blue."Delay Nhiá»‡m Vá»¥ Follow (lá»›n hÆ¡n 20): $white";
  $timedelay=trim(fgets(STDIN));
  if($timedelay < 20)
    {
      echo $red."\033[1;37m~\033[1;31m Delay Follow khÃ´ng há»£p lá»‡, tá»‘i thiá»ƒu 20 !\n";
      GOTO Time_nv;
    }
  }
  echo $yellow." â© ".$blue."Cháº¿ Ä‘á»™ Tym$pink (on/off): $white";
  $chon1=trim(fgets(STDIN));
  if ($chon1 == 'on' or $chon1 == 'On' or $chon1 == 'ON'){
    array_push($listnv,'tym');
	Time_nvtym:
	echo $yellow." â© ".$blue."Delay Nhiá»‡m Vá»¥ Tym (lá»›n hÆ¡n 10): $white";
  $timedelaytym=trim(fgets(STDIN));
  
	if($timedelaytym < 10)
    {
      echo $red."\033[1;37m~\033[1;31m Delay Tym khÃ´ng há»£p lá»‡, tá»‘i thiá»ƒu 10 !\n";
      GOTO Time_nvtym;
    }
  }
  if (count($listnv) == 0){
    echo $red."Chá»n tá»‘i thiá»ƒu 1 loáº¡i Job !\n";
    GOTO Job;
  }
  
  
usleep(200000);

echo $white."             \n";
for($v=0;$v<= 12;$v++){
    echo "\033[1;37m- ";usleep(15000);
    echo "\033[1;33m- ";usleep(15000);
}
echo "\033[1;37m- ";usleep(15000);
echo "\033[1;33m-";usleep(15000);
echo"\n";
echo $cyan." âœ… ".$cam."Username: $white".$user."\n";
echo $cyan." âœ… ".$cam."Account number :$white $luong\n";
echo $cyan." âœ… ".$cam."Coin :$white $xu\n";
echo $cyan." âœ… ".$cam."ID tool :$white T001\n";
echo $cyan." âœ… ".$cam."Verison :$white V.2\n";
echo $cyan." âœ… ".$cam."Follow 6 láº§n sáº½ nháº­n xu Táº¤T Cáº¢ 1 lÆ°á»£t\n";
for($v=0;$v<= 12;$v++){
    echo "\033[1;37m- ";usleep(15000);
    echo "\033[1;33m- ";usleep(15000);
}
echo "\033[1;37m- ";usleep(15000);
echo "\033[1;33m-";usleep(15000);
echo"\n";
$q=1;
while (0<1){
for($l=0;$l<count($mangcookie);$l++){
$cookie = $mangcookie[$l];
$access = cookie($cookie,$useragent);
$text = '{"has_phone_number"'.explode('"has_phone_number"',$access)[1];
		
		preg_match('/\{(?:[^{}]|(?R))*\}/', $text, $matches, PREG_OFFSET_CAPTURE);
		$configdata = json_decode($matches[0][0],true);
		
		// $configdata = json_decode(trim(explode('}}',explode('["XIGSharedData",[],', $access)[1])[0].'}}'),true);
		if ($configdata !== null && $configdata['username'] !== null)
		{
	// $dataa = json_decode($configdata['raw'],true);
  // $idfb =  $dataa['config']['viewerId'];
  // $tenfb =  $dataa['config']['viewer']['username'];
  
  $idfb =  $configdata['id'];
  $tenfb =  $configdata['username'];
  $h = datnick($idfb,$useragent);
}else{
  echo "                                     \r";
  echo $white." â›” ".$red."Cookie Die - ÄANG Äá»”I NICK\n";
  array_splice($mangcookie,$l,1);
}

if ($h == '1'){
echo "                                                    \r";
echo $white." Supports VIPIG - ".$green."Account: $cam".$tenfb."\n";
        $i=1;
        $max=0;
 $rand = $listnv[array_rand($listnv,1)];

  if ($rand == 'sub'){
    $loai = 'sub';
    $list = getnv($loai,$useragent);
    $check = count($list);
    if($check <5){
      echo "                                                      \r";
     echo $white." âŒ ".$red."Ko Ä‘á»§ 5 Nhiá»‡m Vá»¥ Follow\r";
	 if(count($mangcookie)==1){
          echo "                                                      \r";
           for($j = $dl;$j> 0;$j--){
             echo $green."Äang Chá» Delay TrÃ¡nh Block$yellow $j GiÃ¢y";
             sleep(1);
             echo "\r";
           }
        }
    }else{
	$churk_list = array_chunk($list,6);
	if(count($churk_list[count($churk_list) - 1]) <5){
	$rmd = array_pop($churk_list);
	}
	foreach ($churk_list  as $listid) {
	$idnhanxu ='';
	$coloigiuachung = false;
    foreach ($listid  as $id) {

    $id = $id[("soID")];
	$idnhanxu .= $id.",";
	$csf = explode(';',explode('csrftoken=', $cookie)[1])[0];
	$chayfl = follow($id,$cookie,$csf);
	$max=$max+1;
    $g = json_decode($chayfl,true);
    if($g == null || $g['status'] !== 'ok'){
	echo "\r";
    echo "                                              \r";
	echo $red." â—$red FOLLOW Lá»–I$red â— $white";	
	echo "\n";
	
	$ck = hoanthanhsub(rtrim($idnhanxu,","));
	if($ck !== null){
		if(isset($ck['mess'])){
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $green." â© ".$green.$ck['mess']."\n";
		}else if(isset($ck['error'])){
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $red." â© ".$red.$ck['error']."\n";
		}
	}
	$xu = getxu();
      echo "\r";
      echo "                                              \r";

      echo date("H:i");
      echo $white." â© ".$blue."Sá»‘ dÆ° : $white ";
      echo $xu."\n";
	
	$coloigiuachung = true;
	if(count($mangcookie)==1){
          echo "                                                      \r";
           for($j = $dl;$j> 0;$j--){
             echo $green."Äang Chá» Delay TrÃ¡nh Block$yellow $j GiÃ¢y";
             sleep(1);
             echo "\r";
           }
		   break 1;
    }else if(count($mangcookie) > 1){
		echo "\r";
    echo "                                              \r";
	echo $blue." â©$blue ÄANG Äá»”I NICK$blue â— $white";	
	echo "\n";
		 break 2;
	}
	}else{
    echo "\r";
    echo "                                              \r";
	echo $green." â—$green FOLLOW THÃ€NH CÃ”NG$green â— $white";
	echo "\n";
	}
	loadtime($timedelay);
	
    } // foreach
	if($coloigiuachung == false){
	$ck = hoanthanhsub(rtrim($idnhanxu,","));
	if($ck !== null){
		if(isset($ck['mess'])){
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $green." â© ".$green.$ck['mess']."\n";
		}else if(isset($ck['error'])){
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $red." â© ".$red.$ck['error']."\n";
		}
	}
	$xu = getxu();
      echo "\r";
      echo "                                              \r";

      echo date("H:i");
      echo $white." â© ".$blue."Sá»‘ dÆ° : $white ";
      echo $xu."\n";
	}
	  if ($max >= $doi){
           $max=0;
           break;
          }
	}
  }
  }
  if ($rand == 'tym'){
    //$loai = 'sub';
    $list = getnv("tym",$useragent);
    $check = count($list);
    if($check <0){
      echo "                                                      \r";
     echo $white." âŒ ".$red."Háº¿t nhiá»‡m vá»¥ tym rá»“i\r";
	 if(count($mangcookie)==1){
          echo "                                                      \r";
           for($j = $dl;$j> 0;$j--){
             echo $green."Äang Chá» Delay TrÃ¡nh Block$yellow $j GiÃ¢y";
             sleep(1);
             echo "\r";
           }
        }
    }else{

	foreach ($list  as $listid) {
	$soloitym = 0;

    $id = $listid['idpost'];
	
	$csf = explode(';',explode('csrftoken=', $cookie)[1])[0];
	$chayfl = tym($id,$cookie,$csf);
	 //echo $id."-".$chayfl."\n";
	$max=$max+1;
    $g = json_decode($chayfl,true);
    if($g == null || $g['status'] !== 'ok'){
	echo "\r";
    echo "                                              \r";
	echo $red." â—$red TYM Lá»–I$red â— $white";	
	echo "\n";
	$soloitym ++;
	}else{
    echo "\r";
    echo "                                              \r";
	echo $green." â—$green TYM THÃ€NH CÃ”NG$green â— $white";
	echo "\n";
	$ck = hoanthanhtym($id);
	if($ck !== null){
		if(isset($ck['mess'])){
			$soloitym = 0;
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $green." â© ".$green.$ck['mess']."\n";
		  $_SESSION['xu'] = $_SESSION['xu']+300;
		}else if(isset($ck['error'])){
			echo "\r";
		  echo "                                              \r";

		  echo date("H:i");
		  echo $red." â© ".$red.$ck['error']."\n";
		  
		  // echo $id."\n";
		}
	}
	$xu = $_SESSION['xu'];
      echo "\r";
      echo "                                              \r";

      echo date("H:i");
      echo $white." â© ".$blue."Sá»‘ dÆ° : $white ";
      echo $xu."\n";
	}
	loadtime($timedelaytym);
	
	if($soloitym >4){
		if(count($mangcookie)==1){
          echo "                                                      \r";
           for($j = $dl;$j> 0;$j--){
             echo $green."Äang Chá» Delay TrÃ¡nh Block$yellow $j GiÃ¢y";
             sleep(1);
             echo "\r";
           }
		   //break 1;
    }else if(count($mangcookie) > 1){
		echo "\r";
    echo "                                              \r";
	echo $blue." â©$blue ÄANG Äá»”I NICK$blue â— $white";	
	echo "\n";
		 break 1;
	}
	}
	
  if ($max >= $doi){
	   $max=0;
	   break;
	  }
	}
  }
  }


}else if($h == '2'){
	echo "\r";
      echo "                                              \r";

      echo date("H:i");
      echo $white." â© ".$red."Cáº§n thÃªm nick : $white".$tenfb.$red." vÃ o trÆ°á»›c khi cháº¡y";
      echo $xu."\n";
}else{
	echo "\r";
      echo "                                              \r";

      echo date("H:i");
      echo $white." â© ".$red."Lá»–I, ÄANG Äá»”I NICK";
      echo $xu."\n";
}
}
}
if (count($mangcookie)==1 && empty($dl)){
  echo $pink." â© ".$blue."Dá»«ng Thá»i Gian: ";
  $dl=trim(fgets(STDIN));
}
if (count($mangcookie)==0){
unlink("ListccVIPIG.txt");
echo $pink." â›” ".$red."Táº¥t Cáº£ Cookie Äá»u Die\n";
echo $pink." â© ".$red."Ctrl+C vÃ  cháº¡y láº¡i tool\n";


}
// }



function cookie($cookie,$useragent){
$ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/');
$head[] = "Connection: keep-alive";
$head[] = "Keep-Alive: 300";
$head[] = "authority: www.instagram.com";
$head[] = "ccept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
$head[] = "accept-language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
$head[] = "cache-control: max-age=0";
$head[] = "upgrade-insecure-requests: 1";
$head[] = "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
$head[] = "sec-fetch-site: none";
$head[] = "sec-fetch-mode: navigate";
$head[] = "sec-fetch-user: ?1";
$head[] = "sec-fetch-dest: document";
curl_setopt($ch, CURLOPT_USERAGENT,$useragent );
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
$access = curl_exec($ch);
//echo $access;
curl_close($ch);

return $access;
}
function follow($id,$cookie,$csrftoken = null){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/web/friendships/'.$id.'/follow/');
	$headers = [
    'x-requested-with: XMLHttpRequest',
    'x-ig-www-claim: hmac.AR2KtRYzNVfelijR0GD6-VLJU3G-vRVGUezuXpjzaQ5m4MmZ',
    'x-ig-app-id: 936619743392459',
    'x-csrftoken: '.$csrftoken.'',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36',
    'x-instagram-ajax: bd344c4b4f36',
    'referer: https://www.instagram.com/'
];
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36');
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,array());
	$access = curl_exec($ch);
	curl_close($ch);
	return $access;
}
function shortcode_to_mediaid($shortcode,$cookie){

    $mediaid = false;

    $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, 'https://www.instagram.com/p/'.$shortcode);
$head[] = "Connection: keep-alive";
$head[] = "Keep-Alive: 300";
$head[] = "authority: www.instagram.com";
$head[] = "ccept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
$head[] = "accept-language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5";
$head[] = "cache-control: max-age=0";
$head[] = "upgrade-insecure-requests: 1";
$head[] = "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
$head[] = "sec-fetch-site: none";
$head[] = "sec-fetch-mode: navigate";
$head[] = "sec-fetch-user: ?1";
$head[] = "sec-fetch-dest: document";
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36');
curl_setopt($ch, CURLOPT_ENCODING, '');
curl_setopt($ch, CURLOPT_COOKIE, $cookie);
curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 60);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
$access = curl_exec($ch);
curl_close($ch);
if(strpos($access,'"media_id":"') !== false){
	$mediaid = explode('"',explode('"media_id":"',$access)[1])[0];
}
return $mediaid;

}
function tym($id,$cookie,$csrftoken = null){
	$mediaid = shortcode_to_mediaid($id,$cookie);
	if($mediaid !==false){
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://i.instagram.com/api/v1/web/likes/'.$mediaid.'/like/');
	$headers = [
    'x-requested-with: XMLHttpRequest',
    'content-length: 0',
    'content-type: application/x-www-form-urlencoded',
    'x-ig-www-claim: hmac.AR2KtRYzNVfelijR0GD6-VLJU3G-vRVGUezuXpjzaQ5m4MmZ',
    'x-ig-app-id: 936619743392459',
    'x-csrftoken: '.$csrftoken.'',
    'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36',
    'x-instagram-ajax: 1005633491',
    'referer: https://www.instagram.com/'
];
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.0.0 Safari/537.36');
	curl_setopt($ch, CURLOPT_ENCODING, '');
	curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	curl_setopt($ch, CURLOPT_POST,true);
	curl_setopt($ch, CURLOPT_POSTFIELDS,array());
	$access = curl_exec($ch);
	curl_close($ch);
	
	return $access;
	}else{
		return '';
	}
}
function loadtime($time){
        for ( $x = $time; $x--; $x ) {
echo "                                                      \r";
echo "\e[1;32mðŸ‡»ðŸ‡³ Vui LÃ²ng Chá» \e[1;37m \e[1;31m- \e[1;32m- \e[1;33m- \e[1;34m- \e[1;35m- \e[1;37m ".$x."\033[1;37m \e[1;33mGiÃ¢y";
usleep(170000);
echo "\r";
echo "                                                      \r";
echo "\e[1;36mðŸ‡»ðŸ‡³ Vui LÃ²ng Chá» \e[1;37m \e[1;33m- \e[1;34m- \e[1;35m- \e[1;36m- \e[1;31m- \e[1;37m ".$x."\033[1;37m \e[1;34m GiÃ¢y";
       usleep(170000);
       echo "\r";
       echo "                                                      \r";
       echo "\e[1;34mðŸ‡»ðŸ‡³ Vui LÃ²ng Chá» \e[1;37m \e[1;34m- \e[1;35m- \e[1;36m- \e[1;31m- \e[1;33m- \e[1;37m ".$x."\033[1;37m \e[1;31m GiÃ¢y";
       usleep(170000);
       echo "\r";
       echo "                                                      \r";
       echo "\e[1;33mðŸ‡»ðŸ‡³ Vui LÃ²ng Chá» \e[1;37m \e[1;35m- \e[1;36m- \e[1;31m- \e[1;33m- \e[1;34m- \e[1;37m ".$x."\033[1;37m \e[1;32m GiÃ¢y";
       usleep(170000);
       echo "\r";
       echo "                                                      \r";
       echo "\e[1;31mðŸ‡»ðŸ‡³ Vui LÃ²ng Chá» \e[1;37m \e[1;33m- \e[1;32m- \e[1;31m- \e[1;35m- \e[1;36m-\e[1;37m ".$x."\033[1;37m \e[1;36m GiÃ¢y";
       usleep(170000);
       echo "\r";
}
}

function getcookieVIPIG($user,$pass,$useragent){
  $ch=curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'https://vipig.net/login.php');
curl_setopt($ch, CURLOPT_COOKIEJAR, "VIPIG.txt");
curl_setopt($ch, CURLOPT_USERAGENT,$useragent);
$login =array('username' => $user,'password' => $pass,'submit' => 'ÄÄ‚NG NHáº¬P');
curl_setopt($ch, CURLOPT_POST,count($login));
curl_setopt($ch, CURLOPT_POSTFIELDS,$login);
curl_setopt($ch, CURLOPT_COOKIEFILE, "VIPIG.txt");
$source=curl_exec($ch);
curl_close($ch);
return $source;
}
function datnick($idfb,$useragent){
$dat=http_build_query(array('iddat[]'=> $idfb));
$ch=curl_init();
	curl_setopt($ch, CURLOPT_URL,'https://vipig.net/cauhinh/datnick.php');
	$head[]='Host: vipig.net';
	$head[]='content-length: '.strlen($dat);
	$head[]='accept: */*';
	$head[]='origin: https://vipig.net';
	$head[]='x-requested-with: XMLHttpRequest';
	$head[]='save-data: on';
	$head[]='content-type: application/x-www-form-urlencoded; charset=UTF-8';
	$head[]='referer: https://vipig.net/cauhinh/index.php';
	$head[]='accept-language: vi-VN, vi;q=0.9,fr-FR;q=0.8,fr;q=0.7, en-US;q=0.6,en;q=0.5,zh-CN;q=0.4.zh;q=0.3';
	$head[]='cookie: TawkConnectionTime=0';
  curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch,CURLOPT_POST, 1);
  curl_setopt($ch,CURLOPT_POSTFIELDS,$dat);
  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
  curl_setopt($ch,CURLOPT_HTTPHEADER, $head);
  curl_setopt($ch,CURLOPT_ENCODING, TRUE);
  curl_setopt($ch,CURLOPT_COOKIEFILE,"VIPIG.txt");
	$h = curl_exec($ch);
	curl_close($ch);
	return $h;
}

function getnv($loai,$useragent){
 $ch=curl_init();
 if($loai == 'tym'){
	curl_setopt($ch, CURLOPT_URL,'https://vipig.net/kiemtien/getpost.php');
	$head[]='referer: https://vipig.net/kiemtien/';
 }else{
 curl_setopt($ch, CURLOPT_URL,'https://vipig.net/kiemtien/'.$loai.'cheo/getpost.php');
 $head[]='referer: https://vipig.net/kiemtien/'.$loai.'cheo';
 }
 $head[]='Host: vipig.net';
 $head[]='accept: application/json, text/javascript, *'.'/'.'*; q=0.01';
 $head[]='x-requested-with: XMLHttpRequest';
 $head[]='user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36';
 
 curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
 curl_setopt($ch,CURLOPT_FOLLOWLOCATION, TRUE);
 curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
 curl_setopt($ch,CURLOPT_POST,1);
 curl_setopt($ch,CURLOPT_HTTPGET, true);
 curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, 0);
 curl_setopt($ch,CURLOPT_HTTPHEADER, $head);
 curl_setopt($ch,CURLOPT_ENCODING, TRUE);
 curl_setopt($ch,CURLOPT_COOKIEFILE, "VIPIG.txt");
 return json_decode(curl_exec($ch),true);
 curl_close($ch);
}

function getxu(){
  $url = "https://vipig.net/home.php";
  $curl = curl_init();
  curl_setopt_array($curl, array(
  CURLOPT_PORT => "443",
  CURLOPT_URL => "$url",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_SSL_VERIFYPEER => true,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_COOKIEFILE => "VIPIG.txt"
  ));
  $data = curl_exec($curl);
  curl_close($curl);
  preg_match('#id="soduchinh">(.+?)<#is', $data, $sd);
  $xu = $sd["1"];
  return $xu;
  $_SESSION['xu'] = $xu;
}

function hoanthanhsub($id)
{
    $url  = "https://vipig.net/kiemtien/subcheo/nhantien2.php";
    $data= ('id=').$id;
    $head = array(
        "Host: vipig.net",
        "content-length: " . strlen($data),
        "x-requested-with: XMLHttpRequest",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36",
        "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        "origin: https://vipig.net",
        "referer: https://vipig.net/kiemtien/subcheo/"
    );
    $ch   = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_COOKIEFILE => "VIPIG.txt",
        CURLOPT_HTTPHEADER => $head,
        CURLOPT_ENCODING => TRUE
    ));
    $a = json_decode(curl_exec($ch), true);
    return $a;
}
function hoanthanhtym($id)
{
    $url  = "https://vipig.net/kiemtien/nhantien.php";
    $data= ('id=').$id;
    $head = array(
        "Host: vipig.net",
        "content-length: " . strlen($data),
        "x-requested-with: XMLHttpRequest",
        "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/102.0.5005.63 Safari/537.36",
        "content-type: application/x-www-form-urlencoded; charset=UTF-8",
        "origin: https://vipig.net",
        "referer: https://vipig.net/kiemtien/"
    );
    $ch   = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_FOLLOWLOCATION => TRUE,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_COOKIEFILE => "VIPIG.txt",
        CURLOPT_HTTPHEADER => $head,
        CURLOPT_ENCODING => TRUE
    ));
    $a = json_decode(curl_exec($ch), true);
    return $a;
}
