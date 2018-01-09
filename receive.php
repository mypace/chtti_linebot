<?php
 $json_str = file_get_contents('php://input'); //接收REQUEST的BODY
 $json_obj = json_decode($json_str); //轉JSON格式

$myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 fwrite($myfile, "\xEF\xBB\xBF".$json_str); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);
 //產生回傳給line server的格式
 $sender_userid = $json_obj->events[0]->source->userId;
 $sender_txt = $json_obj->events[0]->message->text;
 $sender_replyToken = $json_obj->events[0]->replyToken;
 $line_server_url = 'https://api.line.me/v2/bot/message/push';
 //用sender_txt來分辨要發何種訊息
 $objID = $json_obj->events[0]->message->id;
			$url = 'https://api.line.me/v2/bot/message/'.$objID.'/content';
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
			));
				
			$json_content = curl_exec($ch);
			curl_close($ch);
$imagefile = fopen($objID.".jpeg", "w+") or die("Unable to open file!"); //設定一個log.txt，用來印訊息
			fwrite($imagefile, $json_content); 
			fclose($imagefile);



 //回傳給line server
 $header[] = "Content-Type: application/json";
 $header[] = "Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
 $ch = curl_init($line_server_url);                                                                      
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
 $result = curl_exec($ch);
 curl_close($ch); 

/*
//switch case
 $json_str = file_get_contents('php://input'); //接收REQUEST的BODY
 $json_obj = json_decode($json_str); //轉JSON格式

 //產生回傳給line server的格式
 $sender_userid = $json_obj->events[0]->source->userId;
 $sender_txt = $json_obj->events[0]->message->text;
 $sender_replyToken = $json_obj->events[0]->replyToken;
 $line_server_url = 'https://api.line.me/v2/bot/message/push';
 //用sender_txt來分辨要發何種訊息
 switch ($sender_txt) {
    		case "push":
        		$response = array (
				"to" => $sender_userid,
				"messages" => array (
					array (
						"type" => "text",
						"text" => "Hello, YOU SAY ".$sender_txt
					)
				)
			);
        		break;
    		case "reply":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "text",
						"text" => "Hello, YOU SAY ".$sender_txt
					)
				)
			);
        		break;
		case "image":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "image",
						"originalContentUrl" => "https://www.w3schools.com/css/paris.jpg",
						"previewImageUrl" => "https://www.nasa.gov/sites/default/themes/NASAPortal/images/feed.png"
					)
				)
			);
        		break;
		 case "location":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "location",
						"title" => "my location",
						"address" => "〒150-0002 東京都渋谷区渋谷２丁目２１−１",
            					"latitude" => 35.65910807942215,
						"longitude" => 139.70372892916203
					)
				)
			);
        		break;
		case "sticker":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "sticker",
						"packageId" => "1",
						"stickerId" => "1"
					)
				)
			);
        		break;
		 case "button":
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "template",
						"altText" => "this is a buttons template",
						"template" => array (
							"type" => "buttons",
							"thumbnailImageUrl" => "https://www.w3schools.com/css/paris.jpg",
							"title" => "Menu",
							"text" => "Please select",
							"actions" => array (
								array (
									"type" => "postback",
									"label" => "Buy",
									"data" => "action=buy&itemid=123"
								),
								array (
									"type" => "postback",
                   							"label" => "Add to cart",
                    							"data" => "action=add&itemid=123"
								)
							)
						)
					)
				)
			);
        		break;
		case "mypic":
		 	//"{\"userId\":\"Ue8d01d2d9747c48c3e171dd9905727e0\",\"displayName\":\"\u90a6\",\"pictureUrl\":\"http:\/\/dl.profile.line-cdn.net\/0hcb8-J8tIPE0FVBBu4bdDGjkRMiByejoFfTAmf3MGNngqYSsdOmcmKHNVNSh6N38eMDMmKCgEYXt6\",\"statusMessage\":\"love is so short, forgetting is so long\"}"
			$line_server_url = 'https://api.line.me/v2/bot/profile/'.$sender_userid;
 			$header2[] = "Content-Type: application/json";
 			$header2[] = "Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
 			$ch2 = curl_init($line_server_url);                                                                      
 			curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
 			curl_setopt($ch2, CURLOPT_POSTFIELDS,"");                                                                  
 			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);                                                                      
 			curl_setopt($ch2, CURLOPT_HTTPHEADER, $header2);                                                                                                   
 			$result = curl_exec($ch2);
 			curl_close($ch2); 
		 	$json_obj = json_decode($result); //轉JSON格式
		 	$pictureurl=$json_obj->pictureUrl;
			$line_server_url = 'https://api.line.me/v2/bot/message/reply';
        		$response = array (
				"replyToken" => $sender_replyToken,
				"messages" => array (
					array (
						"type" => "image",
						"originalContentUrl" => "https://www.w3schools.com/css/paris.jpg",
						"previewImageUrl" => "https://www.nasa.gov/sites/default/themes/NASAPortal/images/feed.png"
					)
				)
			);
		 	
		 	//$myfile2 = fopen("log2.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
 			//fwrite($myfile2, "\xEF\xBB\xBF".json_encode($result)); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 			//fclose($myfile2);
        		break;
 }

 $myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
fwrite($myfile, "\xEF\xBB\xBF".$json_str.PHP_EOL.json_encode($response).PHP_EOL.$pictureurl); //在字串前加入\xEF\xBB\xBF轉成utf8格式
 fclose($myfile);

 //回傳給line server
 $header[] = "Content-Type: application/json";
 $header[] = "Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
 $ch = curl_init($line_server_url);                                                                      
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
 curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
 curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
 $result = curl_exec($ch);
 curl_close($ch); 
*/
/*
//Reply API
$json_str = file_get_contents('php://input'); //接收REQUEST的BODY
$json_obj = json_decode($json_str); //轉JSON格式

//產生回傳給line server的格式
$sender_userid = $json_obj->events[0]->source->userId;
$sender_txt = $json_obj->events[0]->message->text;
$replyToken = $json_obj->events[0]->replyToken;
$response = array (
	"replyToken" => $replyToken,
	"messages" => array (
		array (
			"type" => "text",
			"text" => "Hello, YOU SAY ".$sender_txt
			)
		)
	);
$myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
fwrite($myfile, "\xEF\xBB\xBF".$json_str.PHP_EOL.json_encode($response)); //在字串前加入\xEF\xBB\xBF轉成utf8格式
fclose($myfile);
//回傳給line server
$header[] = "Content-Type: application/json";
$header[] = "Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
$ch = curl_init("https://api.line.me/v2/bot/message/reply");                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
$result = curl_exec($ch);
curl_close($ch); 

*/

