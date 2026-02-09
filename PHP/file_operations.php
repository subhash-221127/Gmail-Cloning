<?php


$data = "This is a sample text inside text_files.txt";
file_put_contents("text_files.txt", $data);

if (file_exists("text_files.txt")) {
    echo "File exists<br>";
}

echo "File Size: " . filesize("text_files.txt") . "<br>";
echo "File Type: " . filetype("text_files.txt") . "<br>";
echo "Last Modified Time: " . filemtime("text_files.txt") . "<br>";
echo "Permissions: " . fileperms("text_files.txt") . "<br>";

$content = file_get_contents("text_files.txt");
echo "File Content: " . $content . "<br>";

copy("text_files.txt", "copy_text_files.txt");
rename("copy_text_files.txt", "renamed_text_files.txt");

if (is_file("text_files.txt")) {
    echo "Confirmed: It is a file<br>";
}

$files = scandir(".");
print_r($files);

$fp = fopen("text_files.txt", "r");
if (flock($fp, LOCK_SH)) {
    echo "<br>" . fread($fp, filesize("text_files.txt"));
    flock($fp, LOCK_UN);
}
fclose($fp);

unlink("renamed_text_files.txt");

?>
