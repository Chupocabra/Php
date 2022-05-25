<?php

//функция для получения статистики по баннерам
//параметры файл с начальными данными
//возвращает число показов, идентификатор и время последнего показа баннеров
function do_statistic($inputFile): string
{
    $fp = fopen($inputFile, 'r');//открываем файл на чтение
    $banners=array();//массив со всеми баннерами (тут они дублируются)
    while (true) { //считываем все строки
        $str = fgets($fp);
        if (!empty($str)) {
            list($banner_info['id'], $banner_info['time']) = explode("\t", $str);//делим по табуляции
            $banner_info['num']=1;//число всех баннеров - один
            $banners[] = $banner_info;
        } else {
            break;
        }
    }
    $ans_array = array();//массив с баннерами без повторений
    for ($i=0; $i<count($banners); ++$i) {
        $j=-1;//для подсчета баннеров в foreach
        $flag=false;//флаг меняется в случае нахождения баннера с таким же id
        foreach ($ans_array as $banner) {
            $j++;
            if ($banner['id']==$banners[$i]['id'] && $i!=$j) {
                //увеличиваем счетчик для этого баннера
                $banners[$i]['num']=$banner['num']+1;
                //проверяем какой баннер был показан последним
                if (strtotime($banner['time'])>strtotime($banners[$i]['time'])) {
                    $banners[$i]['time'] = $banner['time'];
                }
                $flag=true;
                $ans_array[$j]=$banners[$i];//меняем старый баннер в масиве на последний показанный
            }
        }
        if (!$flag) {
            $ans_array[]=$banners[$i];//заносим в массив новый баннер
        }
    }
    $result='';
    //формируем ответ
    for ($i=0; $i<count($ans_array); ++$i) {
        $result.=$ans_array[$i]['num'].' '.$ans_array[$i]['id'].' '.trim($ans_array[$i]['time'])."\n";
    }
    return $result;
}
//функция для сравнения получившегося ответа и правильного
//параметры номер теста, путь к файлу с ответом, результат преобразований
//вовзращает true в случае совпадения и false в противном случае
function compare_lines($number, $outputFile, $result): bool
{
    $answer=file_get_contents($outputFile);
    $number+=1;
    echo "\n$number. ";
//    echo "\n\t$number Решение\n".trim($result);
//    echo "\n\t$number Ответ\n".$answer."\n";
    $flag=true;//метка, показывает успешность сравнения
    if (trim($result)!=$answer) {//если есть ошибка
        $flag=false;//вернем false
    }
    return $flag;//ошибок не найдено, вернем true
}
//файлы с данными и с ответами для тестов
$inputFiles = glob('./test/*.dat');
$outputFiles = glob('./test/*.ans');
for ($i=0; $i < count($inputFiles); ++$i) {//все файлы
    $result=do_statistic($inputFiles[$i]);//находим ответ
    if (compare_lines($i, $outputFiles[$i], $result)) {
        echo "Верно";
    } else {
        echo "Не верно";
    }
}
