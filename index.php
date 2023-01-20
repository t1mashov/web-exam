

<?php

include 'connect_db.php';

session_start();

$res = mysqli_query($connect, "
    select distinct AdmArea
    from food_shop
");
$adm_areas_code = '';
while ($row = mysqli_fetch_assoc($res)) {
    $adm_areas_code .= '<option value="'.$row['AdmArea'].'">'.$row['AdmArea'].'</option>' ;
}

$res = mysqli_query($connect, "
    select distinct District
    from food_shop
");
$districts_code = '';
while ($row = mysqli_fetch_assoc($res)) {
    $districts_code .= '<option value="'.$row['District'].'">'.$row['District'].'</option>' ;
}

$res = mysqli_query($connect, "
    select distinct TypeObject
    from food_shop
");
$obj_types_code = '';
while ($row = mysqli_fetch_assoc($res)) {
    $obj_types_code .= '<option value="'.$row['TypeObject'].'">'.$row['TypeObject'].'</option>' ;
}

//foreach($_POST as $k => $v) echo $k.' => "'.$v.'"<br>';

if (isset($_POST['page'])) {
    $i = $_POST['page'];
    if (isset($_POST['Next'])) $i += 1;
    if (isset($_POST['Previous'])) $i -= 1;
    if ($i < 0) $i = 0;
} else $i = 0;


if (isset($_POST['delete'])) {
    $query = "
        delete from food_shop
        where id = ".$_POST['delete']."
    ";
    echo $query.'<br>';
    mysqli_query($connect, $query);
}


if (isset($_POST['filter'])) {

    $name = $_POST['Name'];
    $seatsCount_start = $_POST['SeatsCount-start'];
    if ($seatsCount_start == '') $seatsCount_start = 0;
    $seatsCount_end = $_POST['SeatsCount-end'];
    if ($seatsCount_end == '') $seatsCount_end = 99999;
    $isNetObject = $_POST['IsNetObject'];
    $socialPrivileges = $_POST['SocialPrivileges'];
    $admArea = $_POST['adm_area'];
    $district = $_POST['district'];
    $objType = $_POST['obj_type'];

    $query = "
        select * from food_shop
        where SeatsCount >= ".$seatsCount_start." and
              SeatsCount <= ".$seatsCount_end;
    if ($name != '') $query .= " and Name = '".$name."'";
    if ($isNetObject != '--') $query .= " and IsNetObject = '".$isNetObject."'";
    if ($socialPrivileges != '--') $query .= " and SocialPrivileges = '".$socialPrivileges."'";
    if ($admArea != '--') $query .= " and AdmArea = '".$admArea."'";
    if ($district != '--') $query .= " and District = '".$district."'";
    if ($objType != '--') $query .= " and TypeObject = '".$objType."'";
    //$query .= " limit 10 offset ".($i*10);
    
    echo 'query = '.$query;

    $_SESSION['query'] = $query;

    //mysqli_query($connect, $query);
}

$table = '';
if (isset($_SESSION['query'])) {
    //echo $_SESSION['query']." limit 10 offset ".($i*10);
    $res = mysqli_query($connect, $_SESSION['query']." limit 10 offset ".($i*10));
    while ($row = mysqli_fetch_assoc($res)) {
        $table .= '
        <form method="post" class="row border1">
            <div class="col">'.$row['Name'].'</div>
            <div class="col">'.$row['TypeObject'].'</div>
            <div class="col">'.$row['Address'].'</div>
            <div class="col">
                <button class="btn btn-danger" name="delete" value="'.$row['id'].'">Удалить</button>    
            </div>
        </form>
        ';
    }
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>Доставка еды</title>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    
    <main>
        <h1>Административная панель</h1>
        <div class="alert alert-primary" role="alert">
            Уведомление!
        </div>
        <div>Предприятия общественного питания города Москвы</div>
        
        <div class="wrap1">
        <form method="post" class="search">
            <div class="cont1">
                <div class="c1">
                    <p>Наименование</p>
                    <input type="text" name="Name">
                    <div class="r1">
                        <div class="c1">
                            <div>
                                <p>Количество посадочных мест</p>
                                <div class="r1">
                                    <p>от</p>
                                    <input name="SeatsCount-start" type="text">
                                    <p>до</p>
                                    <input name="SeatsCount-end" type="text">
                                </div>
                            </div>
                            <div>
                                <!-- <p>Дата создания</p>
                                <div class="r1">
                                    <p>с</p>
                                    <input type="text">
                                    <p>по</p>
                                    <input type="text">
                                </div> -->
                            </div>
                        </div>
                        
                        <div class="c1">
                            <p>Является сетевым</p>
                            <select name="IsNetObject">
                                <option value="--">--</option>
                                <option value="да">да</option>
                                <option value="нет">нет</option>
                            </select>
                            <p>Льготы</p>
                            <select name="SocialPrivileges">
                                <option value="--">--</option>
                                <option value="да">да</option>
                                <option value="нет">нет</option>
                            </select>
                        </div>
                    </div>
                </div>
                    
                <div class="c1">
                    <p>Административный округ</p>
                    <select name="adm_area">
                        <option value="--">--</option>
                        <?=$adm_areas_code?>
                    </select>
                    <p>Район</p>
                    <select name="district">
                        <option value="--">--</option>
                        <?=$districts_code?>
                    </select>
                    <p>Вид объекта</p>
                    <select name="obj_type">
                        <option value="--">--</option>
                        <?=$obj_types_code?>
                    </select>
                </div>
            </div>
            <input type="hidden" name="filter">
            <div class="mybtn">
                <button type="submit" class="btn btn-secondary mybtn">Найти</button>
            </div>
            
        </form>
        </div>

        <div class="wrap1">
            <div class="result">
                <div class="container">
                    <div class="row">
                        <div class="col">Наименование</div>
                        <div class="col">Вид объекта</div>
                        <div class="col">Адрес</div>
                        <div class="col">Действия</div>
                    </div>
                    <?php
                    
                    echo $table;
                    
                    ?>

                </div>
                
            </div>
        </div>

        <div class="wrap1">
            <form method="post">
                <input type="hidden" name="page" value="<?=$i?>">
                <button name="Previous">Previous</button><?=$i+1?>
                <button name="Next">Next</button>
            </form>
        </div>
        
        
    </main>
    <footer class="bg-dark text-center text-white">
        <h1>Доставка еды</h1>
        <p>(c) Dostavka INC</p>
    </footer>
</body>
</html>