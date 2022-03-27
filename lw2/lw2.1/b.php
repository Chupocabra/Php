<?php
//функция, которая объединяет массивы в одну строку.
//принимает блоки и их количество, возвращает строчку (полный ipv6)
function make_line($block, $k){
    $some_string='';
    if($k==1){//если не было ::
        for($i=0; $i<8; ++$i){
            if($i==0)$some_string=$some_string . $block[0][$i];
            else $some_string=$some_string . ':' . $block[0][$i];
        }
    }
    else{
        for($i=0; $i<count($block[0]); ++$i){//первая половина
            if($i==0)$some_string=$some_string . $block[0][$i];
            else $some_string=$some_string . ':' . $block[0][$i];
        }
        for($i=0; $i<8-count($block[1])-count($block[0]); ++$i){//часть с нулями, тут рыскрываем ::
            $some_string=$some_string . ':0000';
        }
        for($i=0; $i<count($block[1]); ++$i){//вторая половина
            $some_string=$some_string . ':' . $block[1][$i];
        }
    }
    return $some_string;
}
//функция выполняет преобразование из сокращенного в полный вид.
//принимает файл ввода, возвращает массив строчек ipv6
function do_it($inputFile)
{
    $some_array=array(1);//массив, где будем хранить строчки
    $input_array=file($inputFile);//массив с входами
    for($c=0; $c<count($input_array); $c++){
        $block=preg_split('/::/', trim($input_array[$c]));//делим на две части по ::
        for($k=0; $k<count($block); $k++){//для каждой части
            $second_block[$k]=preg_split('/:/', $block[$k]);//делим на маленикие блоки по :
            for($j=0; $j<count($second_block[$k]); $j++){
                while(strlen($second_block[$k][$j])<4){
                    $second_block[$k][$j]='0'.$second_block[$k][$j];//дописываем к ним нули
                }
            }
        }
        $some_array[$c]=make_line($second_block, $k);//массив строчек результатов
    }
    return $some_array;
}
//функция сравнивает наши результаты с ответами
//принимает наш массив строк и файл с ответами, возвращает результат сравнения
function compareLines($full_ipv6, $outputFile){
    $output_array=file($outputFile);//делим ответы на массивы по строчкам
    $flag=true;//метка, показывает успешность сравнения

    for($j=0; $j<count($full_ipv6); ++$j){
        $result=trim($full_ipv6[$j]);
        $answer=trim($output_array[$j]);
        if($result!=$answer){//если есть ошибка
            $j++;//это для вывода
            echo "\n$j $result!=$answer";//покажем ее
            $j--;
            $flag=false;//вернем false
        }
    }
    return $flag;//ошибок не найдено, вернем true
}
$inputFiles=glob('./B/*.dat');//файлы с входами
$outputFiles=glob('./B/*.ans');//файлы с ответами
for($i=0; $i<count($inputFiles); ++$i){
    $full_ipv6=do_it($inputFiles[$i]);
    $flag=compareLines($full_ipv6, $outputFiles[$i]);
    $i++;
    if($flag) echo "\n$i.All ok";
    else echo "\n$i.There some problem";
    $i--;
}