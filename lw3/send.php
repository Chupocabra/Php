<?php
//подключаем бд
//send.php проверяет пользователя по email
//если пользователь существует, проверяет чтобы он оставлял отзывы не чаще чем раз в час
//если пользователя нет, добавляет его в бд и выводит специально сообщение
require_once 'database.php';
//получаем все наши поля get запросом
$fio = htmlspecialchars($_GET['fio']);
$email = htmlspecialchars($_GET['email']);
$tel = htmlspecialchars($_GET['tel']);
$comm = htmlspecialchars($_GET['comm']);
$time = date('y-m-d H:i:s');//еще будем записывать время в бд. Тут в формате Unix
//получим последнее время отправки с текущей почты
$sth = $dbh->prepare("SELECT MAX(time) FROM user where email='$email'");
$sth->execute();
$lastTime = $sth->fetchAll();
$time_passed = time() - strtotime($lastTime[0][0]);//времени прошло
$time_to_wait= round((3600 - $time_passed)/60);//времени осталось, в минутах
if($time_passed < 3600){
    echo "Вы сможете отправить следующий комментарий через $time_to_wait минут";
    die();
}

$dbh->query("INSERT INTO user (fio, email, phone, comment, time) VALUES ('$fio', '$email', '$tel', '$comm', '$time');");
$message = "ФИО: $fio\nE-mail: $email\nТелефон: $tel\nКомментарий: $comm\nВремя: $time";//письмо "менеджеру"
$mailSuccess = mail("komari4ev@gmail.com", 'PhpLw6', $message);
if(!$mailSuccess){
    //в случае неудачной отправки выведем ошибку
    echo error_get_last();
    die();
}
//тут время в формате по заданию + полтора часа
$contact_time = date('H:i:s d.m.y', time() + 90*60);
//для вывода Имени, Фамилии, Отчества
$FIO=preg_split('/ /', $fio);
//специальное сообщение
echo "Оставлено сообщение из формы обратной связи.
<br>Имя: $FIO[1]
<br>Фамилия: $FIO[0]
<br>Отчество: $FIO[2]
<br>E-mail: $email
<br>Телефон: $tel
<br>С Вами свяжутся после $contact_time";

