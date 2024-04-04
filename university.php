<?php

//1 ci connettiamo al database
define('DB_SERVERNAME', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_NAME', 'db-university');

//2 mi collego al databese
$connection = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

//3 controllo che non ci siano errori
if ($connection && $connection->connect_error) {
    echo "Connection failed: " . $connection->connect_error;
    die;
}

//var_dump($connection);



if (isset($_POST['anno_nascita'])) {
    $anno_nascita = $_POST['anno_nascita'];
    if ($anno_nascita === '1990') {
        $sqlTudents = "SELECT * FROM `students` WHERE `date_of_birth` LIKE '%1990%'";
    } elseif ($anno_nascita === '2000') {
        $sqlTudents = "SELECT * FROM `students` WHERE `date_of_birth` LIKE '%2000%'";
    }
    $resultSTudents = $connection->query($sqlTudents);
}

$sqlCourses = "SELECT * FROM `courses` WHERE `period`='I semestre' AND `year`=1";
$resultCourses = $connection->query($sqlCourses);

$sqlCourseTeacher = "SELECT `teachers`.`name`, `teachers`.`surname`, `courses`.`name` AS `name_course`
FROM `course_teacher`
JOIN `courses`
On `course_teacher`.`course_id` = `courses`.`id`
JOIN `teachers`
ON `course_teacher`.`teacher_id` = `teachers`.`id`";

$resultCourseTeacher = $connection->query($sqlCourseTeacher);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container mb-3">
        <form method="POST">
            <label for="anno_nascita">Seleziona l'anno di nascita:</label>
            <select name="anno_nascita" id="anno_nascita">
                <option value="1990">1990</option>
                <option value="2000">2000</option>
            </select>
            <button type="submit">Filtra</button>
        </form>
    </div>

    <div class="container">
        <div class="row">
            <h2>Studenti <?= $_POST['anno_nascita'] ?></h2>
            <?php if (isset($resultSTudents)) : ?>
                <?php while ($row = $resultSTudents->fetch_assoc()) :
                    ['name' => $name, 'date_of_birth' => $date_birth] = $row;
                ?>
                    <div class="col-2 border">
                        <p> Nome: <?= $name ?></p>
                        <p> Data di nascita: <?= $date_birth ?></p>
                    </div>
                <?php endwhile ?>
            <?php endif ?>

        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <h2>I corsi</h2>
            <?php while ($row = $resultCourses->fetch_assoc()) :
                ['name' => $name, 'period' => $period, 'year' => $year] = $row;
            ?>
                <div class="col-2 border">
                    <p> Nome del corso: <?= $name ?></p>
                    <p> Periodo: <?= $period ?></p>
                    <p> Anno del corso: <?= $year ?></p>
                </div>
            <?php endwhile ?>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <h2>Insegnanti e i loro corsi </h2>
            <?php while ($row = $resultCourseTeacher->fetch_assoc()) :
                ['name' => $name, 'name_course' => $nameCourse, 'surname' => $surname] = $row;
            ?>
                <div class="col-4 border">
                    <p>Nome dell'insegnante : <?= $name ?></p>
                    <p>Cognome dell'insegnante : <?= $surname ?></p>
                    <p>Nome del corso : <?= $nameCourse ?></p>
                </div>
            <?php endwhile ?>
        </div>
    </div>
</body>

</html>