<!DOCTYPE HTML>
<html lang="en">
    <link href="style.css" rel="stylesheet" type="text/css">
<head>
    <meta charset="UTF-8">
    <title>Theme9php.REST</title>
</head>
<body>
    <form class="location_form">
        <h3>Где вы находитесь?</h3>
        <div class="form-row" style="visibility: visible">
            <input id="location" required type="text">
            <label for="location">Текущее местоположение</label>
        </div>
        <div class="form-row">
            <input id="address" readonly type="text">
            <label for="location">Структурированный адрес</label>
        </div>
        <div class="form-row">
            <input id="coordinates" readonly type="text">
            <label for="location">Координаты</label>
        </div>
        <div class="form-row">
            <input id="metro" readonly type="text">
            <label for="location">Ближайшее метро</label>
        </div>
        <p><button class="submit" id="where">Где я?</button></p>
    </form>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="script.js"></script>
</body>
</html>