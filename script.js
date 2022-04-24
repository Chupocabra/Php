//по нажатию на кнопку формы, выполняем
$('.submit').click(function (e){
    e.preventDefault();
    var location = document.getElementById('location').value;//поле с адресом
    clearValue();
    console.log(location);
    //ajax запрос c нашим адресом
    $.ajax({
        url: 'get_loc.php',
        method: 'get',
        dataType: 'json',
        data: {
            location: location
        },
        success (data) {//если ошибок не возникло
            if(data.status) {//и адрес нашелся, get_loc вернул true
                //убираем только чтение, записываем адрес
                document.getElementById('address').readOnly=false;
                document.getElementById('address').value=data.address;
                //записываем координаты
                document.getElementById('coordinates').readOnly=false;
                document.getElementById('coordinates').value=data.coordinates;
                //записываем метро
                document.getElementById('metro').readOnly=false;
                document.getElementById('metro').value=data.metro;
            }
            else {
                document.getElementById('address').readOnly=false;
                document.getElementById('address').value=data.address;
            }
        }
    });
});
//функция очищает инпуты формы, ничего не возвращает
function clearValue(){
    document.getElementById('address').value='';
    document.getElementById('coordinates').value='';
    document.getElementById('metro').value='';
}
