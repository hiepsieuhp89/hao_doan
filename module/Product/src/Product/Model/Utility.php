<?php

namespace Product\Model;
use Zend\Session\Container;
class Utility {
   var $image;
   var $image_type;
    public function __construct() {
        
    }
    public  function load($filename) {
 
      $image_info = getimagesize($filename);
      $this->image_type = $image_info[2];
      if( $this->image_type == IMAGETYPE_JPEG ) {
 
         $this->image = imagecreatefromjpeg($filename);
      } elseif( $this->image_type == IMAGETYPE_GIF ) {
 
         $this->image = imagecreatefromgif($filename);
      } elseif( $this->image_type == IMAGETYPE_PNG ) {
 
         $this->image = imagecreatefrompng($filename);
      }
   }
   public function save($filename, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image,$filename,$compression);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image,$filename);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image,$filename);
      }
      if( $permissions != null) {
 
         chmod($filename,$permissions);
      }
   }
   public function output($image_type=IMAGETYPE_JPEG) {
 
      if( $image_type == IMAGETYPE_JPEG ) {
         imagejpeg($this->image);
      } elseif( $image_type == IMAGETYPE_GIF ) {
 
         imagegif($this->image);
      } elseif( $image_type == IMAGETYPE_PNG ) {
 
         imagepng($this->image);
      }
   }
   public function getWidth() {
 
      return imagesx($this->image);
   }
   public function getHeight() {
 
      return imagesy($this->image);
   }
   public function resizeToHeight($height) {
 
      $ratio = $height / $this->getHeight();
      $width = $this->getWidth() * $ratio;
      $this->resize($width,$height);
   }
 
   public function resizeToWidth($width) {
      $ratio = $width / $this->getWidth();
      $height = $this->getheight() * $ratio;
      $this->resize($width,$height);
   }
 
   public function scale($scale) {
      $width = $this->getWidth() * $scale/100;
      $height = $this->getheight() * $scale/100;
      $this->resize($width,$height);
   }
 
   public function resize($width,$height) {
      $new_image = imagecreatetruecolor($width, $height);
      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
      $this->image = $new_image;
   }      
   public function croppThis($target_url) {

    $this->jpegImgCrop($target_url);

 }
 public function jpegImgCrop($target_url) {//support



  $image = imagecreatefromjpeg($target_url);
  $filename = $target_url;
  $width = imagesx($image);
  $height = imagesy($image);
  $image_type = imagetypes($image); //IMG_GIF | IMG_JPG | IMG_PNG | IMG_WBMP | IMG_XPM

  if($width==$height) {

   $thumb_width = $width;
   $thumb_height = $height;

  } elseif($width<$height) {

   $thumb_width = $width;
   $thumb_height = $width;

  } elseif($width>$height) {

   $thumb_width = $height;
   $thumb_height = $height;

  } else {
   $thumb_width = 360;
   $thumb_height = 255;
  }
$thumb_width = 360;
   $thumb_height = 255;
  $original_aspect = $width / $height;
  $thumb_aspect = $thumb_width / $thumb_height;

  if ( $original_aspect >= $thumb_aspect ) {

     // If image is wider than thumbnail (in aspect ratio sense)
     $new_height = $thumb_height;
     $new_width = $width / ($height / $thumb_height);

  }
  else {
     // If the thumbnail is wider than the image
     $new_width = $thumb_width;
     $new_height = $height / ($width / $thumb_width);
  }

  $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );

  // Resize and crop
  imagecopyresampled($thumb,
         $image,
         0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
         0 - ($new_height - $thumb_height) / 2, // Center the image vertically
         0, 0,
         $new_width, $new_height,
         $width, $height);
  imagejpeg($thumb, $filename, 50);

 }
    public function formatDate($date, $showNow = true) {

        $date = trim($date);
        if (!$date && $showNow) {
            $date = 'now';
        }
        if ($date) {
            return date('d/m/Y', strtotime($date));
        } else {
            return '';
        }
    }

    public function chuyenDoi($cs, $tolower = false) {
        /* Mảng chứa tất cả ký tự có dấu trong Tiếng Việt */
        $link="";
        $marTViet = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă",
            "ằ", "ắ", "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề",
            "ế", "ệ", "ể", "ễ",
            "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ",
            "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă",
            "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ", "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ","-","  ", " ", ",", "?", ")", "(", ":", "!", "*", "&", "%", "$", "@", "`", "~", "/","#","\"",".","'","\\");

        /* Mảng chứa tất cả ký tự không dấu tương ứng với mảng $marTViet bên trên */
        $marKoDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a",
            "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o",
            "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d",
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A",
            "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D",""," ", "-", "", "", "", "", "", "", "", "", "", "", "", "", "", "","","","","","");

        if ($tolower) {
            $link=strtolower(str_replace($marTViet, $marKoDau, $cs));
        }
        $link=str_replace($marTViet, $marKoDau, $cs);
return preg_replace('([^a-zA-Z0-9])', '-', $link);
        
    }

    public function removescript($str) {
        $script = array("<", ">");
        $remove = array("&lt;", "&gt;");
        return str_replace($script, $remove, $str);
    }

    public function checkdeCode($string) {
        $datacode = array(
            "0" => "location",
            "1" => "href",
            "2" => "reload",
            "3" => "hash",
            "4" => "replace",
            "5" => "alert",
            "6" => "$",
            "7" => "<script>",
            "8" => "JQuery",
            "9" => "document",
        );
        foreach ($datacode as $key => $value) {
            $data = strstr($string, $value);
            if ($data != null) {
                return false;
            }
        }
        return true;
    }

    function subStringv($str, $len) {
        //$str = html_entity_decode($str, ENT_QUOTES, $charset='UTF-8');
        if (strlen($str) > $len) {
            $arr = explode(' ', $str);
            $str = substr($str, 0, $len);
            $arrRes = explode(' ', $str);
            $last = $arr[count($arrRes) - 1];
            unset($arr);
            if (strcasecmp($arrRes[count($arrRes) - 1], $last)) {
                unset($arrRes[count($arrRes) - 1]);
            }
            return implode(' ', $arrRes) . "...";
        }
        return $str;
    }

    public function tooltipString($str, $maxlength = 30, $strip_tag = true) {
        if ($strip_tag) {
            $str = strip_tags($str);
        }
        if (strlen($str) > $maxlength) {
            return "<span title='$str'>" . $this->subStringv($str, $maxlength) . '</span>';
        } else {
            return $str;
        }
    }
    public function rand_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            @$str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
     public function rand_code_oder($length) {
        $chars = "0123456789098765432119023847566574839201";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            @$str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }	
	
	public function witelog($text){
        $session_ip = new Container('S_IP');
        $ip_client = $session_ip->ip_client;
        
         $session_user = new Container('user');
         $username = $session_user->username;
        $array_date = explode('-', date('Y-m-d'));       
        $parth_forder = WEB_MEDIA . '/public/log/Thang-' . $array_date['1'] . '-' . $array_date['0'];
        $name_file = $array_date['2'] . '-' . $array_date['1'] . '-' . $array_date['0'] . '.txt';
        
        //ghi file log đã tạo khi login
         $fp = fopen($parth_forder . '/' . $name_file, 'a') or exit('Error2');
                $string = $username . ' ----- '.$ip_client.' ----- ' . date('Y-m-d H:i:s') . ' ----- '.$text;
                fwrite($fp, $string . "\r\n");
                fclose($fp);
    }
	
}
