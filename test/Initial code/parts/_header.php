<?php require_once './parts/_db.php' ?>
<?php
if (isset($_SESSION['user_id'])) {
    $sql = 'update users set last_hit = now() where id = :user_id';
    $s = $pdo->prepare($sql);
    $s->bindValue('user_id', $_SESSION['user_id']);
    $s->execute();
}

?>

<!DocType html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/layout.css" />
    <link rel="stylesheet" href="css/website.css" />
    <title>Student Training</title>
</head>

<body>
    <header>
        <div>
            <img src="images/training.png" alt="logo" height="150" width="150" />
            <h1>Student Training</h1>
        </div>

        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<div class="headclass">';
            echo '<a>' . $_SESSION['displayname'] . ' </a>';
            echo '<a href="./logout.php">  Logout </a>';
            echo '</div class="headclass">';
        } else {
            echo '<a href="./index.php">Login</a>';
        }

        ?>
        <br>
        <br>
        <br>
        <nav>
            <a href="./index.php">Home</a>
            <a href="./students.php">Students List</a>
            <a href="./companies.php">Companies List</a>
        </nav>
    </header>