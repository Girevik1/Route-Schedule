<?php

class Routes
{
    /**
     * Возвращает список регионов
     *
     */
    public static function getRegionsList()
    {
        $db = Db::getConnection();
        $regionList = array();
        $sql = "SELECT * FROM  region";
        $result = $db->prepare($sql);
        $result->execute();

        $i = 0;
        while ($row = $result->fetch()) {
            $regionList[$i]['id'] = $row['id'];
            $regionList[$i]['name'] = $row['name'];
            $i++;
        }

        return $regionList;

    }

    /**
     * Возвращает регион по id
     *
     */
    public static function getByRegionId($regionId)
    {
        $db = Db::getConnection();

        $sql = 'SELECT id, name, duration FROM region WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $regionId, PDO::PARAM_INT);
        $result->execute();

        return $result->fetch();
    }

    /**
     * Возвращает массив маршрутов, дат, курьеров
     *
     */
    public static function getRoutesFullList()
    {
        $db = Db::getConnection();
        $fullInformationList = array();

        $sql = "SELECT trip.id, trip.departure_date, trip.arrival_date, trip.return_date, region.name, courier.courier_name 
                FROM trip LEFT JOIN courier ON trip.courier_id = courier.id_courier LEFT JOIN region 
                ON trip.region_id = region.id ORDER BY id DESC LIMIT 5";
        $result = $db->prepare($sql);
        $result->execute();

        $i = 0;
        while ($row = $result->fetch()) {
            $fullInformationList[$i]['id'] = $row['id'];
            $fullInformationList[$i]['courier_name'] = $row['courier_name'];
            $fullInformationList[$i]['name'] = $row['name'];
            $fullInformationList[$i]['departure_date'] = $row['departure_date'];
            $fullInformationList[$i]['arrival_date'] = $row['arrival_date'];
            $fullInformationList[$i]['return_date'] = $row['return_date'];
            $i++;
        }

        return $fullInformationList;
    }

    /**
     * Возвращает массив маршрутов, курьеров по дате
     *
     */
    public static function getRoutesListByDate($start)
    {

        $db = Db::getConnection();
        $tasksList = array();

        $sql = "SELECT trip.id, trip.departure_date, trip.arrival_date, trip.return_date, region.name, courier.courier_name 
                FROM trip LEFT JOIN courier ON trip.courier_id = courier.id_courier LEFT JOIN region ON trip.region_id = region.id 
                WHERE departure_date = :start";
        $result = $db->prepare($sql);
        $result->bindParam(':start', $start, PDO::PARAM_STR);
        $result->execute();

        $i = 0;
        while ($row = $result->fetch()) {
            $tasksList[$i]['id'] = $row['id'];
            $tasksList[$i]['courier_name'] = $row['courier_name'];
            $tasksList[$i]['name'] = $row['name'];
            $tasksList[$i]['departure_date'] = $row['departure_date'];
            $tasksList[$i]['arrival_date'] = $row['arrival_date'];
            $tasksList[$i]['return_date'] = $row['return_date'];
            $i++;
        }

        return $tasksList;
    }

    /**
     * Регистрация маршрута
     *
     */
    public static function registerRoute($regionId, $courier, $date, $fullTimeDateFromSec, $halfTimeDateFromSec)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO trip (region_id, courier_id, departure_date, return_date, arrival_date)'
            . 'VALUES (:region_id, :courier_id, :departure_date, :return_date, :arrival_date)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':region_id', $regionId, PDO::PARAM_STR);
        $result->bindParam(':courier_id', $courier, PDO::PARAM_STR);
        $result->bindParam(':departure_date', $date, PDO::PARAM_STR);
        $result->bindParam(':return_date', $fullTimeDateFromSec, PDO::PARAM_STR);
        $result->bindParam(':arrival_date', $halfTimeDateFromSec, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Удаляем маршрут по id
     *
     */
    public static function deleteRouteById($id)
    {
        $db = Db::getConnection();

        $sql = 'DELETE FROM trip WHERE id = :id';
        $result = $db->prepare($sql)
        ;
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->execute();

        return $result->fetch();
    }

}