<?php

include_once ROOT . '/models/Couriers.php';
include_once ROOT . '/models/Routes.php';

class RoutesController extends Controller
{
    /**
     * Action для "Список маршрутов"
     */
    public function actionIndex()
    {
        $regionsList = Routes::getRegionsList();
        $couriersList = Couriers::getCouriersList();
        // Выводим информацию по маршруту, курьеру и дате в "информационное поле"
        $fullLists = Routes::getRoutesFullList();

        require_once(ROOT . '/views/RoutesMain/index.php');

        return true;
    }

    /**
     * Action для "Просмотра маршрутов по дате"
     */
    public function actionView()
    {


        if (isset($_POST['submit'])) {
            $date_from = $_POST['date_from'];

            // Преобразовали строку в дату в нужном формате
            $date1 = strtotime($date_from);
            $start = date("Y-m-d H:i:s", $date1);

            // Метод для просмотра маршрутов по дате
            $routesList = Routes::getRoutesListByDate($start);

            // Выводим список регионов и курьеров
            $regionsList = Routes::getRegionsList();
            $couriersList = Couriers::getCouriersList();

            // Выводим информацию по маршруту, курьеру и дате в "информационное поле"
            $fullLists = Routes::getRoutesFullList();

            require_once(ROOT . '/views/RoutesMain/index.php');

            return true;
        }

    }

    /**
     * Action для "Регистрация маршрута"
     */
    public function actionRegister()
    {
        $regionsList = Routes::getRegionsList();
        $couriersList = Couriers::getCouriersList();
        // Выводим информацию по маршруту, курьеру и дате в "информационное поле"
        $fullLists = Routes::getRoutesFullList();

        $result = false;

        // Флаг ошибок
        $errors = false;

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена
            // Получаем данные из формы
            $courier = $_POST['courier_name'];
            $regionId = $_POST['region'];
            $date = $_POST['date'];

            // Валидация даты
            if (empty($date)) {
                $errors[] = 'Выберите дату';
            }

            // получили название города по id
            // $regionId = Routes::getByRegionId($regionId);
            // $nameCity = $regionId['name'];

            // получили фио курьера по id
            // $courier = Couriers::getByCourierId($courier);
            // $nameCourier = $courier['courier_name'];

            // получили расстояние по маршруту (туда и обратно)
            $regionById = Routes::getByRegionId($regionId);
            $duration = $regionById['duration'];

            // преобразовали дату выезда в секунды
            $secOfDate = strtotime($date);

            // получили расстояние по маршруту (в одну точку, по плану)
            $halfTrip = $duration / 2;

            // необходимое время курьеру (в регион)
            $halfTime = $secOfDate + $halfTrip;

            // необходимое время курьеру (туда и обратно)
            $fullTime = $secOfDate + $duration;


            // Время возвращения курьера в Москву преобразовали из секунды в дату
            $fullTimeDateFromSec = date("Y-m-d", $fullTime);
            // Потраченное время курьером в регион преобразовали из секунды в дату
            $halfTimeDateFromSec = date("Y-m-d", $halfTime);



            // Валидация курьера на совпадения дат его поездок
            if (Couriers::checkCourierOnFree($courier, $date, $fullTimeDateFromSec)) {
                $errors[] = 'Этот маршрут в это время уже используется курьером';
            }
            // Если нет ошибок, то регистрируем
            if ($errors == false) {
                // Регистрируем маршрут
                $result = Routes::registerRoute($regionId, $courier, $date, $fullTimeDateFromSec, $halfTimeDateFromSec);
            }

            // Подключаем вид
            require_once(ROOT . '/views/RoutesMain/index.php');
            return true;
        }
        // если форма пуста отправляем браузер обратно
        return $this->redirect('/');
    }


    /**
     * Action для "Удаления маршрута из списка таблицы"
     */
    public function actionDeleteById($id)
    {

            Routes::deleteRouteById($id);

        return $this->redirect('/');

    }
}