/*
//push API
$json_str = file_get_contents('php://input'); //接收REQUEST的BODY
$json_obj = json_decode($json_str); //轉JSON格式

//產生回傳給line server的格式
$sender_userid = $json_obj->events[0]->source->userId;
$sender_txt = $json_obj->events[0]->message->text;
$response = array (
	"to" => $sender_userid,
	"messages" => array (
		array (
			"type" => "text",
			"text" => "Hello, YOU SAY ".$sender_txt
			)
		)
	);
$myfile = fopen("log.txt","w+") or die("Unable to open file!"); //設定一個log.txt 用來印訊息
fwrite($myfile, "\xEF\xBB\xBF".$json_str.PHP_EOL.json_encode($response)); //在字串前加入\xEF\xBB\xBF轉成utf8格式
fclose($myfile);
//回傳給line server
$header[] = "Content-Type: application/json";
$header[] = "Authorization: Bearer fmUCBqFv8uF0YAIfJUE2uEtUFNnhqP/Vd5IqdgnKYPSyYC7/rqsszpwRMjCRrAyk2pbzTMz1NP77A0AOlQQN0JMIeUr6gCEmp2y9aSHW2klseVMC9/Om9yBOXoBKOriG+2s0r+VOUN3+Hl92wzXCNwdB04t89/1O/w1cDnyilFU=";
$ch = curl_init("https://api.line.me/v2/bot/message/push");                                                                      
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));                                                                  
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);                                                                                                   
$result = curl_exec($ch);
curl_close($ch); 
*/


/*
  $json_str=file_get_contents('php://input'); //接收REQUEST的BODY. 此為string format
  $json_obj=json_decode($json_str); //轉為json格式


  $myfile=fopen("log.txt","w+") or die("Unable to open file");
  fwrite($myfile, "\xEF\xBB\xBF".$json_str.PHP_EOL);  //在字串前加入\xEF\xBB\xBF 轉成utf-8格式
  fclose($myfile);
*/
?>
