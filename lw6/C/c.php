<?php

//читаем файл в массив, в массиве будут учтены весы
//(так, баннер с весом 4 будет записан 4 раза)
//передаем файл с входными данными
//функция возвращает массив, в котором учтены веса баннеров
function read_file($inputFile): array
{
    $banner=array();
    $fp = fopen($inputFile, 'r');
    while (true) {
        $str = fgets($fp);
        if (!empty($str)) {
            list($id, $weight) = explode(" ", $str);//делим по пробелу
            for ($i=0; $i<$weight; $i++) {//записываем баннер столько раз, сколько он весит
                array_push($banner, $id);
            }
        } else {
            break;
        }
    }
    return $banner;
}
//функция "показывает" 10^6 баннеров и считает долю показа баннеров
//параметр -- массив  с баннерами
//возвращает массив, в котором посчитаны доли показа для каждого баннера
function show_banners($banners): array
{
    //ключ массива -- баннер, значение -- число показов
    $massive=array();
    //записываем ключи (баннеры), со значением ноль
    foreach ($banners as $banner) {
        $massive[$banner] = 0;
    }
    //подсчитываем число показов
    for ($i=0; $i < pow(10, 6); ++$i) {//pow(10, 6)
        $randomBanner = rand(0, count($banners)-1);//выбираем баннер случайным образом
        $massive[$banners[$randomBanner]]+=1;//массив['баннер']
    }
    foreach ($massive as &$banner) {
        //считаем долю показа, округляя до 6и знаков
        //$banner = number_format($banner/pow(10, 6), 6, '.');
        $banner = sprintf("%01.6f", $banner/pow(10, 6));
    }
    return $massive;
}
//функция преобразует массив в строки
//параметр -- массив с долями показа каждого баннера
//возвращает строчки-ответ
function massiveToAnswer($massive): string
{
    $arr=array_keys($massive);//получаем баннеры
    $str='';
    for ($i=0; $i<count($arr); ++$i) {
        $str.=$arr[$i].' '.$massive[$arr[$i]]."\n";
    }
    return $str;
}
$inputFiles = glob('./test/*.dat');
$outputFiles = glob('./test/*.ans');
for ($i=0; $i < count($inputFiles); ++$i) {//все файлы count($inputFiles)
    $banners=read_file($inputFiles[$i]);
    $number=$i+1;
    echo "\t$number";
    echo "\nМой ответ:\n";
    echo massiveToAnswer(show_banners($banners));
    echo "Ответ\n";
    echo $answer=file_get_contents($outputFiles[$i]);
    echo "\n";
}
