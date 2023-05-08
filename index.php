<?php
/*
@datisnetwork
https://t.me/datisnetwork
*/
error_reporting(0);
require 'jdf.php';
$token='5847031605:AAH-L-DSIZSVr3NOuDnssDKDmJCujt1aCRo';
define('API_KEY',$token);
function Neman($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}

$ne= mysqli_connect("localhost","root", "","myTestDatabase");
mysqli_set_charset($ne,"utf8");

$input=file_get_contents("php://input");
$input = json_decode($input,true);
$message=$input['message'];
$chat_id = $input['message']['chat']['id'];
$text = $input['message']['text'];
$message_id = $input['message']['message_id'];
$name = $input['message']['from']['first_name'];
$last = $input['message']['from']['last_name'];
$from_id = $input['message']['from']['id'];
$username = $input['message']['from']['username'];
$lat=$message['location']['latitude'];
$lng=$message['location']['longitude'];

$call=$input['callback_query'];
$cchat=$call['message']['chat'];
$chatid=$cchat['id'];
$cafr=$call['from'];
$did=$cafr['id'];
$data=$call['data'];
$msgid=$call['message']['message_id'];
$tx=$call['message']['text'];
$idc=$call['id'];
$typ=$cchat['type'];
$titl=$cchat['title'];

$admin = ['224874049','000000000000']; //آیدی عددی ادمین ها

$from=$did?$did:$from_id;
$msgid=$msgid?$msgid:$message_id;
$teda=$text?$text:$data;

$sql=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$from'"));

$ad=mysqli_fetch_assoc(mysqli_query($ne,"select * from admin where step='admin' and txt=$from"))?true:false;

$channel="datisnetwork"; //اکانال
$channel2="datisnetwork"; //کانال دوم
$support="پشتبانی ربات : @datisnetwork"; // پشتیبانی ربات و متن

function sendmessage($chat_id, $text ,$k=null,$m=null,$h=null,$d=null,$n=null){
return Neman('sendmessage',[
'chat_id'=>$chat_id,
'text'=>$text,
'reply_markup'=>$k,
'reply_to_message_id'=>$m,
'parse_mode'=>$h,
'disable_web_page_preview'=>$d,
'disable_notification'=>$n
]);
}function editmessage($chatid,$message,$text,$k=null,$p=null,$d=null){
return Neman('editmessagetext',[
'chat_id'=>$chatid,
'message_id'=>$message,
'text'=>$text,
'reply_markup'=>$k,
'parse_mode'=>$p,
'disable_web_page_preview'=>$d
]);
}function forward($chat,$from,$msi){
return Neman('forwardmessage',[
'chat_id'=>$chat,
'from_chat_id'=>$from,
'message_id'=>$msi
]);
}function sendphoto($chat,$photo,$caption='',$key='',$rp=''){
	Neman('sendphoto',[
	'chat_id'=>$chat,
	'photo'=>$photo,
	'caption'=>$caption,
	'reply_markup'=>$key,
	'reply_to_message_id'=>$rp
	]);
}function is_join($from){
	global $channel,$channel2;
	$x=Neman('getchatmember',[
	'chat_id'=>"@".$channel,
	'user_id'=>$from
	])->result->status;
	$x2=Neman('getchatmember',[
	'chat_id'=>"@".$channel2,
	'user_id'=>$from
	])->result->status;
	if($x=="left" or $x2=="left"){
		return false;
	}else{
		return true;
	}
}function passrand($a=5){
	global $ne;
$t='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
$t=preg_split('//u',$t);
unset($t[0]);unset($t[count($t)]);
$x='';
for($i=count($t)-$a;$i<count($t);$i++){
$z=array_rand($t);
$x.=$t[$z];
}
$g=mysqli_fetch_array(mysqli_query($ne,"select * from members where user=$x"));
if(!$g){
	return $x;
}else{
	passrand();
}}
function where($lat1,$long1,$lat2,$long2,$unit){
	$theta = $long1 - $long2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
	$unit = strtoupper($unit);
    if($unit == "K"){
    	$miles=$miles * 1.609344;
    	preg_match('/\d+(\.\d{1,2})?/',$miles,$ki);
    	$ki=$ki[0].' km';
        return $ki;
    }else if($unit == "N"){
        return($miles * 0.8684).' nm';
    }else{
        return $miles.' mi';
    }
}
function alert($text,$show=false){
	global $idc;
	Neman('answercallbackquery',[
	'callback_query_id'=>$idc,
	'text'=>$text,
	'show_alert'=>$show
	]);
}function markup($key){
	global $from,$msgid;
	Neman('editMessageReplyMarkup',[
	'chat_id'=>$from,
	'message_id'=>$msgid,
	'reply_markup'=>$key
	]);
}function tk(){
	global $sql,$admin;
	$r='';
		if(!$sql['name']){
			$r.='نام،';
		}
		if(!$sql['city']){
			$r.='شهر،';
		}
		if(!$sql['lat']){
			$r.='موقعیت چی پی اس،';
		}
		if(!$sql['photo']){
			$r.='عکس،';
		}
		$e=explode('،',$r);
		$z=count($e);
		$x=$e[$z-2];
		if($z > 2){
			$r=str_replace('،'.$x.'،',' و '.$x,$r);
		}
		else{
			$r=str_replace("،","",$r);
		}
		return $r;
	}
function nem($x){
	$x=floor($x);
	if(is_numeric($x) & $x<7 & $x>0){
		switch($x){
			case 1:
			return 'تبلیغات سایت ها ربات ها و کانال ها';
			break;
			case 2:
			return 'ارسال محتوای غیر اخلاقی در چت';
			break;
			case 3:
			return 'ایجاد مزاحمت';
			break;
			case 4:
			return 'پخش شماره موبایل یا اطلاعات شخصی دیگران';
			break;
			case 5:
			return 'جنسیت اشتباه در پروفایل';
			break;
			case 6:
			return 'دیگر موارد...';
			break;
		}
	}else{
		return false;
	}
}

$usbot=Neman('getme')->result->username;

$k_panel=json_encode(['keyboard'=>[
[['text'=>"🎲Send To All"],['text'=>"📊State"],['text'=>"🎲Forward To All"]],
[['text'=>"✅New Admin"],['text'=>"✅Remove Admin"]],
[['text'=>"❌Block"],['text'=>"❌UnBlock"]],
[['text'=>"📢Back"]],
],'resize_keyboard'=>true]);

$k_panel2=json_encode(['keyboard'=>[
[['text'=>"🎲Send To All"],['text'=>"📊State"],['text'=>"🎲Forward To All"]],
[['text'=>"📢Back"]],
],'resize_keyboard'=>true]);

$k_pane=$from==$admin?$k_panel:$k_panel2;
$k_backpanel=json_encode(['keyboard'=>[
[['text'=>"📢Back To Panel"]]
],'resize_keyboard'=>true]);

$k_jens=json_encode(['inline_keyboard'=>[
[['text'=>"من 🙎‍♂ پسرم",'callback_data'=>"boy"],['text'=>"من 🙍‍♀دخترم",'callback_data'=>"girl"]]
]]);

$_x=10;
for($i=0;$i<=14;$i++){
	for($ii=0;$ii<=5;$ii++){
		$k_sen[$i][$ii]['text']=$_x;
		$_x++;
	}
}
$k_sen=json_encode(['keyboard'=>$k_sen,'resize_keyboard'=>true]);

$k_join=json_encode(['inline_keyboard'=>[
[['text'=>'کانال اول','url'=>'https://t.me/'.$channel1],['text'=>'کانال دوم','url'=>'https://t.me/'.$channel2]],
[['text'=>"♻️ بررسی عضویت و فعالسازی ♻️",'callback_data'=>"joined"]]
]]);

$k_added=json_encode(['inline_keyboard'=>[
[['text'=>"معرفی افراد بیشتر",'callback_data'=>"add"]]
]]);

$k_start=json_encode(['keyboard'=>[
[['text'=>"🔗 به یه ناشناس وصلم کن!"]],
[['text'=>'💑 پیدا کردن افراد مورد پسند من']],
[['text'=>"📍پیدا کردن افراد نزدیک با GPS"]],[['text'=>"💰 سکه"],['text'=>"👤 پروفایل"],['text'=>"🤔 راهنما"]],
[['text'=>"🚸 معرفی به دوستان ( سکه رایگان)"]]
],'resize_keyboard'=>true]);

$k_add=json_encode(['inline_keyboard'=>[
[['text'=>"🚸 معرفی به دوستان ( سکه رایگان)",'callback_data'=>"add"]],
[['text'=>'💳 خرید سکه','callback_data'=>'buy']]
]]);

$k_pro=json_encode(['inline_keyboard'=>[
[['text'=>"تکیمل پروفاـــــیل👤",'callback_data'=>"tkpro"]]
]]);

$k_pro1=json_encode(['inline_keyboard'=>[
[['text'=>"📍مشاهده موقعیت GPS من",'callback_data'=>"vigps"]],
[['text'=>"📝 ویرایش پروفایل",'callback_data'=>"editpro"]]
]]);

$k_pro2=json_encode(['inline_keyboard'=>[
[['text'=>"✏️ تغییر نام",'callback_data'=>"edit-name"],['text'=>"✏️ تغییر جنسیت",'callback_data'=>"edit-jens"]],
[['text'=>"✏️ تغییر شهر",'callback_data'=>"edit-city"],['text'=>"✏️ تغییر سن",'callback_data'=>"edit-sen"]],
[['text'=>"✏️ تغییر موقعیت GPS",'callback_data'=>"edit-gps"],['text'=>"✏️ تغییر عکس",'callback_data'=>"edit-photo"]]
]]);

$k_adgps=json_encode(['inline_keyboard'=>[
[['text'=>"✏️ ثبت موقعیت GPS",'callback_data'=>"edit-gps"]]
]]);

$k_gps=json_encode(['keyboard'=>[
[['text'=>"📍برای ارسال موقعیت GPS اینجا را بزنید",'request_location'=>true]],
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true]);

$k_shans=json_encode(['inline_keyboard'=>[
[['text'=>"🎲 جستجوی شانسی",'callback_data'=>"shans-sh"]],
[['text'=>"🙎‍♂جستجوی پسر",'callback_data'=>"shans-pes"],['text'=>"🙎‍♀جستجوی دختر",'callback_data'=>"shans-dokh"]],
[['text'=>"🛰 جستجوی اطراف",'callback_data'=>"shans-at"]]
]]);

$k_atgps=json_encode(['inline_keyboard'=>[
[['text'=>"فقط 🙎‍♀دختر ها",'callback_data'=>"at-dokh"],['text'=>"فقط 🙎‍♂ پسر ها",'callback_data'=>"at-pes"]],
[['text'=>"همه رو نشون بده",'callback_data'=>"at-all"]]
]]);

$k_canc=json_encode(['keyboard'=>[
[['text'=>"◻️ مشاهده پروفایل این مخاطب ◻️"]],
[['text'=>"قطع مکالمه"]]
],'resize_keyboard'=>true]);

$k_et=json_encode(['inline_keyboard'=>[
[['text'=>"❌ اتمام چت",'callback_data'=>"m-ye"],['text'=>"🔙 ادامه چت",'callback_data'=>"m-no"]]
]]);

$k_cha=json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$sql['chat']],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$sql['chat']]],
[['text'=>"🔒بلاک کردن کاربر",'callback_data'=>"cha-bl-".$sql['chat']],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$sql['chat']]]
]]);

