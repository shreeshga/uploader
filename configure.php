<?php
require_once 'facebook.php';

$app_id = "232348633455049";
$app_key = "";
$app_secret = "b03989d5a2037453be10f779a06b165e";
$canvas_url = "http://oml.in/shreesh/apps/FB/pic_upload.php";

$facebook = new Facebook(array(
'appId'  => $app_id,
'secret' => $app_secret,
'cookie' => true
));

$user= $facebook->getUser();

if (!$user) {

        $url = $facebook->getLoginUrl(array(
        'canvas' => 1,
        'fbconnect' => 0,
        'req_perms' => 'publish_stream, user_photos, read_stream, read_friendlists'
        ));

        echo "<script type='text/javascript'>top.location.href = '$url';</script>";

    }//end if session user 
else
{

        try {

        $uid = $facebook->getUser();
        $me = $facebook->api('/me');

        $updated = date("l, F j, Y", strtotime($me['updated_time']));

 //       echo "Hello " . $me['name'] . "<br />";
 //      echo "You last updated your profile on " . $updated  . "<br />" ;
 //       echo "<img src='https://graph.facebook.com/".$uid."/picture'/>"; 
        }//end try getUser 
        catch (FacebookApiException $e) {

        echo "Error:" . print_r($e, true);

        }//end catch getUser 
}//end else user
