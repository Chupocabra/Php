<?php
//функция проверяет строчку
//вернет OK или FAIL
function checkString($string)
{
    preg_match('/\> S \d+ \d+/', $string, $string1);
    $string2=preg_split('/ /', $string1[0]);//получаем строку с правилом и параметрами[2][3]
    preg_match('#\<(.*?)\>#', $string, $string3);//полчаем строку(значение) $string3[1]
    if(strlen($string3[1])>=$string2[2] && strlen($string3[1])<=$string2[3]){//проверяем длину
        $output='OK';
    }
    else{
        $output='FAIL';
    }
    return $output;
}
//функция проверяет число
//вернет OK или FAIL
function checkNumber($string)
{
    preg_match('/\> N [-0-9]+ \d+/', $string, $string1);
    $string2=preg_split('/ /', $string1[0]);//получаем строку с правилом и параметрами[2][3]
    preg_match('/\<[-0-9]+\>/', $string, $string3);//смотрим с кавычками
    if(count($string3)==0){
        $output='FAIL';//если в кавычках ничего не было, вернем фейл
        return $output;
    }
    preg_match('/[-0-9]+/', $string3[0], $string3);//убираем кавычки
    if($string3[0]>=$string2[2] && $string3[0]<=$string2[3]){//проверяем число
        $output='OK';
    }
    else{
        $output='FAIL';
    }
    return $output;
}
//функция проверяет номер телефона
//вернет OK или FAIL
function checkPhone($string)
{
    preg_match('#\<(.*?)\>#', $string, $string2);//получаем номер
    if(preg_match('/^\+7 \([0-9]{3,3}\) [0-9]{3,3}-[0-9][0-9]-[0-9][0-9]$/', $string2[1], $string3)){
        $output='OK';
    }
    else{
        $output='FAIL';
    }
    return $output;
}
//функция проверяет дату
//вернет OK или FAIL
function check_date($string)
{
    preg_match('#\<(.*?)\>#', $string, $string2);//получаем дату и время
    if(preg_match('/^([1-9]|0[1-9]|[1-2][0-9]|3[0-1])\.([1-9]|0[1-9]|1[0-2])\.([0-9]{4}) ([0-2][0-9]|[0-9]):[0-5][0-9]/', $string2[1], $string3)){
        if(checkdate($string3[2], $string3[1], $string3[3])){//проверим правильность даты
            $output='OK';
        }
        else{
            $output='FAIL';
        }
    }
    else{
        $output='FAIL';
    }
    return $output;
}
//функция проверяет почту
//вернет OK или FAIL
function check_email($string)
{
    preg_match('#\<(.*?)\>#', $string, $string2);//получаем строчку почты
    if(1==preg_match('/^[0-9a-zA-Z][0-9a-zA-Z_]{3,29}\@[A-Za-z]{2,30}\.[a-z]{2,10}/', $string2[1], $string3)){
        $output_array='OK';
    }
    else{
        $output_array='FAIL';
    }
    return $output_array;
}

$inputFiles=glob('./C/*.dat');//файлы с входами
$outputFiles=glob('./C/*.ans');//файлы с ответами
for($i=0; $i<count($inputFiles); ++$i){
    $flag=true;//флаг ошибки
    $input_array=file($inputFiles[$i]);//массив строчек i-го файла
    $output_array=file($outputFiles[$i]);
    for($j=0; $j<count($input_array); ++$j){
        $result='wrong';//по умолчанию валидация не пройдена
        preg_match('/\> S|\> N|\> P|\> D|\> E/', $input_array[$j], $string);//ищем тип валидации
        $string=preg_split('/ /', $string[0]);//[0]-кавычка, [1]-буква
        switch (trim($string[1])){//выбираем наш тип
            case 'S':
                $result=checkString($input_array[$j]);
                break;
            case 'N':
                $result=checkNumber($input_array[$j]);
                break;
            case 'P':
                $result=checkPhone($input_array[$j]);
                break;
            case 'D':
                $result=check_date($input_array[$j]);
                break;
            case 'E':
                $result=check_email($input_array[$j]);
                break;
        }
        if($result!=trim($output_array[$j])){//сравниваем с ответом
            $flag=false;//в случае неравенства, установим флаг false
        }
    }
    ++$i;
    if(!$flag){//выведем результат в соответствии с флагом
        echo "\n$i.Error";
    }
    else echo "\n$i.Okay";
    --$i;
}