$k_back=json_encode(['keyboard'=>[
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true]);

$k_backed=json_encode(['keyboard'=>[
[['text'=>"❗بیخیال جستجو"]]
],'resize_keyboard'=>true]);

if(!$ne){
	sendmessage($admin,"خطا در اتصال به دیتابیس : ".mysqli_connect_error($ne));
}
mysqli_query($ne,"create table if not exists members(id int,step text,jens text,sen text,lng text,lat text,coin int default 0,coad int default 0,user text,added text,name text,city text,online text,photo text,chat text,type text,shansi int default 3,ssen text,va int default 3,banlist longtext,err int default 0,tr text)engine=MyISAM default character set=utf8 collate=utf8_persian_ci");
mysqli_query($ne,"create table if not exists admin(step text,txt longtext)engine=MyISAM default character set=utf8 collate=utf8_persian_ci");

if($sql['err']>= 8 & $from != $admin){
	sendmessage($from,"🚫شما توسط ادمین مسدود شده اید و نمی توانید در ربات فعالیت کنید.",null,$msgid);
	exit();
}

if(preg_match('/^\/start\s?(.*)/si',$text,$start)){
	if(!$sql['id']){
		sendmessage($from,"سلام $name عزیز ✋

به 《زومیت چت 🤖》 خوش اومدی ، توی این ربات می تونی افراد #نزدیک ات رو پیدا کنی و باهاشون آشنا شی و یا به یه نفر بصورت #ناشناس وصل شی و باهاش #چت کنی ❗️

- استفاده از این ربات رایگانه و اطلاعات تلگرام شما مثل اسم،عکس پروفایل یا موقعیت GPS کاملا محرمانه هست?? 

برای شروع جنسیتت رو انتخاب کن 👇",$k_jens);
	$pr=passrand();
	mysqli_query($ne,"insert into members(id,step,user,ssen)values('$from','jens','$pr','off')");
	
$gid=mysqli_fetch_array(mysqli_query($ne,"select user,id,coin from members where user='{$start[1]}' limit 1"));
	if($gid['id']){
		$coin2=$gid['coin']+3;
		sendmessage($gid['id'],"💥تبریک!

هم اکنون یک نفر با لینک مخصوص شما در ربات عضو شد و به شما 3 سکه بابت این معرفی تعلق گرفت.

💰سکه های شما  : $coin2

به محض تکمیل پروفایل کاربر به شما 5 سکه دیگر تعلق خواهد گرفت😎",$k_added);
		mysqli_query($ne,"update members set coin=coin+3,coad=coad+1 where user='{$start[1]}'");
		mysqli_query($ne,"update members set added='{$start[1]}' where id=$from");
	}
	exit();
	}else if(!$sql['step']){
		sendmessage($from,"خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

از منوی پایین👇 انتخاب کن",$k_start,$msgid);
	exit();
	}else if($sql['chat']){
		sendmessage($from,"🤖 پیام سیستم 👇

❗️اول باید مکالمه جاری رو قطع کنی بعدا 《استارت》 بزنی 👇👇👇");
	exit();
	}
}
if(($data=="boy"|$data=="girl") & $sql['step']=='jens'){
	Neman('deletemessage',[
	'chat_id'=>$from,
	'message_id'=>$msgid
	]);
	sendmessage($from,"خب حالا کافیه فقط سنت رو بهم بگی تا وارد ربات شیم ؟

`• سنت رو از لیست پایین 👇انتخاب کن یا خودت تایپ کن`",$k_sen,null,'markdown');
	mysqli_query($ne,"update members set jens='$data',step='sen' where id=$from");
}
else if($sql['step']=="jens"){
	sendmessage($from,"❓ لطفا جنسیت خود را انتخاب کنید 👇",$k_jens,$msgid);
}
else if($sql['step']=="sen"){
	if(9<=$text & 99>=$text){
		sendmessage($from,"✅اطلاعات شما ثبت شد.

به خانواده بزرگ《زومیت چت 🤖》 خوش اومدی بهت توصیه میکنم اول از همه با لمس کردن 《🤔 راهنما》   با ربات آشنا شی

از منوی پایین👇 انتخاب کن",$k_start);
	mysqli_query($ne,"update members set step='',sen='".floor($text)."' where id=$from");
	}else{
	sendmessage($from,"⚠️ خطا : عدد سن باید بین 9 الی 99 باشد .",$k_sen);
	}
}

else if($data=="joined"){
	if(!is_join($from)){
		alert("باید در هر دو کانال عضو شوید",true);
	}else{
		sendmessage($from,"✅ عضویت شما تایید شد ! شما هم اکنون می توانید از امکانات ویژه ربات استفاده کنید ! 


💥 با تکمیل کردن اطلاعات پروفایلت 💰 5 تا سکه رایگان بگیر 😍


`یکی از گزینه های زیر را لمس کنید 👇`",null,null,'markdown');
	sendmessage($from,"همین الان برای شروع چت 《🎲جستجوی شانسی》  رو بزن و شانستو #رایگان و بدون نیاز به 💰سکه امتحان کن 😎",$k_start);
	}
}

else if(!is_join($from) & !$sql['step']){
	sendmessage($from,"‏$name عزیز 
 برای استفاده از ربات ابتدا باید در دو کانال ما عضو بشی 👇

 بعد از عضـــویت « بررسی عضویت و فعال سازی » را لمس کنید تا ربات برای شما فعال شود 👇",$k_join);
}

else if($data=="editpro"){
	if($sql['chat']){
		alert("⚠️ خطا : ویرایش پروفایل هنگام چت امکان پذیر نمی باشد.

لطفا بعد از اتمام چت دوباره امتحان کنید.",true);
	}else{
		markup($k_pro2);
	}
}
else if(preg_match('/back2-(.+)/',$data,$b)){
	if($sql['chat']){
		$use=mysqli_fetch_assoc(mysqli_query($ne,"select user from members where id='{$b[1]}'"))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇",$k_canc,$msgid);
	}
	else{
	Neman('deletemessage',['chat_id'=>$from,'message_id'=>$msgid]);
	sendmessage($from,"خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`از منوی پایین👇 انتخاب کن`",$k_start,null,'markdown');
	mysqli_query($ne,"update members set step='' where id=$from");
	}
}
else if($data=="back"){
	if($sql['chat']){
		alert("⚠️ خطا : ویرایش پروفایل هنگام چت امکان پذیر نمی باشد.

لطفا بعد از اتمام چت دوباره امتحان کنید.",true);
	}
	else{
	Neman('deletemessage',['chat_id'=>$from,'message_id'=>$msgid]);
	sendmessage($from,"خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`از منوی پایین👇 انتخاب کن`",$k_start,null,'markdown');
	mysqli_query($ne,"update members set step='' where id=$from");
	}
}

else if($text=="🔙 بازگشت" & !empty($sql['step'])){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		sendmessage($from,"خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`از منوی پایین👇 انتخاب کن`",$k_start,$msgid,'markdown');
		mysqli_query($ne,"update members set step='' where id=$from");
	}
}

else if($text=="🔗 به یه ناشناس وصلم کن!"){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		sendmessage($from,"*به کی وصلت کنم؟*   انتخاب کن👇",$k_shans,$msgid,'markdown');
		if($sql['shansi'] != 0){
			sendmessage($from,"<code>استفاده از 🎲 جستجوی شانسی  رایگان و بدون نیاز به سکه است❗️</code>

توصیه میکنم قبل از استفاده حتما راهنمای بخش چت ناشناس (/help_chat) رو بخونی.",null,$msgid+1,'html');
			mysqli_query($ne,"update members set shansi=shansi-1 where id=$from");
		}
	}
}

else if($text=='💑 پیدا کردن افراد مورد پسند من'){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		$k_jens2=json_encode(['inline_keyboard'=>[
			[['text'=>'فقط 🙎‍♀دختر ها','callback_data'=>'rade-dokh'],['text'=>'فقط 🙎‍♂ پسر ها','callback_data'=>'rade-pes']],
			[['text'=>'همه رو نشون بده','callback_data'=>'rade-all']]
		]]);
		sendmessage($from,"چه کسایی رو نشونت بدم؟  انتخاب کن👇",$k_jens2);
	}
}


else if($data=='rade-all'){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		$k_rade=json_encode(['inline_keyboard'=>[
			[['text'=>'15 - 20','callback_data'=>'rad-a-1520'],['text'=>'20 - 25','callback_data'=>'rad-a-2025']],
			[['text'=>'25 - 30','callback_data'=>'rad-a-2530'],['text'=>'30 - 35','callback_data'=>'rad-a-3035']],
			[['text'=>'35 - 40','callback_data'=>'rad-a-3540'],['text'=>'40 - 45','callback_data'=>'rad-a-4045']],
			[['text'=>'45 - 50','callback_data'=>'rad-a-4550'],['text'=>'50 - 55','callback_data'=>'rad-a-5055']],
			[['text'=>'55 - 60','callback_data'=>'rad-a-5560'],['text'=>'60 - 65','callback_data'=>'rad-a-6065']],
			[['text'=>'همه','callback_data'=>'rad-a-1565']]
		]]);
		editmessage($did,$msgid,'خیلی خوب حالا میخوای اونی که بهش ‌وصل میشی چن ساله باشه؟ 
مثلا ۲۰.۲۵ افراد ۲۰ تا ۲۵ سال رو بهت نشون میده.
انتخاب کن 👇',$k_rade);
	}
}



else if($data=='rade-dokh'){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		$k_rade=json_encode(['inline_keyboard'=>[
			[['text'=>'15 - 20','callback_data'=>'rad-d-1520'],['text'=>'20 - 25','callback_data'=>'rad-d-2025']],
			[['text'=>'25 - 30','callback_data'=>'rad-d-2530'],['text'=>'30 - 35','callback_data'=>'rad-d-3035']],
			[['text'=>'35 - 40','callback_data'=>'rad-d-3540'],['text'=>'40 - 45','callback_data'=>'rad-d-4045']],
			[['text'=>'45 - 50','callback_data'=>'rad-d-4550'],['text'=>'50 - 55','callback_data'=>'rad-d-5055']],
			[['text'=>'55 - 60','callback_data'=>'rad-d-5560'],['text'=>'60 - 65','callback_data'=>'rad-d-6065']],
			[['text'=>'همه','callback_data'=>'rad-d-1565']]
		]]);
		editmessage($did,$msgid,'خیلی خوب حالا میخوای اونی که بهش ‌وصل میشی چن ساله باشه؟ 
مثلا ۲۰.۲۵ افراد ۲۰ تا ۲۵ سال رو بهت نشون میده.
انتخاب کن 👇',$k_rade);
	}
}

else if($data=='rade-pes'){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		$k_rade=json_encode(['inline_keyboard'=>[
			[['text'=>'15 - 20','callback_data'=>'rad-p-1520'],['text'=>'20 - 25','callback_data'=>'rad-p-2025']],
			[['text'=>'25 - 30','callback_data'=>'rad-p-2530'],['text'=>'30 - 35','callback_data'=>'rad-p-3035']],
			[['text'=>'35 - 40','callback_data'=>'rad-p-3540'],['text'=>'40 - 45','callback_data'=>'rad-p-4045']],
			[['text'=>'45 - 50','callback_data'=>'rad-p-4550'],['text'=>'50 - 55','callback_data'=>'rad-p-5055']],
			[['text'=>'55 - 60','callback_data'=>'rad-p-5560'],['text'=>'60 - 65','callback_data'=>'rad-p-6065']],
			[['text'=>'همه','callback_data'=>'rad-p-1565']]
		]]);
		editmessage($did,$msgid,'خیلی خوب حالا میخوای اونی که بهش ‌وصل میشی چن ساله باشه؟ 
مثلا ۲۰.۲۵ افراد ۲۰ تا ۲۵ سال رو بهت نشون میده.
انتخاب کن 👇',$k_rade);
	}
}

else if(preg_match('~rad-(a|p|d)-(\d\d)(\d\d)~',$data,$d)){
	$j=$d[1]=='p'?'boy':'girl';$min=$d[2];$max=$d[3];$je=$d[1]=='a'?'':" jens='$j' and";
	$q=mysqli_query($ne,"select * from members where$je sen between $min and $max");
	while($r=mysqli_fetch_assoc($q))
		$ar[$r['user']]=$r['online'];
	arsort($ar);
	$a=array_chunk($ar,7,true);
	$i=0;$t='';
	foreach($a[0] as $k=>$v){
		$r=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where user='$k'"));
		$w=$r['online'];
		if($w>time()-30)$w='هم اکنون انلاین است';
		else if($w>time()-3600)$w='چند دقیقه پیش آنلاین بود';
		else if($w>time()-86400)$w='چند ساعت پیش آنلاین بود';
		else $w='چند روز پیش آنلاین بود';
		$je=$r['jens']=='boy'?"🙎‍♂️":"🙍‍♀";
		++$i;
		$ch=$r['chat']?' (در حال چت)':'';
		$s='  '.$r['sen'].' ساله';
		$wh=where($sql['lat'],$sql['lng'],$r['lat'],$r['lng'],'k');
		$city=$r['city']?PHP_EOL.'از '.$r['city']:'';
		if($r['lng'] and $wh!='0 km' and $sql['lng'])$we=PHP_EOL."فاصله از شما : (🏁 ".$wh.' )';
		$na=mb_substr($r['name'],0,40);
		$t.="$i. $je $na$s$city
$w$ch$we
/user_$k
〰〰〰〰〰〰〰〰〰〰〰".PHP_EOL;
	}
	if($t){
		alert('صبر');
		$k='';
		if(count($a)>1)
		$k=json_encode(['inline_keyboard'=>[
			[['text'=>'بعدی','callback_data'=>"li-{$d[1]}-{$d[2]}{$d[3]}-1"]]
		]]);
		editmessage($did,$msgid,'انتخاب کنید : '.PHP_EOL.PHP_EOL.$t,$k);
	}else
		alert('موردی یافت نشد.',true);
}

else if(preg_match('~li-(.+)-(\d\d)(\d\d)-(\d+)~',$data,$d)){
	$j=$d[1]=='p'?'boy':'girl';$min=$d[2];$max=$d[3];$je=$d[1]=='a'?'':" jens='$j' and";
	$q=mysqli_query($ne,"select * from members where$je sen between $min and $max");
	while($r=mysqli_fetch_assoc($q))
		$ar[$r['user']]=$r['online'];
	arsort($ar);
	$a=array_chunk($ar,7,true);
	$i=0;$t='';
	foreach($a[$d[4]] as $k=>$v){
		$r=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where user='$k'"));
		$w=$r['online'];
		if($w>time()-30)$w='هم اکنون انلاین است';
		else if($w>time()-3600)$w='چند دقیقه پیش آنلاین بود';
		else if($w>time()-86400)$w='چند ساعت پیش آنلاین بود';
		else $w='چند روز پیش آنلاین بود';
		$je=$r['jens']=='boy'?"🙎‍♂️":"🙍‍♀";
		++$i;
		$ch=$r['chat']?' (در حال چت)':'';
		$s='  '.$r['sen'].' ساله';
		$wh=where($sql['lat'],$sql['lng'],$r['lat'],$r['lng'],'k');
		$city=$r['city']?PHP_EOL.'از '.$r['city']:'';
		if($r['lng'] and $wh!='0 km' and $sql['lng'])$we=PHP_EOL."فاصله از شما : (🏁 ".$wh.' )';
		$na=mb_substr($r['name'],0,40);
		$t.="$i. $je $na$s$city
$w$ch$we
/user_$k
〰〰〰〰〰〰〰〰〰〰〰".PHP_EOL;
	}
		alert('صبر');
		if(count($a)-1==$d[4])
			$k=json_encode(['inline_keyboard'=>[
			[['text'=>'قبلی','callback_data'=>"li-{$d[1]}-{$d[2]}{$d[3]}-".($d[4]-1)]]
		]]);
		else if($d[4]==0)
			$k=json_encode(['inline_keyboard'=>[
			[['text'=>'بعدی','callback_data'=>"li-{$d[1]}-{$d[2]}{$d[3]}-".($d[4]+1)]]
		]]);
		else
			$k=json_encode(['inline_keyboard'=>[
			[['text'=>'قبلی','callback_data'=>"li-{$d[1]}-{$d[2]}{$d[3]}-".($d[4]-1)],['text'=>'بعدی','callback_data'=>"li-{$d[1]}-{$d[2]}{$d[3]}-".($d[4]+1)]]
		]]);
		editmessage($did,$msgid,'انتخاب کنید : '.PHP_EOL.PHP_EOL.$t,$k);
}

else if(preg_match('/pay-(\d+)-(\d+)/',$data,$d)){
	Neman('sendphoto',[
	'chat_id'=>$from,
	'caption'=>'⚠️ دقت کنید !
حتما پس از پرداخت گزینه «تکمیل فراید پرداخت» را لمس کنید تا سکه را دریافت کنید.


درصورت عدم ورود به درگاه پرداخت ، فیلترشکن خود را خاموش کنید',
	'photo'=>new curlfile('pay.jpg')
	]);
	$coin=$d[1];$pay=$d[2];
	$dir=dirname($_SERVER['SCRIPT_URI']);
	$k_pay=json_encode(['inline_keyboard'=>[
		[['text'=>'ورود به درگاه پرداخت','url'=>$dir.'/pay.php?amount='.$pay.'&callback='.$dir.'/payed.php?from='.$did]]
	]]);
	sendmessage($from,"▪️ پرداخت از طریق درگاه بانکی شتابی بصورت کاملا امن انجام میگیرد.

⚠️ هنگام پرداخت صحفه‌ پرداخت ممکن است دیر بالا بیاورد پس حداقل تا 30 ثانیه به هیچ وجه از صحفه پرداخت خارج نشوید!! 

لینک خرید $coin سکه به مبلغ $pay تومان برای شما ساخته شد 👇",$k_pay);
}


else if($data=='buy'){
	$k_pay=json_encode(['inline_keyboard'=>[
		[['text'=>'1⃣ 30 سکه | 5000 تومان','callback_data'=>'pay-30-5000']],
		[['text'=>'2⃣ 80 سکه | 8000 تومان','callback_data'=>'pay-80-8000']],
		[['text'=>'3⃣ 210 سکه | 12000 تومان','callback_data'=>'pay-210-12000']],
		[['text'=>'4⃣ 💥420 سکه | 19000 تومان','callback_data'=>'pay-420-19000']],
		[['text'=>'5⃣ 1040 سکه | 34000 تومان','callback_data'=>'pay-1040-34000']]
	]]);
	
	editmessage($did,$msgid,"💰 شما در این قسمت میتوانید برای حساب خود سکه خرید کنید
آموزش خریدسکه رو حتما و تاکیدا قبل از خرید ببینید
《 @Source_Home 》

در صورت بروز مشکل در پرداخت با پشتییانی در تماس باشید .
آدرس پشتیبانی 👈 : @Source_Home",$k_pay);
}


else if($text=="📍پیدا کردن افراد نزدیک با GPS"){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else if(!$sql['lat']){
		sendmessage($from,"انتظار که نداری بدون دونستن موقعیتت بتونم افراد نزدیکتو پیدا کنم؟

⚠️ خطا : شما موقعیت مکانی خود را ثبت نکرده اید.

با زدن گزینه ✏️ ثبت موقعیت GPS  ، موقعیت خود را ثبت کنید 👇",$k_adgps);
	}else{
		sendmessage($from,"*چه کسایی رو نشونت بدم؟*  انتخاب کن👇",$k_atgps,$msgid,'markdown');
	}
}

else if(preg_match('/^(ta|ka)-(.+)-(.+)/',$data,$t)){
	$ti=$t[3];
	$a=$t[2];
	$ty=$t[1];
	if($ti < time()-180){
		alert("⚠ خطا : تاریخ انقضای این جستجو تمام شده است !

لطفا دوباره جستجو کنید✔",true);
		exit();
		$g=mysqli_fetch_assoc(mysqli_query($ne,"select * from atch where time='$ti'"));
		$s=count($g['js']);
		if($ty=='ta'){
			
			if($a!=$s){
		$k=json_encode(['inline_keyboard'=>[
				[['text'=>"⬅ لیست بعدی",'callback_data'=>"ta-". $a+1 ."-$ti"],
				['text'=>"➡ لیست قبلی",'callback_data'=>"ka-". $a-1 ."-$ti"]]
			]]);
			}else if($a==$s){
		$k=json_encode(['inline_keyboard'=>[
				[['text'=>"➡ لیست قبلی",'callback_data'=>"ka-". $a-1 ."-$ti"]]
			]]);
			}else if($a==0){
		$k=json_encode(['inline_keyboard'=>[
				[['text'=>"⬅ لیست بعدی",'callback_data'=>"ta-". $a+1 ."-$ti"]]
			]]);
			}
			
			$ar='';	
			foreach(json_decode($g['js'])[$a] as $value=>$value2){
				$g=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where user='$value'"));
				$where=str_replace(" km","",where($sql['lat'],$sql['lng'],$g['lat'],$g['lng'],'k'));
			$u=$g['jens']=='boy'?'🙎‍♂':'🙎‍♀';
			
			if($value2 > time()-60){
				$t='لحظه';
			}else if($value2 > time()-3600){
				$t='دقیقه';
			}else if($value2 > time()-86400){
				$t='ساعت';
			}else{
				$t='روز';
			}
			
			$ar.=" {$g['sen']} $u {$g['name']} /user_{$g['user']} 
<code>⏳ چند $t پیش آنلاین بوده</code> (🏁 {$where}km)
〰〰〰〰〰〰〰〰〰〰〰\n";
			}
			editmessage($from,$msgid,"📍 لیست افراد نزدیک شما که در ۳ روز اخیر آنلاین بوده اند

$ar

جستجو شده در ".jdate('y/m/d H:i:s',$ti,'','','en'),$k,$msgid,'html');
			
		}
		
	}
}


else if(preg_match('/^at-(.+)/i',$data,$s)){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else{
		$s=$s[1];
		
		if($s=='pes' | $s=='dokh'){
			$j=" and jens='";
			$j.=$s=='pes'?'boy':'girl';
			$j.="'";
		}
			
			$ti=time()-259200;
			$qu=mysqli_query($ne,"select user,lat,lng,online from members where id!='$from' and lat!='' and name!='' and online > $ti{$j}");
		while($g=mysqli_fetch_assoc($qu)){
				$where=str_replace(" km","",where($sql['lat'],$sql['lng'],$g['lat'],$g['lng'],'k'));
					if($where <= 20){
						if($g['user']){
						$a[$g['user']]=$g['online'];
						}
					}
			}
			arsort($a);
			$js=array_chunk($a,10,true);
			$ar='';	
			foreach($js[0] as $value=>$value2){
$g=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where user='$value'"));
				$where=str_replace(" km","",where($sql['lat'],$sql['lng'],$g['lat'],$g['lng'],'k'));
			$u=$g['jens']=='boy'?'🙎‍♂':'🙎‍♀';
			
			if($value2 > time()-60){
				$t='لحظه';
			}else if($value2 > time()-3600){
				$t='دقیقه';
			}else if($value2 > time()-86400){
				$t='ساعت';
			}else{
				$t='روز';
			}
			
			$ar.=" {$g['sen']} $u {$g['name']} /user_{$g['user']} 
<code>⏳ چند $t پیش آنلاین بوده</code> (🏁 {$where}km)
〰〰〰〰〰〰〰〰〰〰〰\n";
			}
			if(count($js)>1){
				$k_att=json_encode(['inline_keyboard'=>[
				[['text'=>"⬅ مشاهده ادامه لیست",'callback_data'=>"ta-0-".time()]]
			]]);
			mysqli_query($ne,"insert into atch (id,js,time)values('$from','".json_encode($js)."','".time()."')");
			}
			sendmessage($from,"📍 لیست افراد نزدیک شما که در ۳ روز اخیر آنلاین بوده اند

$ar

جستجو شده در ".jdate('y/m/d H:i:s','','','','en'),$k_att,$msgid,'html');
			
	}
}

else if($text=='❗بیخیال جستجو' & !empty($sql['type']) & empty($sql['chat'])){
	sendmessage($from,"‼عملیات لغو شد.

➡به منو برگشتید.
⬅چه کمکی میتونم بکنم؟",$k_start,$msgid);
	mysqli_query($ne,"update members set type='',step='' where id=$from");
}

else if($text=="/on"){
	sendmessage($from,"✅ جستجوی همسن برای شما فعال شد. 
- برای غیر فعال سازی /off را بزنید

با قابلیت جستجوی همسن ، فقط افرادی که سن نزدیک به شما دارند جستجو خواهند شد.

`⚠️ جستجوی همسن باعث افزوده شدن فیلتر سن در جستجو می شود و می تواند باعث دیر پیدا شدن (و یا گاهی پیدا نشدن) مخاطب شما شود.`",null,$msgid,'markdown');
	mysqli_query($ne,"update members set ssen='on' where id=$from");
}
else if($text=="/off"){
	sendmessage($from,"📴 جستجوی همسن برای شما غیرفعال شد. 
- برای فعال سازی /on را بزنید

با قابلیت جستجوی همسن ، فقط افرادی که سن نزدیک به شما دارند جستجو خواهند شد.

`⚠️ جستجوی همسن باعث افزوده شدن فیلتر سن در جستجو می شود و می تواند باعث دیر پیدا شدن (و یا گاهی پیدا نشدن) مخاطب شما شود.`",null,$msgid,'markdown');
	mysqli_query($ne,"update members set ssen='off' where id=$from");
}

else if(preg_match('/^shans-(.+)/',$data,$s)){
	if($sql['chat']){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"⚠️ خطا : هم اکنون شما در حال چت با /user_$use هستید !

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

`برای استفاده از ربات ابتدا باید مکالمه رو قطع کنی 👇`",null,$msgid,'markdown');
	}else if($sql['type']!=$s[1]){
		$s=$s[1];
		
		if($s=="dokh" | $s=="pes" | $s=='at'){
			if($sql['coin'] < 2){
				sendmessage($from,"⚠️تعداد سکه های شما برای جستجو کافی نمی باشند. (تعداد سکه های مورد نیاز برای هر جستجو ۲ تا می باشد.!!) میتوانید از طریق دکمه ″👤 پروفایل″ با کامل کردن مشخصات خود ۵ سکه دریافت کنید.)");
				exit();
			}
		}
		
		
		$o=$sql['ssen']=='on'?'✅ فعال
- غیرفعال کردن : /off':'📴 غیر فعال
- فعال کردن : /on';
		Neman('deletemessage',[
		'chat_id'=>$from,
		'message_id'=>$msgid
		]);
		if($s=='at' and !$sql['lat']){
			sendmessage($from,"انتظار که نداری بدون دونستن موقعیتت بتونم افراد نزدیکتو پیدا کنم؟

⚠️ خطا : شما موقعیت مکانی خود را ثبت نکرده اید.

با زدن گزینه ✏️ ثبت موقعیت GPS  ، موقعیت خود را ثبت کنید 👇",$k_adgps);
			exit;
		}
		
		if($s=='dokh'){
			$mn='🙎‍♀جستجوی دختر';
		}else if($s=='pes'){
			$mn='🙎‍♂جستجوی پسر';
		}else if($s=='at'){
			$mn='🛰 جستجوی اطراف';
		}else{
			$mn='🎲 جستجوی شانسی';
		}
		sendmessage($from,"🔎 درحال جستجوی مخاطب ناشناس شما 
`- $mn `

⏳ حداکثر تا ۲ دقیقه صبر کنید.

⚙️ جستجوی همسن : $o",$k_backed,null,'markdown');
	
	$sen=$sql['ssen']=='on'?" and sen='{$sql['sen']}'":'';
	if($s=='pes' | $s=="dokh"){
		$j=$sql['jens']=='boy'?'pes':'dokh';
		$z=$s=='pes'?'boy':'girl';
		$g=mysqli_fetch_array(mysqli_query($ne,"select user,id,ssen,sen,type from members where (type='$j' or type='sh') and jens='$z'{$sen} and if(ssen='on',sen='{$sql['sen']}',1=1)"));
	}
	else if($s=='sh'){
		$t=$sql['jens']=='boy'?'pes':'dokh';
		$g=mysqli_fetch_array(mysqli_query($ne,"select user,id,ssen,sen,type from members where (type='$s' or type='$t'){$sen} and if(ssen='on',sen='{$sql['sen']}',1=1)"));
	}
	else if($s=='at'){
		$q=mysqli_fetch_array(mysqli_query($ne,"select user,id,lat,lng,type from members where (type='$s' or type='sh') and lat!='' and lng!=''{$sen} and if(ssen='on',sen='{$sql['sen']}',1=1)"));
		$w=str_replace(' km','',where($sql['lat'],$sql['lng'],$q['lat'],$q['lng'],'k'));
		if($w <= 30){
			$g=mysqli_fetch_array(mysqli_query($ne,"select user,id from members where id=".$q['id']));
		}
	}
	
	if($g){
		sendmessage($from,"👀 پیدا کردم وصلتون کردم

به مخاطبت سلام کن 🗣",$k_canc);
	mysqli_query($ne,"update members set step='chat',chat='{$g['id']}',type='' where id=$from");
	mysqli_query($ne,"update members set step='chat',chat='$from',type='' where id='{$g['id']}'");
	sendmessage($g['id'],"👀 پیدا کردم وصلتون کردم

به مخاطبت سلام کن 🗣",$k_canc);
	if($s=='at' | $s=='pes' | $s=='dokh'){
		mysqli_query($ne,"update members set coin=coin-2 where id=$from");
		if($g['type']!='sh')
		mysqli_query($ne,"update members set coin=coin-2 where and id=".$g['id']);
	}else if($s=='sh' and $g['type']!='sh')
		mysqli_query($ne,"update members set coin=coin-2 where id=".$g['id']);
	}else{
		
			mysqli_query($ne,"update members set type='$s' where id=$from");
		
	}
	}else{
		sendmessage($from,"چنبار میزنی؟ دارم برات جستجو میکنم صبر کن");
	}
}

else if(preg_match('/^ch-(gh|na)-(.+)-(.+)/',$data,$ch)){
	$t=$ch[3];
	$f=$ch[2];
	$ty=$ch[1];
	
	if($t < time()-120){
		alert("⚠ خطا : ‌درخواست چت منقضی شده است.",true);
		Neman('deletemesssage',['chat_id'=>$from,'message_id'=>$msgid]);
		exit();
	}
	
	$ge=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where id=$from"));
	if(strpos($ge['banlist'],$from)!==false){
		alert('کاربر شما را مسدود کرده است',true);
		exit;
		}
	$use=$ge['user'];
	if($ty=="na"){
		sendmessage($from,"✅ درخواست چت از طرف /user_$use توسط شما لغو شد.");
		sendmessage($f,"🔔 درخواست چت شما به /user_{$sql['user']} رد شد.");
	}else if($ty=="gh"){
		
		if($ge['chat']){
			editmessage($from,$msgid,"⚠️ خطا : امکان تایید درخواست چت وجود ندارد،کاربر /user_$use درحال چت است .");
			$b=$sql['user'];
			sendmessage($f,"🔔 کاربر /user_$b درخواست چت شما را تایید کرد ، اما چون در حال چت با مخاطب دیگه ای هستی چت وصل نشد.");
			exit();
		}
		mysqli_query($ne,"update members set step='chat',chat='$f' where id=$from");
	mysqli_query($ne,"update members set step='chat',chat='$from',coin=coin-2 where id=$f");
		sendmessage($from,"👀 درخواست چت وصل شد

 به مخاطبت سلام کن 🗣",$k_canc);
		sendmessage($f,"👀 درخواست چت وصل شد

 به مخاطبت سلام کن 🗣",$k_canc);
	}
}

else if($data=="dlp"){
	Neman('deletemessage',['chat_id'=>$from,'message_id'=>$msgid]);
	Neman('deletemessage',['chat_id'=>$from,'message_id'=>$call['message']['reply_to_message_id']['message_id']]);
	alert("🏵 پاک شد.");
}

else if(preg_match('/^rp-(.+)-(.+)/',$sql['step'],$s)){
	if($text){
		$ms=forward($admin,$from,$msgid)->result->message_id;
		$b=nem($s[2]);
		sendmessage($admin,"⚠ شخصی به دلیل <code>$b</code> اقدام به گزارش کرده است.

🚫پیام گزارش با این پیام ریپلی شده است.

🧐می خواهید چه عملی برای این شخص انجام دهید!؟

⁉در صورت تأیید گزارش، می توانید شخصی که گزارش شده را مسدود، یا اختار دهید.
👤همینطور می توانید همین کار را با شخصی که گزارش را ارسال کرده داشته باشید.

برای انجام این کار ها، از دکمه های شیشه ای زیر استفاده کنید.

".$support,json_encode(['inline_keyboard'=>[
		[['text'=>"🚫فردی که گزارش داد",'callback_data'=>"ad-bl-$from"],['text'=>"🚫فردی که گزارش شد",'callback_data'=>"ad-bl-".$s[1]]],
		[['text'=>"⚠اختار به شخصی که گزارش داد",'callback_data'=>"ad-e-$from"],['text'=>"⚠اختار به شخصی که گزارش شد",'callback_data'=>"ad-e-".$s[1]]],
		[['text'=>"مشاهده ی شخص گزارش شده",'callback_data'=>"pr-".$s[1]]],
		[['text'=>"❌بستن پیام",'callback_data'=>"dlp"]]
		]]),$ms,'html');
		sendmessage($from,"✅ با تشکر از همکاری شما، گزارش شما با موفقیت ثبت شد و بزودی بررسی خواهد شد 🌹",$k_start,$msgid);
		mysqli_query($ne,"update members set step='' where id=$from");
	}else{
		sendmessage($from,"⚠️ فرم ارسال گزارش عدم رعایت قوانین به دلیل X

`خب حالا کافیه یه توضیح کوتاه و 《کامل》 درباره گزارشت بفرستی تا ثبتش کنم.
- مثلا : داره تبلیغات فلان کانال رو میکنه.`


برای لغو گزارش 《 🔙 بازگشت 》 را انتخاب کنید 👇",$k_back,$msgid,'markdown');
	}
}
else if(preg_match('/^ad-(.+)-(.+)/',$data,$a)){
	$s=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where id=".$a[2]));
	if($a[1]=='e'){
		mysqli_query($ne,"update members set err=err+1 where id=".$a[2]);
		if($s['err'] >= 8){
			alert('شخص به کل از ربات مسدود شد');
			sendmessage($s['id'],"⚠ شما به دلیل گرفتن اخطار های پی در پی، مسدود شدید و دیگر نمی توانید از ربات استفاده کنید.");
		}else if($s['err'] == 1){
			alert('اولین خطا داده شد.');
			sendmessage($s['id'],"⚠شما یک خطا به دلیل گزارش دریافت کردید.
اگر تعداد این اخطار ها به 8 برسد، شما از ربات مسدود شده و دیگر نمیتوانید از ربات استفاده کنید.");
		}else{
			alert('انجام شد');
			$er=$s['err'];
			sendmessage($s['id'],"⚠شما یک خطای دیگر دریافت کردید.
تعداد خطاهای شما : $er");
		}
	}
	else if($a[1]=='bl'){
		if($s['err'] >= 8){
			alert("⚠این شخص قبلا مسدود شده بود.");
		}else{
			alert("شخص مسدود شد.");
			sendmessage($s['id'],"⚠ شما توسط ادمین مسدود شدید و دیگر نمی توانید از ربات استفاده کنید.");
			mysqli_query($ne,"update members set err=8 where id=".$s['id']);
		}
	}
}

else if(preg_match('/^rp-(.+)-(.+)/',$data,$p)){
	$nem=nem($p[2]);
	sendmessage($from,"⚠️ فرم ارسال گزارش عدم رعایت قوانین به دلیل $nem

`خب حالا کافیه یه توضیح کوتاه و 《کامل》 درباره گزارشت بفرستی تا ثبتش کنم.
- مثلا : داره تبلیغات فلان کانال رو میکنه.`


برای لغو گزارش 《 🔙 بازگشت 》 را انتخاب کنید 👇",$k_back,null,'markdown');
	mysqli_query($ne,"update members set step='rp-{$p[1]}-{$p[2]}' where id=$from");
}

else if(preg_match('/^s-(.+)/',$data,$x)){
	$use=mysqli_fetch_assoc(mysqli_query($ne,"select user from members where id='{$x[1]}'"))['user'];
	$g=strchr($tx,"ــــــــــــــــــــــــــــــــــــــــ");
	
	sendmessage($x[1],"🔔  پیام #دایرکت جدید از طرف /user_{$sql['user']} ، در ".jdate('y/m/d H:i:s')."\n".$g,json_encode(['inline_keyboard'=>[
[['text'=>"📨 ارسال پاسخ",'callback_data'=>"cha-dl-$from"]]
]]));
	editmessage($from,$msgid,"✅ پیام #دایرکت شما به /user_$use در ".jdate('y/m/d H:i:s')." ارسال شد.\n".$g);
	mysqli_query($ne,"update members set coin=coin-1 where id=$from");
}

else if(preg_match('/^bikh-(.+)/',$data,$s)){
	$use=mysqli_fetch_assoc(mysqli_query($ne,"select user where id='{$s[1]}'"))['user'];
	editmessage($from,$msgid,"✅ ارسال پیام دایرکت لغو شد");
	if(strpos($sql['step'],"dl-")!==false){
		mysqli_query($ne,"update members set step='' where id=$from");
	}
}

	else if(preg_match('/pr-(.+)/',$data,$z) & $from==$admin){
		$a=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='{$z[1]}'"));
		$photo=$a['photo']?$a['photo']:"https://tlgur.com/d/BGWWYqbG";
	$name=$a['name']?$a['name']:'❓';
	$city=$a['city']?$a['city']:'❓';
	$jens=$a['jens']=='boy'?"🙎‍♂️ پسر":"🙍‍♀ دختر";
		$on=$a['online'];
		
		
		if($on >= time()-60){
			$onl="👀 آنلایـــن";
		}else if($on >= time()-3600){
			$onl="چقد دقیقه قبل";
		}else if($on >= time()-86400){
			$onl="چند ساعت قبل";
		}else{
			$onl="چند روز قبل";
		}
		
		$sen=$a['sen'];
		
		$use=$a['user'];
		
		$where=where($a['lat'],$a['lng'],$sql['lat'],$sql['lng'],'k');
		if($a['lat'] & $sql['lat']){
			$nem=$where;
		}else{
			$nem='نا معلوم';
		}
		
		sendphoto($admin,$photo,"• نام: $name
• جنسیت: $jens
• شهر: $city
• سن: $sen

هم اکنون $onl

‏🆔 آیدی : /user_$use

🏁 فاصله از شما : $nem");
	}

else if(preg_match('/^dl-(.+)/',$sql['step'],$s)){
		$use=mysqli_fetch_assoc(mysqli_query($ne,"select user from members where id='{$s[1]}'"))['user'];
	if($text){
		$t=mb_substr($text,0,200);
		sendmessage($from,"📜 پیش نمایش پیام دایرکت شما به /user_$use 
ــــــــــــــــــــــــــــــــــــــــ
$t",json_encode(['inline_keyboard'=>[
[['text'=>"📤 ارسال",'callback_data'=>"s-{$s[1]}"],['text'=>"بیخیال",'callback_data'=>"back2-".$s[1]]]
]]));
	mysqli_query($ne,"update members set step='' where id=$from");
	}else{
		sendmessage($from,"⚠️خطا : پیام دایرکت فقط باید بصورت متن باشد (حداکثر 200 حرف)

- برای لغو ارسال پیام دایرکت به /user_$use 《بیخیال》 را لمس کنید",
json_encode(['inline_keyboard'=>[
[['text'=>"بیخیال",'callback_data'=>"bikh-".$s[1]]]
]])
);
	}
}

else if(preg_match('/^cha-(.+)-(.+)$/',$data,$ch)){
	$am=$ch[1];
	$id=$ch[2];
	$get=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where id='$id'"));
	if(($am=='dl' | $am=='ch') & in_array($from,explode(',',$get['banlist']))){
		alert("⚠ خطا : شما توسط این کاربر بلاک شده اید.",true);
		exit();
	}
	if($am=='bl'){
	if(!in_array($id,explode(",",$sql['banlist']))){
		alert("✅ کاربر بلاک شد.

این کاربر امکان ارسال درخواست چت و پیام دایرکت به شما را نخواهد داشت.",true);
	$st=$sql['banlist'].$id.",";
		mysqli_query($ne,"update members set banlist='$st' where id=$from");
		markup(json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$id],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$id]],
[['text'=>"🔐 آنبلاک کردن کاربر",'callback_data'=>"cha-ub-".$id],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$id]]
]]));
	exit();
	}else{
		alert("✅ کاربر قبلا توسط شما بلاک شده بود.",true);
		markup(json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$id],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$id]],
[['text'=>"🔐 آنبلاک کردن کاربر",'callback_data'=>"cha-ub-".$id],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$id]]
]]));
	exit();
	}
}
	if($am=="ub"){
	if(in_array($id,explode(",",$sql['banlist']))){
		alert("✅ کاربر آنبلاک شد.

این کاربر امکان ارسال درخواست چت و پیام دایرکت به شما را خواهد داشت.",true);
$st=str_replace($id.',',null,$sql['banlist']);
		mysqli_query($ne,"update members set banlist='$st' where id=$from");
		markup(json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$id],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$id]],
[['text'=>"🔒بلاک کردن کاربر",'callback_data'=>"cha-bl-".$id],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$id]]
]]));
	exit();
	}else{
		alert("✅ کاربر بلاک نیست.",true);
		markup(json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$id],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$id]],
[['text'=>"🔒بلاک کردن کاربر",'callback_data'=>"cha-bl-".$id],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$id]]
]]));
	exit();
	}
	}

	$get=mysqli_fetch_array(mysqli_query($ne,"select * from members where id=$id"));
	$use=$get['user'];
	if(strpos($get['banlist'],$from)!==false)exit(sendmessage($from,"شما توسط کاربر مسدود شده اید"));
	if($am=="dl"){
		if($sql['coin']>=1){
sendmessage($from,"متن پیام دایرکت خود را ارسال کنید (حداکثر 200 حرف)

<code>- برای لغو ارسال پیام دایرکت به</code> /user_$use <code>《بیخیال》 را لمس کنید</code>",json_encode(['inline_keyboard'=>[
[['text'=>"بیخیال",'callback_data'=>"bikh-$id"]]
]]),null,'html');
	mysqli_query($ne,"update members set step='dl-$id' where id=$from");
	exit();
		}else{
			sendmessage($from,"برای ارسال پیام دایرکت، شما باید حداقل یک سکه داشته باشید.");
			exit;
		}
	}
	
	if($am=="ch"){
		if($sql['chat']){
			alert("⚠ خطا : امکان ارسال درخواست چت هنگامی که درحال چت هستید وجود ندارد.",true);
			exit();
		}
		if(strpos($get['banlist'],$from)!==false)exit(alert('کاربر شمارا مسدود کرده است.',true));
		
	if(preg_match('/ent-(.+)/',$sql['step'],$ent)){
			if($ent[1] > time()-120){
				alert("⚠ خطا : شما در 2 دقیقه اخیر یک درخواست چت به این کاربر ارسال کرده اید.",true);
				exit();
		}
		}
		
		if($get['online'] < time()-900){
			alert("⚠ خطا : در حال حاضر فقط امکان ارسال درخواست چت به کاربرانی که در 15 دقیقه اخیر آنلاین بوده اند وجود دارد.

 در حال حاضر می توانید برای کاربر 📨پیام دایرکت ارسال نمایید.",true);
 		exit();
		}
		
		if($get['chat']){
				alert("⚠ خطا : امکان ارسال درخواست چت وجود ندارد.

کاربر در حال چت است.",true);
				exit();
		}
		if($sql['coin']<2){
			sendmessage($from,"برای ارسال درخواست چت، حداقل باید دو سکه داشته باشید");
			exit;
		}
		
		sendmessage($from,"✅ درخواست چت شما برای /user_$use ارسال شد.

🚶منتظر باش و اگه تا دو دقیقه تایید نکرد درخواست چت لغو میشه...");
$us=$sql['user'];
	
	sendmessage($id,"🔔 درخواست چت از طرف /user_$us 

<code>- شما تا دو دقیقه پس از ارسال این پیام میتوانید درخواست چت را تایید کنید.</code>",json_encode(['inline_keyboard'=>[
	[['text'=>"✅ قبول درخواست",'callback_data'=>"ch-gh-".$from."-".time()],['text'=>"👎 رد درخواست",'callback_data'=>"ch-na-".$from."-".time()]]
	]]),null,'html');
	
	mysqli_query($ne,"update members set step='ent-".time()."' where id=$from");
	exit();
	}
	
	if($am=='rp'){
		
		if($sql['chat']){
			alert("⚠ امکان گزارش کاربر هنگام چت وجود ندارد.",true);
			exit();
		}
		
		sendmessage($from,"⚠️ فرم ارسال گزارش عدم رعایت قوانین

چرا میخوای /user_$use رو گزارش کنی؟ 

- توجه : تمامی گزارشات بررسی خواهند شد و 🔴 ارسال گزارشات اشتباه موجب مسدود شدن شما خواهد شد.

انتخاب کن 👇",
json_encode(['inline_keyboard'=>[
[['text'=>"تبلیغات سایت ها ربات ها و کانال ها",'callback_data'=>"rp-$id-1"]],
[['text'=>"ارسال محتوای غیر اخلاقی در چت",'callback_data'=>"rp-$id-2"]],
[['text'=>"ایجاد مزاحمت",'callback_data'=>"rp-$id-3"]],
[['text'=>"پخش شماره موبایل یا اطلاعات شخصی دیگران",'callback_data'=>"rp-$id-4"]],
[['text'=>"جنسیت اشتباه در پروفایل",'callback_data'=>"rp-$id-5"]],
[['text'=>"دیگر موارد...",'callback_data'=>"rp-$id-6"]]
]]));
		
	}
	
}


