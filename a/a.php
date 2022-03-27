<?php

function do_it($inputFile)
{
    $balance=0;//наш баланс, его сравним с ответом
    $file=fopen($inputFile, 'r');
    $n=fgets($file);//n-количество ставок
    for($i=0; $i<$n; ++$i){
        $str=fgets($file);
        list($a, $s, $r) = explode(' ', $str);//разделяем на
        //a-идентификатор игры
        //s-сумма ставки
        //r-результат игры
        $balance=$balance-$s;//считаем деньги
        $bet[$a][$r]=$s;//запоминаем ставку
    }
    $m=fgets($file);//m-количество игр
    for($j=0; $j<$m; ++$j){
        $str=fgets($file);
        list($b, $c, $d, $k, $t) = explode(' ', $str);//разделяем на
        //b-идентификатор игры
        //c-коэффициент на победу L
        //d-коэффициент на победу R
        //k-коэффициент на ничью D
        //t-результат игры L, R, D
        if(isset($bet[$b][$t])) {//если такая ставка существует
            switch (trim($t)) {//для совпадений добавляем выигрыш
                case 'L':
                    $balance+=$bet[$b][$t]*$c;
                    break;
                case 'R':
                    $balance+=$bet[$b][$t]*$d;
                    break;
                case 'D':
                    $balance+=$bet[$b][$t]*$k;
                    break;
            }
        }
    }
    fclose($file);
    return $balance;
}
$inputFiles=glob('./A/*dat');//файлы с входами
$outputFiles=glob('./A/*ans');//файлы с ответами
for($i=0; $i<count($inputFiles); ++$i){
    $res=do_it($inputFiles[$i]);//баланс из функции
    $ans=trim(fgets(fopen($outputFiles[$i], 'r')));//баланс из ответа
    $i++;//это для вывода пунктов
    if ($res == $ans){
        echo "\n$i. All ok";
    }
    else{
        echo "\n$i. Function result:$res; answer:$ans";
    }
    $i--;//возвращаем значение
}
