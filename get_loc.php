<?php
$url = "https://geocode-maps.yandex.ru/1.x/?";
$apikey = file_get_contents("key.txt");//ключ из файла
$geocode = $_GET['location'];
//параметры http запроса на геокодитрование
$params = array(
    'apikey' => $apikey,
    'geocode' => $geocode,
    'format' => 'json'
);
$response = file_get_contents($url.http_build_query($params));//ответ апи
$currentLocation = json_decode($response, true);//делаем из json ассоциативный массив
if($currentLocation['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found']>0){
    //второй запрос нужен для kind, он работает с координатами
    $params = array(
        'apikey' => $apikey,
        'geocode' => $currentLocation['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'],
        'kind' => 'metro',
        'format' => 'json'
    );
    $response = file_get_contents($url.http_build_query($params));//ответ апи
    $metroLocation = json_decode($response, true);//делаем из json ассоциативный массив
    //записываем результат в переменную метро
    if($metroLocation['response']['GeoObjectCollection']['metaDataProperty']['GeocoderResponseMetaData']['found']>0) {
        $metro=$metroLocation['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['name'];
    }
    else{
        $metro='Поблизости нет метро';
    }
    //формируем ответ
    $response=[
        "status"=>true,
        "address"=>$currentLocation['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'],
        "coordinates"=>$currentLocation['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'],
        "metro"=>$metro
    ];
}
//поиск неудачен, формируем ответ
else{
    $response=[
        "status"=>false,
        "address"=>'Я не нашел такой адрес'
    ];
}
//массив в json
echo json_encode($response);