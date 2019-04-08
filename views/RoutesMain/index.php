<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Расписание поездок</title>
</head>
<body>

<div class="row justify-content-center">
    <h1> Расписание поездок курьеров в регионы</h1>
</div>
<hr>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-4">
            <h5> Выберите необходимую дату</h5>
            <form action="/routes/view" method="post">
                <div class="form-group">
                    <label for="inputDate">Введите дату:</label>
                    <input type="date" name="date_from" class="form-control">
                </div>
                <input type="submit" class="btn btn-primary" name="submit" value="Обновить"/>
            </form>
        </div>
    </div>
</div>
<br>
<?php if (isset($routesList)) { ?>
<div class="container">
    <p><?php foreach ($routesList as $routesItem) { ?>
        Все поездки на <?= $routesItem['departure_date'];
        break(1); ?></p>
    <?php }; ?>

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Дата выезда с Москвы</th>
            <th>Дата приезда в регион</th>
            <th>Дата приезда в Москву</th>
            <th>Регион</th>
            <th>ФИО курьера</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($routesList)) { ?>
            <?php foreach ($routesList as $routesItem) { ?>

                <tr>
                    <td><?= $routesItem['departure_date']; ?></td>
                    <td><?= $routesItem['arrival_date']; ?></td>
                    <td><?= $routesItem['return_date']; ?></td>
                    <td>Москва - <?= $routesItem['name']; ?></td>
                    <td><?= $routesItem['courier_name']; ?></td>
                </tr>
            <?php }; ?>
        <?php } else {
            echo "<h3 style='color: red'>За выбранную дату поездок не было</h3>";
        }; ?>
        </tbody>
    </table>
    <?php }; ?>
</div>
<hr>
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-4">
            <?php if (!empty($result)) { ?>
                <ul>
                    <
                    li>Новый маршрут зарегистрирован</li>
                </ul>
            <?php } else { ?>
                <?php if (isset($errors)) { ?>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li style='color: red'><?php echo $error; ?></></li>
                        <?php endforeach; ?>
                    </ul>
                <?php }; ?>
            <?php }; ?>
            <h5>Добавить новый маршрут</h5>

            <form action="/routes/register" method="post">

                <div class="form-group">
                    <label for="name">Регион</label>
                    <select class="form-control" name="region">
                        <?php foreach ($regionsList as $regionsItem) { ?>
                            <option value="<?= $regionsItem['id'] ?>"> <?= $regionsItem['name'] ?></option>
                        <?php }; ?>
                    </select>

                    <label for="inputDate">Дата отправления:</label>
                    <input type="date" name="date" class="form-control">

                    <label for="task">ФИО курьера</label>
                    <select class="form-control" name="courier_name">
                        <?php foreach ($couriersList as $couriersItem) { ?>
                            <option value="<?= $couriersItem['id_courier'] ?>"> <?= $couriersItem['courier_name'] ?></option>
                        <?php }; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Добавление</button>

            </form>

        </div>
    </div>
</div>
<br>
<div class="container">
    <p>Все поездки</p>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Регион</th>
            <th>Дата выезда из Москвы</th>
            <th>ФИО курьера</th>
            <th>Дата прибытия в регион</th>
            <th>Дата прибытия в Москву</th>
            <th>Удаление</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($fullLists as $ListsItem) { ?>
            <tr>
                <td><?= $ListsItem['name'] ?></td>
                <td><?= $ListsItem['departure_date'] ?></td>
                <td><?= $ListsItem['courier_name'] ?></td>
                <td><?= $ListsItem['arrival_date'] ?></td>
                <td><?= $ListsItem['return_date'] ?></td>
                <td><a href="/delete/id/<?= $ListsItem['id'] ?>">Удалить</a></td>
            </tr>
        <?php }; ?>
        </tbody>
    </table>

</div>

</body>
</html>


