<?php
//функция сокращает подробные margin и padding
//параметры: входная строка; строка 'margin' или 'padding'
//ничего не возвращает, переписывает переданную входную строку
function cutMargin(&$file, $string){
    //все, что внутри {}, т.е. стиль
    preg_match_all('/{[^}]*}/', $file, $styles);
    foreach ($styles[0] as $style){
        $margin=array('0', '0', '0', '0');//сокращенный margin
        $flag=0;//считаем "подробонсть"
        //находим в стиле строчки с margin
        if ($string=='padding') preg_match_all('/padding-[t|b|l|r][A-z]+:(-|)[0-9]+/', $style, $margins);
        elseif ($string=='margin') preg_match_all('/margin-[t|b|l|r][A-z]+:(-|)[0-9]+/', $style, $margins);
        //если нашли
        if(isset($margins[0])){
            foreach ($margins[0] as $marg){
                //var_dump(preg_split('/:/', $marg)[0]);
                //перебираем отступ, записываем в сокращенный массив в соответствии с порядком
                switch(preg_split('/:/', $marg)[0]){
                    case $string.'-top':
                        $margin[0]=preg_split('/:/', $marg)[1];
                        $flag+=1;
                        break;
                    case $string.'-right':
                        $margin[1]=preg_split('/:/', $marg)[1];
                        $flag+=1;
                        break;
                    case $string.'-bottom':
                        $margin[2]=preg_split('/:/', $marg)[1];
                        $flag+=1;
                        break;
                    case $string.'-left':
                        $margin[3]=preg_split('/:/', $marg)[1];
                        $flag+=1;
                        break;
                }
            }
        }
        //подстрока по которой производим поиск в строке-ответе
        $search=implode('px;', $margins[0]);
        //var_dump($search);
        if(strlen($search)>0){
            if($flag==4){//если это был подробный margin
                //var_dump(implode(';', $margins[0]));
                //если все равны
                if($margin[0]==$margin[1] && $margin[0]==$margin[2] && $margin[0]==$margin[3]){
                    $replace="$string:$margin[0]";
                }
                //если равны left и right
                elseif($margin[1]==$margin[3]){
                    //если равны top и bottom
                    if($margin[0]==$margin[2]){
                        $replace="$string:$margin[0]px $margin[1]";
                    }
                    else $replace="$string:$margin[0]px $margin[1]px $margin[2]";
                }
                else{
                    $replace="$string:".implode('px ', $margin);
                }
                //search мы заменяем на replace в строке file
                $file=str_replace($search, $replace, $file);
            }
        }
    }
}
//функция модифицирует css в соответствии с заданием
//передаем файл с исходным css
//возвращает строку--модифицированный css
function mod_file($inputFile)
{
    //считать файл
    $file=file_get_contents($inputFile);
    //удаляем комментарии
    $file=preg_replace('%/\*[^*]*\*+(?:[^/*][^*]*\*+)*/%', '', $file);
    //удаляем лишние пробелы, переводы строки, точки с запятой
    $file=str_replace(array("  ", "\r\n"), '', $file);
    $file=str_replace(array(" {"), '{', $file);
    $file=str_replace(array(": "), ':', $file);
    $file=str_replace(' > ', '>', $file);
    $file=str_replace(', ', ',', $file);
    $file=str_replace('; ', ';', $file);
    $file=str_replace(';}', '}', $file);

    //удаляем пустые стили
    $file=preg_replace('/[#.]?[a-zA-Z0-9]{1,10}>?[#.]?[a-zA-Z0-9]{1,10}{}/', '', $file);
    $file=preg_replace('/[#.]?[a-zA-Z0-9]{1,10}{}/', '', $file);

    //заменить цвета из списка
    $colorsHex=['#CD853F'=>'peru', '#FFC0CB'=>'pink',
        '#DDA0DD'=>'plum', '#FF0000'=>'red', '#FFFAFA'=>'snow', '#D2B48C'=>'tan'];
    preg_match_all('/#[0-9A-z][0-9A-z][0-9A-z][0-9A-z][0-9A-z][0-9A-z]/', $file, $color);
    foreach ($color[0] as &$col){
        if(isset($colorsHex[$col])){
            $file=str_replace($col, $colorsHex[$col], $file);
        }
    }
    //сократить шестнадцетиричные цвета
    foreach ($color[0] as &$col){
        $decrease_color=str_split($col);
        if($decrease_color[1]==$decrease_color[2]){
            if($decrease_color[3]==$decrease_color[4]){
                if($decrease_color[5]==$decrease_color[6]){
                    $file=str_replace($col, '#'.$decrease_color[1].$decrease_color[3].$decrease_color[5], $file);
                }
            }
        }
    }
    //сократить подробные margin
    cutMargin($file, 'margin');
    //сократить подробные padding
    cutMargin($file, 'padding');

    //удалить лишние единицы измерения после нулей
    $file=str_replace(' 0px', ' 0', $file);
    $file=str_replace(':0px', ':0', $file);

    return $file;
}
//функция сравнивает ответы
//передаем номер теста, результат преобразований и файл с правильным ответом
//ничего не возвращает, печатает статус проверки и ошибки
function compareAnswers($i, $result, $outputFile){
    $number=$i+1;
    $answer=file_get_contents($outputFile);
    if($result!=$answer){
        echo "\n$number Error\n";
        echo $result;
        echo "\n";
        echo $answer;
    }
    else echo "\n$number Ok\n";
}
$inputFiles = glob('./test/*.dat');
$outputFiles = glob('./test/*.ans');
for ($i=0; $i < count($inputFiles); ++$i) {//все файлы count($inputFiles)
    $result=mod_file($inputFiles[$i]);
    compareAnswers($i, $result, $outputFiles[$i]);
}