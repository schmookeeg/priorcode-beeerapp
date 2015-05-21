<?php 
$MAXIMUM_FILESIZE = 4194304; // 4 MB 
//echo exif_imagetype($_FILES['Filedata']); 
if ($_FILES['Filedata']['size'] <= $MAXIMUM_FILESIZE) 
{ 
    move_uploaded_file($_FILES['Filedata']['tmp_name'], "temporary/".$_FILES['Filedata']['name']); 
    $type = exif_imagetype("./temporary/".$_FILES['Filedata']['name']); 
    if ($type == 1 || $type == 2 || $type == 3) 
    { 
        rename("temporary/".$_FILES['Filedata']['name'], "uploads/".$_FILES['Filedata']['name']); 
    } 
    else 
    { 
        unlink("temporary/".$_FILES['Filedata']['name']); 
    } 
} 
echo "uploads/".$_FILES['Filedata']['name'];
?>