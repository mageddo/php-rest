<?php
header('Content-Type: text/plain');

echo "file\n";
print_r($_FILES);
//echo "my file" . $_FILES[file][tmp_name];
echo exif_imagetype($_FILES[veiculo_imagem_1][tmp_name]);
//move_uploaded_file($_FILES[file][tmp_name], "/tmp/mytmp");

echo "\nPOST\n";
print_r($_POST);
echo "\nGET\n";
print_r($_GET);