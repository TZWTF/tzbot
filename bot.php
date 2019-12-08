<?php
	
ini_set ( "max_execution_time", "0" );
set_time_limit (0);

$login = 'Вася'; // Ник в игре!
$password = 'парольваси123';  // ник в игре!

ConnectKlient ();
sleep (2);
Connectchat ();
$l = $login;
$c = 'city1.timezero.ru?c=city1.timezero.ru'; // Сервер игровой , city1 = terra , city2 = rapa
$vc = '';
$dubl = '';
 
sleep(3);
	$buf1 = '';
	usleep ( 5000 );

while ( strpos ( $buf1, 'noduel' ) === false ) 
	{
	usleep ( 5000 );
	$buf1 .= fgets ( $klient );
	}
	preg_match ("|ses2=\"(.*)\"\s|U", $buf1, $ses);
	$s = $ses[1];

sleep(2);
socket_set_blocking ( $klient, false );
socket_set_blocking ( $chat, false );
$send = 0;
$i = 0;
$last = time ();
$tim = time ();
$s = ' ';
for($i = 0; $i <= 10; $i ++)
    $buf = fgets ( $chat );
$i = 0;
 
while ( $i < 1 ) {
    if( fmod (time(),40)==0) {sleep(1);}
    if (! is_resource ( $klient ) || feof ( $klient )) {
        die ();
    }
    $buf = fgets ( $chat );
    $buf1 = fgets ( $klient );
    $anti_flood = ' ';
	$today = date("H:i:s");
// выбиваем из игры
    if (strpos ( $buf, 'private [вася] exit' ) > 0) // если вашему боту написали в приват exit , он выйдет с игры.
	{
		fputs($chat, "<POST t=\"private [Дуся] пока! \" />\r\n\r\n"); // в чат отправит сообщение (вам , или в общий чат )
        die ();
    } 
	
    if (strpos ( $buf, 'private [вася] время' ) > 0) // если боту написать в приват слово "время" он даст время текущее
	{
		fputs($chat, "<POST t=\"private [KLOD] " .$today. "\" />\r\n\r\n");
		usleep (1);
    } 

   else
    {
//Стабильный онлайн
    usleep ( 15 );
    $tc = time () - $last;
    if ($tc >= 15) 
	    {
          fputs ( $klient, "<N />\r\n\r\n" );
          fputs ( $chat, "<N />\r\n\r\n" );
          $last = time ();
        }  
	}

}

// конетим верхний фрейм
    Function ConnectKlient() {
    global $b, $last, $buf, $klient, $i, $s, $ss, $ses, $chat, $login, $password;
    $klient = fsockopen ( "city1.timezero.ru", 5190 ); // Сервер игровой , city1 = terra , city2 = rapa
    if (! $klient) {
        die ();
    } else {
        socket_set_blocking ( $klient, false );
        fputs ( $klient, "\r\n\r\n" );
        sleep ( 1 );
        $key = fgets ( $klient );
        $key = substr ( $key, 8, 32 );
        fputs ( $klient, "<LOGIN lang=\"ru\" v3=\"127.0.0.1\" v2=\"7.0.1 (7.1.2.6)\" v=\"108\" open_pssw=\"1\" p=\"${password}\" l=\"${login}\" />\r\n\r\n" );
        socket_set_blocking ( $klient, true );
        $i = 0;
        $s = '   ';
        $b = 0;
        $ses = '';
        $buf = '';
        $ss = '';
        while ( $i < 22 ) {
            $buf = fgetc ( $klient );
            $s .= $buf;
            if ($b == 1) {
                $ss .= $buf;
                $i ++;
            }
            $s = substr ( $s, 1, 3 );
            if ('ses' == $s)
                $b = 1;
        }
        $ses = substr ( $ss, 2, 20 );
 
        fputs ( $klient, "<GETME />\r\n\r\n" );
        fputs ( $klient, "<CHAT />\r\n\r\n" );
    }

// Конектим чатик
   Function Connectchat() 
    {
       global $ses, $chat, $login;
       $chat = fsockopen ( "chat.timezero.ru", 5190 );
       fputs ( $chat, "<CHAT ses=\"${ses}\" l=\"${login}\" />\r\n\r\n" );
       fputs($chat, "<POST t=\"private [KLOD] Онлайн! \" />\r\n\r\n");
    }
					  
}
die ();
?>