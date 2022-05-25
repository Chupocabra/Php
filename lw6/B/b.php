<?php

//функция для прочтения файла с входными данными
//передаем функции файл с начальными данными
//функция возвращает массив с данными о каждом разделе каталога
function read_file($inputFile): array
{
    $catalog = array();//массив хранит id, name left key и right key каждого каталога
    $fp = fopen($inputFile, 'r');
    while (true) {
        $str = fgets($fp);
        if (!empty($str)) {
            list($info['id'], $info['name'], $info['lk'], $info['rk']) = explode(" ", $str);//делим по пробелу
            $catalog[] = $info;
        } else {
            break;
        }
    }
    return $catalog;
}
//функция делает из массива меню (из каталога -- меню)
//передаем массив с данными о каждом разделе каталога
//возвращает вложенное меню
function do_menu($catalog): string
{
    $number = 1;//номер узла
    $treeLevel = 0;//уровень дерева
    $numberOfCategories=count($catalog);//количество разделов
    $menu='';//формируем вложенное меню
    while ($numberOfCategories!=0) {
        foreach ($catalog as $category) {
            //left key- номер для входа в узел
            if ($category['lk']==$number) {
                $menu.=str_repeat('-', $treeLevel);//рисуем -
                $menu.=$category['name']." \n";
                $treeLevel++;//спускаемся
                $number++;
                $numberOfCategories--;//уменьшаем число оставшихся узлов
                break;
            }
            //кшпре key- номер для выхода из узла
            elseif ($category['rk']==$number) {
                $treeLevel--;//поднимаемся
                $number++;
                break;
            }
        }
    }
    return $menu;
}
//функция сравнивает полученное вложенное меню с правильным
//параметры: номер теста, полученное меню, файл с ответом
//возвращает true или false
function compareAnswers($i, $result, $outputFile): bool
{
    $answer=file_get_contents($outputFile);
    $number=$i+1;
    echo "\n$number. ";
    if ($answer!=$result) {
        echo "\tМой ответ\n".$result;
        echo "\tПравильный ответ\n".$answer."\n";
        return false;
    } else {
        return true;
    }
}
$inputFiles = glob('./test/*.dat');
$outputFiles = glob('./test/*.ans');
for ($i=0; $i < count($inputFiles); ++$i) {//все файлы
    if (compareAnswers($i, do_menu(read_file($inputFiles[$i])), $outputFiles[$i])) {
        echo "Верно";
    } else {
        echo "Не верно";
    }
}
