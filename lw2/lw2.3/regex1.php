<?php

function multiply($matches){
    $matches[1]*=2;
    return "'$matches[1]'";
}

$fp = @fopen("./strings.txt", "r") or die("не удалось прочесть файл");//читаем построчно файл со строками
if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
        echo preg_replace_callback('/\'([0-9]+)\'/', 'multiply', $buffer);
    }
    fclose($fp);
}