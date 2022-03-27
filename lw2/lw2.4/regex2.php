<?php
//функция возвращает новую строчку
function changeString($matches){
    $newString=preg_split('#http://asozd\.duma\.gov\.ru/main\.nsf/\(Spravka\)\?OpenAgent&RN=#', $matches[0]);//отделяем ссылку
    $newString=preg_split('#&#', $newString[1]);//отделяем номер законопроекта
    $matches='http://sozd.parlament.gov.ru/bill/'.$newString[0];//дописываем номер в нашу новую строчку
    return $matches;
}

$fp = @fopen("./example2.txt", "r") or die("не удалось прочесть файл");//читаем построчно файл со строками
if ($fp) {
    while (($buffer = fgets($fp, 4096)) !== false) {
        echo preg_replace_callback('#http://asozd\.duma\.gov\.ru/main\.nsf/\(Spravka\)\?OpenAgent&RN=[0-9-]+&\d+#', 'changeString', $buffer);
    }
    fclose($fp);
}
