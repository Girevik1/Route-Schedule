<?php
class Couriers
{
    /**
     * Возвращает список курьеров
     *
     */
    public static function getCouriersList()
    {
        $db = Db::getConnection();
        $courierList = array();
        $sql = "SELECT * FROM courier";
        $result = $db->prepare($sql);
        $result->execute();

        $i = 0;
        while ($row = $result->fetch()) {
            $courierList[$i]['courier_name'] = $row['courier_name'];
            $courierList[$i]['id_courier'] = $row['id_courier'];
            $i++;
        }

        return $courierList;
    }

    /**
     * Возвращает курьера по id
     *
     */
    public static function getByCourierId($courier)
    {
        $db = Db::getConnection();

        $sql = 'SELECT id_courier, courier_name FROM courier WHERE id_courier = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $courier, PDO::PARAM_INT);
        $result->execute();

        return $result->fetch();
    }

    /**
     * Проверяет не занят ли курьер в определенные даты
     *
     */
    public static function checkCourierOnFree($courier, $date, $fullTimeDateFromSec)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM `trip` WHERE courier_id = :courier_id
                AND (:departure_date >= departure_date AND :departure_date <= return_date
                OR :return_date >= departure_date AND :return_date <= return_date
                OR :departure_date <= departure_date AND :return_date >= return_date)';


        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':courier_id', $courier, PDO::PARAM_STR);
        $result->bindParam(':departure_date', $date, PDO::PARAM_STR);
        $result->bindParam(':return_date', $fullTimeDateFromSec, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

}

