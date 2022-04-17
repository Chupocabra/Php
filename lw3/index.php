<!DOCTYPE html>
<html lang="en">
    <link href="style.css" rel="stylesheet" type="text/css">
<head>
    <meta charset="UTF-8">
    <title>Lw6Php</title>
</head>
<body>
    <form class="contact-form" id="contact-form" method="POST" enctype="multipart/form-data">
        <h3>Форма обратной связи</h3>
        <p class="contact-form__description"></p>
        <div class="form-row">
            <input id="fio" type="text" required ><label for="fio">ФИО</label>
        </div>
        <div class="form-row">
            <input id="email" required type="email"><label for="email">Почта</label>
        </div>
        <div class="form-row">
            <input id="tel" required type="tel"><label for="tel">Телефон</label>
        </div>
        <div class="form-row2">
            <textarea id="comm" rows="5" required></textarea>
            <label for="comm">Комментарий</label>
        </div>
        <div class="success" id="validationSuccess"></div>
        <p><button class="submit" id="send">Отправить!</button></p>
        <div class="error" id="validationError"></div>
    </form>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>