else if($text=="◻️ مشاهده پروفایل این مخاطب ◻️" & is_numeric($sql['chat'])){
	if($sql['va'] != 0 or ($sql['jens'] and $sql['sen'] and $sql['city'] and $sql['photo'] and $sql['name'] and $sql['lat'])){
		
		$a=mysqli_fetch_array(mysqli_query($ne,"select * from members where id=".$sql['chat']));
		
		$photo=$a['photo']?$a['photo']:"https://tlgur.com/d/BGWWYqbG";
	$name=$a['name']?$a['name']:'❓';
	$city=$a['city']?$a['city']:'❓';
	$jens=$a['jens']=='boy'?"🙎‍♂️ پسر":"🙍‍♀ دختر";
		$on=$a['online'];
		
		
		if($on >= time()-60){
			$onl="👀 آنلایـــن";
		}else if($on >= time()-3600){
			$onl="چقد دقیقه قبل";
		}else if($on >= time()-86400){
			$onl="چند ساعت قبل";
		}
		
		$sen=$a['sen'];
		
		$use=$a['user'];
		
		$where=where($a['lat'],$a['lng'],$sql['lat'],$sql['lng'],'k');
		if($a['lat'] & $sql['lat']){
			$nem=$where;
		}else{
			$nem='نا معلوم';
		}
		
		sendphoto($from,$photo,"• نام: $name
• جنسیت: $jens
• شهر: $city
• سن: $sen

هم اکنون $onl

‏🆔 آیدی : /user_$use

🏁 فاصله از شما : $nem",$k_cha);
		
		if($sql['va']!=0){
			mysqli_query($ne,"update members set va=va-1 where id=$from");
		}
		sendmessage($sql['chat'],"🤖 پیام سیستم 👇

مخاطب شما «پروفایل ِ زومیت چت» شما را مشاهده کرد.");
	}else{
		$tk=tk();
		sendmessage($from,"⚠️ خطا : برای مشاهده پروفایل کاربران دیگر باید پروفایل شما تکمیل شده باشد. 

اطلاعات تکمیل نشده ی شما :  $tk",$k_pro,$msgid);
	}
}

else if($text=="قطع مکالمه" & is_numeric($sql['chat'])){
	sendmessage($from,"🤖 پیام سیستم 👇

مطمئنی می‌خوای این گپ رو ببندی؟",$k_et,$msgid);
}

else if(preg_match('/^m-(.+)/',$data,$gh)){
	$gh=$gh[1];
	Neman('deletemessage',[
		'chat_id'=>$from,
		'message_id'=>$msgid
	]);
	if($gh=='ye'){
		$use=mysqli_fetch_array(mysqli_query($ne,"select user from members where id=".$sql['chat']))['user'];
		sendmessage($from,"چت شما با /user_$use توسط شما قط شد

برای گزارش عدم رعایت قوانین (/help_terms) می توانید با لمس 《 🚫 گزارش کاربر 》 در پروفایل، کاربر را گزارش کنید.",$k_start);

	$use2=$sql['user'];

	sendmessage($sql['chat'],"چت شما با /user_$use2 توسط مخاطب شما قط شد

برای گزارش عدم رعایت قوانین (/help_terms) می توانید با لمس 《 🚫 گزارش کاربر 》 در پروفایل، کاربر را گزارش کنید.",$k_start);

	mysqli_query($ne,"update members set step='',chat='' where id=$from");
	mysqli_query($ne,"update members set step='',chat='' where id=".$sql['chat']);

	}
}


else if(preg_match('/(add)|(^\/link)|(🚸 معرفی به دوستان \( سکه رایگان\))$/siu',$teda)){
	sendphoto($from,"https://tlgur.com/d/9gwbQyyG","《زومیت چت 🤖》 هستم،بامن میتونی

📡 افراد #نزدیک خودتو پیداکنی و باهاشون آشنا شی

💬 به صورت #ناشناش با یک نفر چت کنی

همین الان روی لینک بزن  👇
http://t.me/$usbot?start={$sql['user']}

✅ #رایگان و #واقعی 😎");
	sleep(2);
	sendmessage($from,"لینک⚡️ دعوت شما با موفقیت ساخته شد 👆

شما میتوانید بنر حاوی لینک⚡️ خود را به گـــروه ها و دوستان خود ارسال کنید

- با معرفی هر نفر 8 سکه بگیرید ! برای اطلاعات بیشتر راهنمای سکه (/help_credit) را بخوانید.

👈 شما تاکنون {$sql['coad']} نفر را به این ربات دعوت کرده اید .",null,$msgid+1);
}

else if($data=="vigps"){
	if($sql['chat']){
		alert("⚠️ خطا : ویرایش پروفایل هنگام چت امکان پذیر نمی باشد.

لطفا بعد از اتمام چت دوباره امتحان کنید.",true);
	}else if(!$sql['lat']){
		sendmessage($from,"انتظار که نداری بدون دونستن موقعیتت بتونم افراد نزدیکتو پیدا کنم؟

⚠️ خطا : شما موقعیت مکانی خود را ثبت نکرده اید.

با زدن گزینه ✏️ ثبت موقعیت GPS  ، موقعیت خود را ثبت کنید 👇",$k_adgps,$msgid);
	}else if($sql['lat']){
		Neman('sendlocation',[
		'chat_id'=>$from,
		'latitude'=>$sql['lat'],
		'longitude'=>$sql['lng'],
		'reply_markup'=>$k_adgps,
		'reply_to_message_id'=>$msgid
		]);
	}
}
else if(preg_match('/^edit-(.+)/i',$sql['step'],$step)){
	$s=$step[1];
	
	if($s=="gps"){
		if($lat){
			mysqli_query($ne,"update members set lat='$lat',lng='$lng' where id=$from");
			$ty='موقعیت GPS';
		}else{
			sendmessage($from,"⚠️ خطا : ورودی باید بصورت لوکیشن GPS باشد.",json_encode(['inline_keyboard'=>[[['text'=>"بیخیال ✏️ تغییر موقعیت GPS",'callback_data'=>"back"]]]]),$msgid);
			exit();
		}
	}else if($s=="photo"){
		if($message['photo']){
			mysqli_query($ne,"update members set photo='{$message['photo'][2]['file_id']}' where id=$from");
			$ty='تصویر';
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت عکس باشد.",json_encode(['inline_keyboard'=>[[['text'=>"بیخیال ✏️ تغییر عکس",'callback_data'=>"back"]]]]),$msgid);
			exit();
		}
	}else if($s=="name"){
		if($text){
		mysqli_query($ne,"update members set name='$text' where id=$from");
		$ty='نام';
	}else{
		sendmessage($from,"⚠️ ارور : ورودی باید بصورت متن باشد.",json_encode(['inline_keyboard'=>[[['text'=>"بیخیال ✏️ تغییر نام",'callback_data'=>"back"]]]]),$msgid);
		exit();
		}
	}else if($s=="city"){
		if($text){
			$ty='شهر';
			mysqli_query($ne,"update members set city='$text' where id=$from");
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت متن باشد.",json_encode(['inline_keyboard'=>[[['text'=>"بیخیال ✏️ تغییر شهر",'callback_data'=>"back"]]]]),$msgid);
			exit();
		}
	}else if($s=="sen"){
		if($text >= 9 & $text <= 99){
			mysqli_query($ne,"update members set sen='".floor($text)."' where id=$from");
			$ty='سن';
		}else{
			sendmessage($from,"⚠️ خطا : فقط عددی بین 9 تا 99 ارسال کنیدـ",json_encode(['inline_keyboard'=>[[['text'=>"بیخیال ✏️ تغییر سن",'callback_data'=>"back"]]]]),$msgid);
			exit();
		}
	}
	$sql=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$from'"));
	if(!$sql['tr'] & empty(tk())){
		if($sql['added']){
			mysqli_query($ne,"update members set coin=coin+5 where user='{$sql['added']}'");
			$f=mysqli_fetch_assoc(mysqli_query($ne,"select id,coin from memebrs where user='{$sql['added']}'"));
			$co=$f['coin'];
			$a=$f['id'];
			sendmessage($a,"🔔 تبریک ! شما 5 سکه بابت تکمیل شدن پروفایل کاربری که توسط شما معرفی شده بود دریافت کردید.
💰سکه فعلی شما : $co",$k_added);
		}
		mysqli_query($ne,"update members set coin=coin+5,tr='ok' where id=$from");
		$co=$sql['coin']+5;
		sendmessage($from,"🔔 تبریک ! 

شما 5 سکه بابت تکمیل کردن پروفایل دریافت کردید !

💰سکه فعلی شما : $co",$k_start);
	}else{
	sendmessage($from,"✏️ تغییر $ty با موفقیت انجام شد ☑️

 خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

از منوی پایین👇 انتخاب کن",$k_start,$msgid,'markdown');
	}
	mysqli_query($ne,"update members set step='' where id=$from");
}

else if(preg_match('/edit-jens-(.+)/',$data,$j)){
	mysqli_query($ne,"update members set jens='{$j[1]}',step='' where id=$from");
	sendmessage($from,"✏️ تغییر جنسیت با موفقیت انجام شد ☑️

 خب ، حالا چه کاری برات انجام بدم؟ 

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

از منوی پایین👇 انتخاب کن",$k_start,$msgid,'markdown');
}

else if($sql['step']=='chjens'){
	sendmessage($from,"⚠ خطا : فقط از دکمه های زیر برای تعیین جنسیت خود استفاده کنید.",json_encode(['inline_keyboard'=>[
[['text'=>"من 🙎‍♂ پسرم",'callback_data'=>"edit-jens-boy"],['text'=>"من 🙍‍♀دخترم",'callback_data'=>"edit-jens-girl"]]
]]));
}

else if(preg_match('/^edit-(.+)/si',$data,$edit)){
	if($sql['chat']){
		alert("⚠️ خطا : ویرایش پروفایل هنگام چت امکان پذیر نمی باشد.

لطفا بعد از اتمام چت دوباره امتحان کنید.",true);
	}
	Neman('deletemessage',[
	'chat_id'=>$from,
	'message_id'=>$msgid
	]);
	$e=$edit[1];
	mysqli_query($ne,"update members set step='edit-{$e}' where id=$from");
	if($e=='name'){
		sendmessage($from,"❓ لطفا نام خود را به صورت متن ارسال کنید .");
	}else if($e=='photo'){
		sendmessage($from,"❓ لطفا عکس پروفایل خود را ارسال کنید.");
	}else if($e=="city"){
		sendmessage($from,"❓ لطفا نام شهر خود را به صورت متن ارسال کنید .");
	}else if($e=="jens"){
		sendmessage($from,"❓ لطفا جنسیت خود را انتخاب کنید 👇",json_encode(['inline_keyboard'=>[
[['text'=>"من 🙎‍♂ پسرم",'callback_data'=>"edit-jens-boy"],['text'=>"من 🙍‍♀دخترم",'callback_data'=>"edit-jens-girl"]]
]]));
	mysqli_query($ne,"update members set step='chjens' where id=$from");
	}else if($e=="sen"){
		sendmessage($from,"❓لطفا عدد سن خود را ارسال کنید 
مثلا : 22");
	}else if($e=="gps"){
		sendmessage($from,"⚠️ هنگام ارسال موقعیت مکانی مطمعن شوید GPS موبایل شما روشن است.

✅ کسی قادر به دیدن موقعیت مکانی شما در ربات نخواهد بود و فقط برای تخمین فاصله و یافتن افراد نزدیک کاربرد خواهد داشت

📚 گیف آموزشی ارسال موقعیت GPS با 2 روش متفاوت ( کلیک کنید)

❓موقعیت GPS خود را ارسال کنید👇",$k_gps);
	mysqli_query($ne,"update members set step='edit-gps' where id=$from");
	}
}

else if($text=="/profile" | $text=="👤 پروفایل" | $text=="/user_".$sql['user']){
	$photo=$sql['photo']?$sql['photo']:"https://tlgur.com/d/BGWWYqbG";
	$name=$sql['name']?$sql['name']:'❓';
	$city=$sql['city']?$sql['city']:'❓';
	$jens=$sql['jens']=='boy'?"🙎‍♂️ پسر":"🙍‍♀ دختر";
	sendphoto($from,$photo,"• نام: $name
• جنسیت: $jens
• شهر: $city
• سن: {$sql['sen']}

هم اکنون 👀 آنلایـــن

‏🆔 آیدی : /user_{$sql['user']}

‌",$k_pro1,$msgid);
	if(!$sql['name'] | !$sql['photo'] | !$sql['lat'] | !$sql['city']){
		$c=0;
		if(!$sql['name']){
			$c++;
		}
		if(!$sql['photo']){
			$c++;
		}
		if(!$sql['lat']){
			$c++;
		}
		if(!$sql['city']){
			$c++;
		}
		$tk=tk();
		sendmessage($from,"🔔 فقط $c قدم تا تکمیل پروفایل !

اطلاعات تکمیل نشده ی شما :  $tk

پروفایل خود را تکمیل کنید👇 و 5 امتیاز دریافت کنید .",$k_pro);
	}
}

else if($data=='tkpro' && !empty(tk())){
	if($sql['chat']){
		alert("⚠ خطا : امکان تکمیل پروفایل در هنگام چت وجود ندارد.",true);
		exit();
	}
	if(!$sql['name']){
		sendmessage($from,"❓ لطفا نام خود را به صورت متن ارسال کنید .",$k_back);
		$t='name';
	}
	else if(!$sql['photo']){
		sendmessage($from,"❓ لطفا عکس پروفایل خود را ارسال کنید.",$k_back);
		$t='photo';
	}
	else if(!$sql['city']){
		sendmessage($from,"❓ لطفا نام شهر خود را به صورت متن ارسال کنید .",$k_back);
		$t='city';
	}
	else if(!$sql['lat']){
		sendmessage($from,"❓ لطفا مکان خود را با استفاده از دکمه ی زیر ارسال کنید.",json_encode(['keyboard'=>[
[['text'=>"📌 ارسال مکان",'request_location'=>true]],
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true]));
		$t='lat';
	}
	mysqli_query($ne,"update members set step='tk-$t' where id=$from");
}
else if(preg_match('/tk-(.+)/',$sql['step'],$o)){
	$h=$o[1];
	if($h=='name'){
		if($text){
			mysqli_query($ne,"update members set name='$text' where id=$from");
			if(!$sql['photo']){
				sendmessage($from,"❓ لطفا عکس پروفایل خود را ارسال کنید.",$k_back);
				$t='photo';
			}else if(!$sql['city']){
				sendmessage($from,"❓ لطفا نام شهر خود را به صورت متن ارسال کنید .",$k_back);
				$t='city';
			}else if(!$sql['lat']){
				sendmessage($from,"❓ لطفا مکان خود را با استفاده از دکمه ی زیر ارسال کنید.",
json_encode(['keyboard'=>[
[['text'=>"📌 ارسال مکان",'request_location'=>true]],
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true])
);
				$t='lat';
			}
			mysqli_query($ne,"update members set step='tk-$t' where id=$from");
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت متن باشد.",null,$msgid);
		}
	}
	else if($h=='photo'){
		if($message['photo']){
			mysqli_query($ne,"update members set photo='{$message['photo'][2]['file_id']}' where id=$from");
		if(!$sql['city']){
				sendmessage($from,"❓ لطفا نام شهر خود را به صورت متن ارسال کنید .",$k_back);
				$t='city';
			}else if(!$sql['lat']){
				sendmessage($from,"❓ لطفا مکان خود را با استفاده از دکمه ی زیر ارسال کنید.",
json_encode(['keyboard'=>[
[['text'=>"📌 ارسال مکان",'request_location'=>true]],
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true])
);
				$t='lat';
			}
			mysqli_query($ne,"update members set step='tk-$t' where id=$from");
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت عکس باشد.",null,$msgid);
		}
	}
	else if($h=='city'){
		if($text){
			mysqli_query($ne,"update members set city='$text' where id=$from");
		if(!$sql['lat']){
				sendmessage($from,"❓ لطفا مکان خود را با استفاده از دکمه ی زیر ارسال کنید.",
json_encode(['keyboard'=>[
[['text'=>"📌 ارسال مکان",'request_location'=>true]],
[['text'=>"🔙 بازگشت"]]
],'resize_keyboard'=>true])
);
				$t='lat';
			}
			mysqli_query($ne,"update members set step='tk-$t' where id=$from");
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت متن باشد.",null,$msgid);
		}
	}
	else if($h=='lat'){
		if($lat){
			mysqli_query($ne,"update members set lat='$lat', lng='$lng' where id=$from");
		}else{
			sendmessage($from,"⚠️ ارور : ورودی باید بصورت مکان باشد.",null,$msgid);
		}
	}
	$sql=mysqli_fetch_assoc(mysqli_query($ne,"select * from members where id='$from'"));
	if(!$sql['tr'] & empty(tk())){
		if($sql['added']){
			mysqli_query($ne,"update members set coin=coin+5 where user='{$sql['added']}'");
			$f=mysqli_fetch_assoc(mysqli_query($ne,"select id,coin from members where user='{$sql['added']}'"));
			$co=$f['coin'];
			$a=$f['id'];
			sendmessage($a,"🔔 تبریک ! شما 5 سکه بابت تکمیل شدن پروفایل کاربری که توسط شما معرفی شده بود دریافت کردید.
💰سکه فعلی شما : $co",$k_added);
		}
		mysqli_query($ne,"update members set coin=coin+5,tr='ok',step='' where id=$from");
		$co=$sql['coin']+5;
		sendmessage($from,"🔔 تبریک ! 

شما 5 سکه بابت تکمیل کردن پروفایل دریافت کردید !

💰سکه فعلی شما : $co",$k_start);
	}
}

else if($text=="💰 سکه" | $text=="/credit"){
	sendmessage($from,"💰سکه فعلی شما : {$sql['coin']}
ــــــــــــــــــــــــــــــــــــــــ
❓روش بدست آوردن سکه چیست؟

1️⃣ معرفی دوستان (رایگان) :

برای افزایش سکه به صورت رایگان بنر لینک⚡️ مخصوص خودت (/link) رو برای دوستات  بفرست و 8 سکه دریافت کن",$k_add,$msgid);
}

else if($text=="/help_credit"){
	sendmessage($from,"🔹 سکه یا امتیاز چیست؟ 

شما با داشتن سکه میتوانید :

`- پیام دایرکت بفرستید (1سکه)
- درخواست چت بفرستید(2سکه)
- از جستجوی پسر  یا جستجوی دختر  استفاده کنید(2سکه)`

📢 توجه : سکه فقط در صورتی کسر می شود که درخواست موفق باشد ( مثلا درخواست چت شما توسط کاربر مقابل پذیرفته شود )

❓روش بدست آوردن سکه چیست؟

1️⃣ معرفی دوستان (رایگان) :
[‌](http://s8.picofile.com/file/8323699634/Capturex.PNG)
برای افزایش سکه به صورت رایگان بنر لینک⚡️ مخصوص خودت (/link) رو برای دوستات  بفرست و 8 سکه دریافت کن

`- به ازای هرنفری که با لینک⚡️ شما وارد ربات میشه به محض ورود  3 تا سکه رایگان دریافت میکنی و بعد از اینکه اطلاعات پروفایــــــلش رو کامل کرد 5 تا سکه دیگه هم دریافت میکنی😎 (3+5=8)`

‌

🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}
else if($text=="/help_terms"){
	sendmessage($from,"🚫 قوانین استفاده از زومیت چت

موارد زیر باعث مسدود شدن دائمی کاربر خواهد شد 

1️⃣ تبلیغات سایت ها ربات ها و کانال ها 

2️⃣ ارسال هرگونه محتوای غیر اخلاقی

3️⃣ ایجاد مزاحمت برای کاربران 

4️⃣ پخش شماره موبایل یا اطلاعات شخصی دیگران

5️⃣ استفاده از کلمات یا عکس غیر اخلاقی و یا توهین آمیز در پروفایل زومیت چت

6️⃣ ثبت جنسیت اشتباه در پروفایل

7️⃣ تهدید و جا زدن خود بعنوان مدیر ربات یا پلیس فتا !

برای گزارش عدم رعایت قوانین می توانید با لمس 《 🚫 گزارش کاربر 》 در پروفایل، کاربر را گزارش کنید.

🔸 - ‏ راهنما : /help",null,$msgid);
}
else if($text=="/help" | $text=="🤔 راهنما"){
	sendmessage($from,"<code>من اینجام که کمکت کنم! برای دریافت راهنمایی در مورد هر موضوع، کافیه دستور آبی رنگی که مقابل اون سوال هست رو لمس کنی:</code>

🔸 - ‏ چگونه بصورت ناشناس چت کنم؟ /help_chat

🔸 -‏ سکه یا امتیاز چیست؟ /help_credit

🔸 - ‏ چگونه افراد نزدیکمو پیدا کنم؟ /help_gps

🔸 - ‏ پروفایل چیست؟ /help_profile

🔸 - ‏ چگونه درخواست چت بفرستم؟ /help_pchat

?? - ‏ پیام دایرکت چیست؟ /help_direct

🔸 -‏ چگونه با \"میان بر\" ها کار کنم؟ /help_shortcuts

🔸 - ‏ 🚫 قوانین استفاده از ربات /help_terms

".$support,null,$msgid,'html');

//👨‍💻 ارتباط با پشتیبانی ربات : 
}
else if($text=="/help_chat"){
	sendmessage($from,"🔹 چگونه بصورت ناشناس چت کنم؟

فقط کافیه تو منوی ربات یکی از گزینه هارو انتخاب کنی : 

`- جستجوی شانسی : بصورت تصادفی به یک نفر وصل میشی (بدون نیاز به سکه)

- جستجوی اطراف : بصورت تصادفی به یک نفر که نزدیکته وصل میشی (بدون نیاز به سکه)

- جستجوی پسر : بصورت تصادفی به یک پسر وصل میشی ( ۲ سکه )

- جستجوی دختر : بصورت تصادفی به یک دختر وصل میشی ( ۲ سکه )`

⚠️ اطلاعات شخصی شما مثل موقعیت GPS یا اسم شما در تلگرام یا عکس پروفایل و.. کاملا مخفی هست و فقط اطلاعاتی که تو ربات ثبت میکنید مانند شهر و عکس(توی ربات) برای کاربرای ربات قابل مشاهده هست.

‌[‌](http://s9.picofile.com/file/8323699726/Capturex.PNG)

🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}

else if($text=="/help_gps"){
	sendmessage($from,"🔹چگونه افراد نزدیکمو پیدا کنم؟

برای دیدن لیست افراد نزدیکت فقط کافیه 《📍پیدا کردن افراد نزدیک با GPS》 رو لمس کنی.
 
- جستجوی افراد نزدیک کاملا رایگان هست (بدون نیاز به سکه)


`برای مشاهده کردن و یا چت کردن با افراد نزدیکت کافیه توی لیست روی آیدی شون بزنی تا پروفایلشونو ببینی.`


📢 توجه : امکان مشاهده موقعیت کاربران وجود ندارد و فقط فاصله آنها نمایش داده می شود.
‌
[‌](http://s8.picofile.com/file/8323699968/Capturex.PNG)
🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}

else if($text=="/help_profile"){
	sendmessage($from,"🔹پروفایل چیست؟

- برای دیدن پروفایل خودت کافیه 《👤 پروفایل》 رو لمس کنی.
- برای دیدن پروفایل کسی که باهاش چت میکنی کافیه 《◻️ مشاهده پروفایل این مخاطب ◻️ 》 رو لمس کنی.

- برای دیدن پروفایل هرکاربر کافیه روی آیدیش تو ربات بزنی.


`📢 آیدی چیست؟ کد اختصاصی هر کاربر که با زدن آن پروفایل کاربر نمایش داده میشود و به صورت /user_X است.`
 
- پروفایل هر کاربر شامل اطلاعاتی که تو ربات ثبت کرده (نام،سن،جنسیت،شهر،عکس) و تاریخ حضورش تو ربات و فاصلش با شمامیشه.

برای ارسال پیام دایرکت یا درخواست چت برای هر کاربر ابتدا باید پروفایلش رو مشاهده کنی و سپس دکمه پیام دایرکت یا درخواست چت رو بزنی.
‌[‌](http://s8.picofile.com/file/8323700284/Capturex.PNG)

🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}
else if($text=='/help_pchat'){
	sendmessage($from,"🔹چگونه درخواست چت بفرستم؟

برای ارسال درخواست چت به کاربران باید گزینه 《💬 درخواست چت》 رو در پروفایل کاربر لمس کنی. 

` - با ارسال درخواست چت تا وقتی که تایید نشده ازتون سکه ای کم نمیشه،درخواست چت وصل شد 2 سکه ازتون کم میشه. `

 📢 توجه : امکان ارسال درخواست چت فقط برای کاربرانی که در 15 دقیقه اخیر آنلاین بوده اند وجود دارد.
‌
[‌](http://s9.picofile.com/file/8323700392/Capturex.PNG)
🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}
else if($text=="/help_shortcuts"){
	sendmessage($from,"🔹 چگونه با \"میان بر\" ها کار کنم؟

میانبر به شما امکان استفاده آسان و سریع از ربات رو میده !

فقط کافیه وقتی توی ربات حرف 《 / 》 رو تایپ کنی تا لیست اصلی ترین میانبر ها رو ببینی.
 
لیست میانبر های ربات 👇

/start - ♻️ شروع از اول
/sr - 🎲جستجوی شانسی  
/sg - 🙍جستجوی دختر  
/sb - 🙎‍♂️جستجوی پسر 
/fn - 📍پیدا کردن افراد نزدیک با GPS   
/link - 💯ساخت لینک اختصاصی من
/profile - 👤 پروفاـــــــیل من
/credit -💰سکه های من
/help - 🤔 راهنما
/id- مشاهده آیدی من

‌
💥 تو عکس پایین میتونی ببینی که با تایپ کردن حرف \"/\" توی ربات لیست اصلی ترین میانبر هارو ببینی 👇
[‌](http://s9.picofile.com/file/8323699484/Capturex.PNG)
🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}

else if($text=="/help_direct"){
	sendmessage($from,"🔹پیام دایرکت چیست؟

با پیام دایرکت میتونی بصورت آنی به کاربر پیام متنی ارسال بکنی حتی اگه درحال چت کردن باشه !

فقط کافیه وقتی پروفایل کاربر رو مشاهده میکنی روی گزینه 《📨 پیام دایرکت》 بزنی و متن پیامتو بفرستی.
 
- درصورت ارسال پیام دایرکت 1 سکه ازت کم میشه 
- این پیام همون لحظه ارسال میشه و بعدا تو ربات آرشیو نمیشه.

📢 توجه : متن پیام حداکثر میتونه 200 حرف باشه و اگه متنی که ارسال میکنی بیشتر از 200 حرف بود فقط 200 حرف اولش ارسال میشه.

(سورس خونه)[https://t.me/Source_Home]

💥 قابلیت ویژه پیام دایرکت : درصورتی که کاربر دریافت کننده ، ربات را بلاک کرده باشد پیام دایرکت به محض آنبلاک شدن کاربر به او ارسال میگردد تا حتما پیام دایرکت را مشاهده کند.
‌[‌](http://s8.picofile.com/file/8323699850/Capturex.PNG)

🔸 - ‏ راهنما : /help",null,$msgid,'markdown');
}
else if($text=="/id"){
	sendmessage($from,"/user_".$sql['user']);
}

else if(preg_match('/^\/user_(.+)/',$text,$d)){
		$a=mysqli_fetch_array(mysqli_query($ne,"select * from members where user='{$d[1]}'"));
		if($a){
		$photo=$a['photo']?$a['photo']:"https://tlgur.com/d/BGWWYqbG";
	$name=$a['name']?$a['name']:'❓';
	$city=$a['city']?$a['city']:'❓';
	$jens=$a['jens']=='boy'?"🙎‍♂️ پسر":"🙍‍♀ دختر";
		$on=$a['online'];
		
		
		if($on >= time()-60){
			$onl="👀 آنلایـــن";
		}else if($on >= time()-3600){
			$onl="چقد دقیقه قبل";
		}else if($on >= time()-86400){
			$onl="چند ساعت قبل";
		}else{
			$onl="چند روز قبل";
		}
		
		$sen=$a['sen'];
		
		$use=$a['user'];
		
		$where=where($a['lat'],$a['lng'],$sql['lat'],$sql['lng'],'k');
		if($a['lat'] & $sql['lat']){
			$nem=$where;
		}else{
			$nem='نا معلوم';
		}
		
		if($sql['va'] == 0 & (!$sql['jens'] | !$sql['sen'] | !$sql['city'] | !$sql['photo'] | !$sql['name'] | !$sql['lat'])){
			$tk=tk();
		sendmessage($from,"⚠️ خطا : برای مشاهده پروفایل کاربران دیگر باید پروفایل شما تکمیل شده باشد. 

اطلاعات تکمیل نشده ی شما :  $tk",$k_pro,$msgid);
	exit();
		}
		
		sendphoto($from,$photo,"• نام: $name
• جنسیت: $jens
• شهر: $city
• سن: $sen

هم اکنون $onl

‏🆔 آیدی : /user_$use

🏁 فاصله از شما : $nem",
json_encode(['inline_keyboard'=>[
[['text'=>"📨 پیام دایرکت",'callback_data'=>"cha-dl-".$a['id']],['text'=>"💬 درخواست چت",'callback_data'=>"cha-ch-".$a['id']]],
[['text'=>"🔒بلاک کردن کاربر",'callback_data'=>"cha-bl-".$a['id']],['text'=>"🚫 گزارش کاربر",'callback_data'=>"cha-rp-".$a['id']]]
]]));
		
	if($sql['va']!=0){
		mysqli_query($ne,"update members set va=va-1 where id=$from");
	}
	}else{
		sendmessage($from,"⚠️ خطا : چنین کاربری وجود ندارد",null,$msgid);
	}
}

else if($text=='📢Back To Panel' & $from==$admin){
		if(!$sql['step']){
			sendmessage($from,"هنوز چیزی نشده که میخوایی برگردی پنل",$k_pane);
		}else{
		mysqli_query($ne,"update members set step='' where id=$from");
		sendmessage($from,"به پنل مدیریتی برگشتید",$k_pane,$msgid);
		}
	}

else if($text=='✅New Admin' & $from==$admin){
			sendmessage($from,"شناسه ی شخص مورد نظر را ارسال کنید.",$k_backpanel);
			mysqli_query($ne,"update members set step='add-ad' where id=$from");
		}
		else if($text=="✅Remove Admin"){
			sendmessage($from,"شناسه ی شخص برای خارج کردن از لیست ادمین هارا بفرستید.",$k_backpanel);
			mysqli_query($ne,"update members set step='del-ad' where id=$from");
		}
		else if($sql['step']=='add-ad' & $from==$admin){
			$s=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$text'"));
			$adi=mysqli_fetch_assoc(mysqli_query($ne,"select * from admin where step='admin' and txt=$text"));
			if($s['id']){
				if(!$adi['id']){
					mysqli_query($ne,"insert into admin(step,txt)values('admin','$text')");
				sendmessage($from,"شخص با موفقیت ادمین شد.",$k_panel,$msgid);
				mysqli_query($ne,"update members set step='' where id=$from");
				}else{
					sendmessage($from,"این شخص قبلا ادمین شده بود.");
				}
			}else{
				sendmessage($from,"همچین شخصی در ربات عضو نیست.");
			}
		}
		else if($sql['step']=='del-ad' & $from==$admin){
			$s=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$text'"));
			$adi=mysqli_fetch_assoc(mysqli_query($ne,"select * from admin where step='admin' and txt=$text"));
			if($s['id']){
				if($adi['id']){
					mysqli_query($ne,"delete from admin where txt='$text'");
				sendmessage($from,"شخص از لیست ادمین ها حذف شد.",$k_panel,$msgid);
				mysqli_query($ne,"update members set step='' where id=$from");
				}else{
					sendmessage($from,"این شخص ادمین نیست");
				}
			}else{
				sendmessage($from,"همچین شخصی در ربات عضو نشده است");
			}
		}
		else if($text=='❌Block' & $from==$admin){
			sendmessage($from,"شناسه ی شخص مورد نظر را ارسال کنید.",$k_backpanel,$msgid);
			mysqli_query($ne,"update members set step='ban-s' where id=$from");
		}
		else if($sql['step']=='ban-s' & $from==$admin){
			$s=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$text'"));
			
			if($s['id']){
				if($s['err'] != 8){
					sendmessage($from,"شخص مسدود شد.",$k_panel,$msgid);
					mysqli_query($ne,"update members set err=8 where id='$text'");
					mysqli_query($ne,"update members set step='' where id='$from'");
				}else{
					sendmessage($from,"شخص قبلا مسدود شده بود");
				}
			}else{
				sendmessage($from,"همچین شخصی در ربات عضو نشده");
			}
		}
		else if($text=="❌UnBlock" & $from==$admin){
			sendmessage($from,"شناسه ی شخص را ارسال کنید تا رفع مسدودیت شود",$k_backpanel,$msgid);
			mysqli_query($ne,"update members set step='unban-s' where id=$from");
		}
		else if($sql['step']=='unban-s' & $from==$admin){
			$s=mysqli_fetch_array(mysqli_query($ne,"select * from members where id='$text'"));
			
			if($s['id']){
				if($s['err'] == 8){
					sendmessage($from,"شخص آزاد شد.",$k_panel,$msgid);
					mysqli_query($ne,"update members set err=0 where id='$text'");
					mysqli_query($ne,"update members set step='' where id='$from'");
				}else{
					sendmessage($from,"شخص مسدود نیست.");
				}
			}else{
				sendmessage($from,"همچین شخصی در ربات عضو نشده");
			}
		}

else if(is_numeric($sql['chat'])){
	if($text or $cp=$message['caption']){
		$text=$text??$cp;
		if(preg_match('/[a-z]/i',$text)){
			sendmessage($from,"🤖 پیام سیستم 👇

⚠️ خطا : امکان ارسال پیام حاوی حروف لاتین وجود ندارد.

برای ارسال لینک یا آیدی تلگرام از پیام دایرکت استفاده کنید.");
		exit;
		}
		if(preg_match('~bot|t(elegram)?\.me|@|\.ir|\.com~i',$text)){
			sendmessage($from,"🤖 پیام سیستم 👇

⚠️ خطا : امکان ارسال لینک وجود ندارد.

برای ارسال لینک یا آیدی تلگرام از پیام دایرکت استفاده کنید.");
			exit;
		}
	}
	if($text)
	sendmessage($sql['chat'],$text);
	else if($message['photo']){
		Neman('sendphoto',[
		'chat_id'=>$sql['chat'],
		'photo'=>$message['photo'][2]['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['document']){
		Neman('senddocument',[
		'chat_id'=>$sql['chat'],
		'document'=>$message['document']['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['video']){
		Neman('sendvideo',[
		'chat_id'=>$sql['chat'],
		'video'=>$message['video']['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['sticker']){
		Neman('sendsticker',[
		'chat_id'=>$sql['chat'],
		'sticker'=>$message['sticker']['file_id']
		]);
	}else if($message['audio']){
		Neman('sendaudio',[
		'chat_id'=>$sql['chat'],
		'audio'=>$message['audio']['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['location']){
		Neman('sendlocation',[
		'chat_id'=>$sql['chat'],
		'latitude'=>$lat,
		'longitude'=>$long
		]);
	}else if($message['voice']){
		Neman('sendvoice',[
		'chat_id'=>$sql['chat'],
		'voice'=>$message['voice']['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['video_note']){
		Neman('sendvideonote',[
		'chat_id'=>$sql['chat'],
		'video_note'=>$message['video_note']['file_id'],
		'caption'=>$message['caption']
		]);
	}else if($message['contact']){
		Neman('sendcontact',[
		'chat_id'=>$sql['chat'],
		'phone_number'=>$message['contact']['phone_number'],
		'first_name'=>$message['contact']['first_name'],
			'last_name'=>$message['contact']['last_name']
		]);
	}
}

elseif($text == '/panel' and $tc == 'private' and in_array($from_id,$admin)){
	Source_Home('sendmessage',[
	'chat_id'=>$from_id,
	'text'=>"📍 ادمین عزیز به پنل مدریت ربات خوش امدید",
	'reply_markup'=>json_encode([
	'keyboard'=>[
		[['text'=>"🎲Send To All"],['text'=>"📊State"],['text'=>"🎲Forward To All"]],
[['text'=>"✅New Admin"],['text'=>"✅Remove Admin"]],
[['text'=>"❌Block"],['text'=>"❌UnBlock"]],
[['text'=>"📢Back"]],
			 ],
	   'resize_keyboard'=>true
	   ])
	 ]);
 }	
	else if($text=='📢Back'){
		sendmessage($from,"به منوی اصلی ربات برگشتید.",$k_start,$msgid);
		mysqli_query($ne,"update members set step='' where id=$from");
	}
	else if($text=='📢Back To Panel'){
		if(!$sql['step']){
			sendmessage($from,"هنوز چیزی نشده که میخوایی برگردی پنل",$k_pane);
		}else{
		mysqli_query($ne,"update members set step='' where id=$from");
		sendmessage($from,"به پنل مدیریتی برگشتید",$k_pane,$msgid);
		}
	}
	
	else if($text=='🎲Forward To All'){
		sendmessage($from,"پیام خود را در هر قالبی که دوست دارید نقل قول کنید.",$k_backpanel);
		mysqli_query($ne,"update members set step='for-all' where id=$from");
	}
	else if($sql['step']=='for-all'){
			$qu=mysqli_query($ne,"select * from members");
			while($r=mysqli_fetch_assoc($qu)){
				forward($r['id'],$from,$msgid);
			}
			sendmessage($from,"پیام شما به تمام اعضای ربات نقل قول شد.",$k_pane,$msgid);
			mysqli_query($ne,"update members set step='' where id=$from");
	}

	else if($text=='🎲Send To All'){
		sendmessage($from,"پیام خود را در قالب متن ارسال کنید.",$k_backpanel);
		mysqli_query($ne,"update members set step='send-all' where id=$from");
	}
	else if($sql['step']=='send-all'){
		if($text){
			$qu=mysqli_query($ne,"select * from members");
			while($r=mysqli_fetch_assoc($qu)){
				sendmessage($r['id'],$text);
			}
			sendmessage($from,"پیام متنی شما به تمامی عضو های ربات ارسال گردید.",$k_pane,$msgid);
			mysqli_query($ne,"update members set step='' where id=$from");
		}else{
			sendmessage($from,"پیام باید در قالب متن باشد.",null,$msgid);
		}
	}
	
	else if($text=='📊State'){
		$c=mysqli_num_rows(mysqli_query($ne,"select * from members"));
		sendmessage($from,"تعداد افراد عضو شده در ربات : $c",null,$msgid);
	}



else if($admin!=$from or !$ad){
	sendmessage($from,"متوجه نشدم :/

کانال رسمی زومیت چت 🤖 (اخبار،آپدیت ها و ترفند ها) 

چه کاری برات انجام بدم؟ از منوی پایین انتخاب کن 👇",$k_start,$msgid,'markdown');
}

mysqli_query($ne,"update members set online='".time()."' where id=$from");
/*
@datisnetwork
https://t.me/datisnetwork
*/
?>




