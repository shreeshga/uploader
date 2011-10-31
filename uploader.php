<?php 
require 'configure.php';
$pageId = '169230733094934';

function exif_get_float($value) { 
  $pos = strpos($value, '/'); 
  if ($pos === false) return (float) $value; 
  $a = (float) substr($value, 0, $pos); 
  $b = (float) substr($value, $pos+1); 
  return ($b == 0) ? ($a) : ($a / $b); 
} 

function exif_get_shutter(&$exif) { 
  if (!isset($exif['ShutterSpeedValue'])) return false; 
  $apex    = exif_get_float($exif['ShutterSpeedValue']); 
  $shutter = pow(2, -$apex); 
  if ($shutter == 0) return false; 
  if ($shutter >= 1) return round($shutter) . 's'; 
  return '1/' . round(1 / $shutter) . 's'; 
} 

function exif_get_fstop(&$exif) { 
  if (!isset($exif['ApertureValue'])) return false; 
  $apex  = exif_get_float($exif['ApertureValue']); 
  $fstop = pow(2, $apex/2); 
  if ($fstop == 0) return false; 
  return 'f/' . round($fstop,1); 
} 


function get_exif_string($file) {
	$exif_data = exif_read_data ( $file);
	$emake =$exif_data['Make'];
	$emodel = $exif_data['Model'];
	$eexposuretime = $exif_data['ExposureTime'];
	$efstop = exif_get_fstop($exif_data);
	$eshutter= exif_get_shutter($exif_data);
	$efnumber = $exif_data['FNumber'];
	$eiso = $exif_data['ISOSpeedRatings'];
	$edate = $exif_data['DateTime'];
	$arr = array ('Make' => $emake, 'Model' => $emodel, 'Exposure' => $eexposuretime, 'FStop' => $efstop, 'Shutter Speed' => $eshutter,'ISO' => $eiso);
	return	implode(array_map(create_function('$key,$value','return $key.":".$value." | ";'),array_keys($arr),array_values($arr)));
}
error_reporting(E_ALL);
ini_set("display_errors", 1); 

if(isset($_POST))
{
if ((($_FILES["file"]["type"] == "image/gif")
|| ($_FILES["file"]["type"] == "image/jpeg")
|| ($_FILES["file"]["type"] == "image/pjpeg")))
   { 
   if ( isset($_FILES["file"]) && $_FILES["file"]["error"] <= 0)
    {
	$file='images/'.$_FILES["file"]['name'];
	if( move_uploaded_file($_FILES["file"]["tmp_name"],$file))
            $facebook->setFileUploadSupport(true);
            $post_data = array(
            'name'=>$_POST['album'],
            'description'=>$_POST['album']
            );
            $data['album'] = $facebook->api("/".$pageId."/albums", 'post', $post_data); 
           $exif_info = get_exif_string($file);  
	   echo "Exif :".$exif_info;
           $post_data = array(
            "message" => $exif_info.'<br/> Caption: '.$_POST['message'],
            "source" => '@' . realpath($file)
            );
            $album_id = $data['album']['id'];
            $data['photo'] = $facebook->api("/$album_id/photos", 'post', $post_data);
            return "<p> File Processed.</p>";
        }
    }
    /**/
}

?>
