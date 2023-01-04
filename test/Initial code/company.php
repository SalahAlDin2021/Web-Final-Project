<?php include "parts/_header.php" ?>
<?php
if (isset($_GET['id'])) {

    $sql = 'SELECT * FROM company WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']  ?? '');
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <main>
        <h2><?php echo $user['name']; ?></h2>
        <img id="company_logo" src="./<?php echo $user['photo_path']; ?>.png" alt="Logo" width="200" height="200" class="photo">
        <div id="info">
            <dl>
                <dt>City: </dt>
                <dd><?php
                    $sql2 = 'SELECT c.* FROM city c WHERE c.id=:id';
                    $stmt2 = $pdo->prepare($sql2);
                    $stmt2->bindValue(':id', $user['city_id']  ?? '');
                    $stmt2->execute();
                    $city = $stmt2->fetch(PDO::FETCH_ASSOC);
                    echo $city['name'];
                    ?></dd>
                <dt>Email: </dt>
                <dd><?php echo $user['email']; ?></dd>
                <dt>Tel: </dt>
                <dd><?php echo $user['tel']; ?></dd>
                <dt>Open Position: </dt>
                <dd><?php echo $user['positions_count']; ?></p>
                <dt>Position Details: </dt>
                <dd><?php echo $user['positions_details']; ?></dd>
            </dl>
        </div>
        <a href="./companies.php">Back to Companies List | </a>
        <?php
        if ($_SESSION['user_id'] == $user['user_id']) {
            echo '<a href="./add-company.php?id=' . $user['id'] . '">Edit</a>';
        }
        ?>
    </main>
    <aside>
        <h2>Similar Companies</h2>
        <p>
            Another companies in same<br> location looking for students...
        </p>
    </aside>
<?php } ?>
<?php include "parts/_footer.php" ?>