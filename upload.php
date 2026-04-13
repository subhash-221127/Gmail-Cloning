<?php

if(isset($_FILES['upload-send']))
    {
     $fileName = $_FILES['upload-send']['name'];
      $tempName = $_FILES['upload-send']['tmp_name'];
      $error = $_FILES['upload-send']['error'];

      $uploadPath = "uploads/" .$fileName;

      if($error == 0){
         if(move_uploaded_file($tempName,$uploadPath)){
                echo "File uploaded successfully";
            }else{
                echo "Failed to move file ";
           }
      }else{
       echo "Error uploading file";
    }
}else{
    echo "No file selected.";
}


?>