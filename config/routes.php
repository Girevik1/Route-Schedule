<?php
/**
 * Возвращает массив с роутами
 */
return array(
    //Просмотр маршрутов
    'routes/view' => 'routes/view',
    //Регистрация маршрута
    'routes/register' => 'routes/register',
    //Удаление маршрута из таблицы
    'delete/id/([0-9]+)' => 'routes/deleteById/$1',
    //Вывод списка маршрутов
    '' => 'routes/index',

);