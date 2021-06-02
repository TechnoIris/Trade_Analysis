<?php
// include('db.php');
include('db_setup.php');
ini_set('display_errors', 1);
session_start();

$message = '';
$newest_file = '';
$is_upload = 0;
$arr = array();

if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['error'] === UPLOAD_ERR_OK)
{
  $fileTmpPath = $_FILES['uploadedFile']['tmp_name'];
  $fileName = $_FILES['uploadedFile']['name'];
  $fileSize = $_FILES['uploadedFile']['size'];
  $fileType = $_FILES['uploadedFile']['type'];

  $fileNameCmps = explode(".", $fileName);
  $fileExtension = strtolower(end($fileNameCmps));

  $newFileName = $fileName;
  $allowedfileExtensions = array('csv', 'xlsx');

  if (in_array($fileExtension, $allowedfileExtensions))
  {
    $uploadFileDir = './uploads/';
    $dest_path = $uploadFileDir . $newFileName;

    if (file_exists($dest_path))
    {
      $message = 'File already exists.';
    }
    elseif (move_uploaded_file($fileTmpPath, $dest_path))
    {
      $is_upload = 1;
      $newestFile = $newFileName;
      $message ='File is successfully uploaded.';
      // // for array access to dropdown
      $dest =  'uploads/' . $newestFile;
      if (($handle = fopen($dest, "r")) !== FALSE) {
        while (($data = fgetcsv($handle)) !== FALSE) {
          $name = strval($data[2]);
          $when = strval($data[1]);
          $price = strval($data[3]);
          if (!in_array($data[2], $arr)) {
            array_push($arr, $data[2]);
          }
          try {
            // $query = "insert into stocks(STOCKNAME, DATE_, PRICE) values ('maath', STR_TO_DATE('17-02-2020', '%d-%m-%Y'), 632)";
            $query = "insert into stocks(STOCKNAME, DATE_, PRICE) values (?, STR_TO_DATE(?, '%d-%m-%Y'), ?)";
            $statement = $connect->prepare($query);
            $statement->bindParam(1, $name, PDO::PARAM_STR, 12);
            $statement->bindParam(2, $when, PDO::PARAM_STR, 12);
            $statement->bindParam(3, $price, PDO::PARAM_INT);
            $statement->execute();
          }
          catch (Exception $e) {
            console.log($e);
          }
        }
        fclose($handle);
      }
    }
    else
    {
      $message = 'There was some error moving the file to upload directory. Please make sure the upload directory is writable by web server.';
    }
  }
  else
  {
    $message = 'Upload failed due to invalid file format. Allowed file types: ' . implode(',', $allowedfileExtensions);
  }
}
else
{
  $message = 'There is some error in the file upload. Please check the following error. ';
  $message .= 'Error:' . $_FILES['uploadedFile']['error'];
}

try {
  $connect = null;
}
catch(Exception $e) {

}

$dest = json_encode(array($message, $is_upload, $arr));
echo $dest;

?>
