<?php

class App_Plugins_UploadAvatar {

   public static function uploadFileLarg($name, $file_name, $path, $max_file, $max_width) {


      $large_image_location = $path . $name;

      /**
        if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777);
        chmod($upload_dir, 0777);
        }
        /*
       */
      $userfile_name = $file_name['name'];
      $userfile_tmp = $file_name['tmp_name'];
      $userfile_size = $file_name['size'];

      if (isset($userfile_name)) {
         move_uploaded_file($userfile_tmp, $large_image_location);

         @chmod($large_image_location, 0777);         
         $width = App_Plugins_UploadAvatar::getWidth($large_image_location);
         $height = App_Plugins_UploadAvatar::getHeight($large_image_location);
         if ($width > $max_width) {
            $scale = $max_width / $width;
            $uploaded = App_Plugins_UploadAvatar::resizeImage($large_image_location, $width, $height, $scale);
         } else {
            $scale = 1;
            $uploaded = App_Plugins_UploadAvatar::resizeImage($large_image_location, $width, $height, $scale);
         }
      }
   }

   public static function resizeImage($image, $width, $height, $scale) {
      $newImageWidth = ceil($width * $scale);
      $newImageHeight = ceil($height * $scale);
      $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
      $source = imagecreatefromjpeg($image);
      imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
      imagejpeg($newImage, $image, 90);
      chmod($image, 0777);
      return $image;
   }

   public static function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale) {
      $newImageWidth = ceil($width * $scale);
      $newImageHeight = ceil($height * $scale);
      $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
      $source = imagecreatefromjpeg($image);
      imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
      imagejpeg($newImage, $thumb_image_name, 90);
      chmod($thumb_image_name, 0777);
      return $thumb_image_name;
   }

   public static function getHeight($image) {
      $sizes = getimagesize($image);
      $height = $sizes[1];
      return $height;
   }

   public static function getWidth($image) {
      $sizes = getimagesize($image);
      $width = $sizes[0];
      return $width;
   }

}