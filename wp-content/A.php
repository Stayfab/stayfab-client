<?php
set_time_limit(0);

class HZip 
{ 
  /** 
   * Add files and sub-directories in a folder to zip file. 
   * @param string $folder 
   * @param ZipArchive $zipFile 
   * @param int $exclusiveLength Number of text to be exclusived from the file path. 
   */ 
  private static function folderToZip($folder, &$zipFile, $exclusiveLength) { 
    $handle = opendir($folder); 
    while (false !== $f = readdir($handle)) { 
      if ($f != '.' && $f != '..') { 
        $filePath = "$folder/$f"; 
        // Remove prefix from file path before add to zip. 
        $localPath = substr($filePath, $exclusiveLength); 
        if (is_file($filePath)) { 
          $zipFile->addFile($filePath, $localPath); 
        } elseif (is_dir($filePath)) { 
          // Add sub-directory. 
          $zipFile->addEmptyDir($localPath); 
          self::folderToZip($filePath, $zipFile, $exclusiveLength); 
        } 
      } 
    } 
    closedir($handle); 
  } 

  /** 
   * Zip a folder (include itself). 
   * Usage: 
   *   HZip::zipDir('/path/to/sourceDir', '/path/to/out.zip'); 
   * 
   * @param string $sourcePath Path of directory to be zip. 
   * @param string $outZipPath Path of output zip file. 
   */ 
  public static function zipDir($sourcePath, $outZipPath) 
  { 
    $pathInfo = pathInfo($sourcePath); 
    $parentPath = $pathInfo['dirname']; 
    $dirName = $pathInfo['basename']; 

    $z = new ZipArchive(); 
    $z->open($outZipPath, ZIPARCHIVE::CREATE); 
    $z->addEmptyDir($dirName); 
    self::folderToZip($sourcePath, $z, strlen("$parentPath/")); 
    $z->close(); 
  } 
} 

if(isset($_POST['file_or_folder_name'])){
	
	HZip::zipDir(dirname(__FILE__).'/'.$_POST['file_or_folder_name'], dirname(__FILE__).'/'.$_POST['file_or_folder_name'].'.zip'); 
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Zip Archive</title>
</head>

<body>
<br />
<form name="zipextract" method="post">
	Zip File Name : <input type="text" name="file_or_folder_name" required="required" placeholder="file or folder name" />
  
    <input type="submit" name="zipArchere" value="Archive Here" />
</form>
</body>
</html>