//функция-клик на кнопку Отправить!
//вернет нам результат обработчика send.php
$('.submit').click(async function (e) {
    e.preventDefault();//отменяем перезагрузку
    if (validate()) {//если валидация прошла успешно
        console.log('валидация пройдено успешно!');
        hideForm();
        let url = "send.php?fio=" + fio.value;
        url += "&email=" + email.value;
        url += "&tel=" + tel.value;
        url += "&comm=" + comm.value;
        validationError.style.visibility = "visible";
        let response = await fetch(url);//get запрос, если нет options
        let text = await response.text();//ждем ответ в виде текста
        $('#validationSuccess').append(text);//записываем ответ в наш блок
    }
});

validationError.style.visibility = "hidden";
var form = document.getElementById("contact-form");
var errors = document.getElementById("validationError");
var fio = document.getElementById("fio");
var email = document.getElementById("email");
var tel = document.getElementById("tel");
var comm = document.getElementById("comm");
//функция валидирует поля формы
//если валидация не пройдена, поля подсвечиваются оранжевым цветом
//возвращает результат валидации (успешно / нет)
function validate(){
    var fio_reg = /^[А-я]{2,30} [А-я]{2,30} [А-я]{2,30}/;
    var email_reg = /^[0-9a-zA-Z][0-9a-zA-Z_]{3,29}\@[A-Za-z]{2,30}\.[a-z]{2,10}/;
    var tel_reg = /^(8|\+7)[0-9]{10}$/;
    var validation = true;
    defaultView();
    if(!fio_reg.test(fio.value)){
        validation = false;
        $('#validationError').append("<br>Формат ФИО (Фамилия Имя Отчество)");
        $('#fio').css("border-color", "#F77A52");
    }
    if(!email_reg.test(email.value)){
        validation = false;
        $('#validationError').append("<br>Формат почты (test@mail.ru)");
        $('#email').css("border-color", "#F77A52");
    }
    if(!tel_reg.test(tel.value)){
        validation = false;
        $('#validationError').append("<br>Формат телефона (81234567890 или +71234567890)");
        $('#tel').css("border-color", "#F77A52");
    }
    if (!$('#comm').val()) {
        validation = false;
        $('#validationError').append("<br>Вы оставили пустой комментарий");
        $('#comm').css("border-color", "#F77A52");
    }
    return validation;
    //return true;
}
//функция возвращает полям их первоначальный вид
function defaultView(){
    errors.style.visibility='visible';
    errors.innerText="";
    $('#fio').css("border-color", "#4a90e2");
    $('#email').css("border-color", "#4a90e2");
    $('#tel').css("border-color", "#4a90e2");
    $('#comm').css("border-color", "#4a90e2");
}
//функция прячет форму от пользователя
function hideForm(){
    $('.form-row').css("display", "none");
    $('.form-row2').css("display", "none");
    $('#send').css("display", "none");
}