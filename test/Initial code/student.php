<?php include "parts/_header.php" ?>
<?php if (isset($_GET['id'])) {
    $sql = 'SELECT * FROM student WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']  ?? '');
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <main>
        <h2><?php echo $user['name']; ?></h2>
        <img id="student_photo" src="<?php echo $user['photo_path']; ?>.png" alt="Logo" width="200" height="200" class="photo">
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
                <dt>University: </dt>
                <dd><?php echo $user['university']; ?></dd>
                <dt>Major:</dt>
                <dd><?php echo $user['major']; ?></dd>
                <dt>Projects:</dt>
                <dd><?php echo $user['projects']; ?></dd>
                <dt>Interests:</dt>
                <dd><?php echo $user['interests']; ?></dd>
            </dl>
        </div>
        <a href="./students.php">Back to Students List | </a>
        <?php
        if ($_SESSION['user_id'] == $user['user_id']) {
            echo '<a href="./add-student.php?id=' . $user['id'] . '">Edit</a>';
        }
        if (isset($_SESSION['company_id']) && $_SESSION['type'] == 'company') {
            $sql33 = 'SELECT c.* FROM students_applications c WHERE c.student_id =:student_id  AND c.company_id=:company_id';
            $stmt33 = $pdo->prepare($sql33);
            $stmt33->bindValue(':student_id', $_GET['id']  ?? '');
            $stmt33->bindValue(':company_id', $_SESSION['company_id']  ?? '');
            // echo  $_GET['id'];
            // echo $_SESSION['company_id'];
            $stmt33->execute();
            $app33 = $stmt33->fetch(PDO::FETCH_ASSOC);
            // var_dump($app33);
            if (isset($_GET['offer'])) {
                // echo $_GET['offer'];
                if (!$app33) {
                    // echo $_GET['offer'];

                    $sst = $pdo->query("SELECT MAX(id) FROM students_applications");
                    $maxid = $sst->fetch();
                    $maxid = intval($maxid['MAX(id)']);
                    $maxid = $maxid + 1;
                    // echo $maxid;
                    $sql4 = 'INSERT INTO students_applications (id, student_id, company_id, apply_date, user_id,
                     application_status) VALUES (:id,:student_id,:company_id,now(),:user_id,:application_status);';
                    $stmt4 = $pdo->prepare($sql4);
                    $stmt4->bindValue(':id', $maxid  ?? '');
                    $stmt4->bindValue(':student_id', $_GET['id']  ?? '');
                    $stmt4->bindValue(':company_id', $_SESSION['company_id']  ?? '');
                    $stmt4->bindValue(':user_id', $_SESSION['id']  ?? '');
                    $stmt4->bindValue(':application_status', 'sent'  ?? '');
                    $stmt4->execute();
                    echo '<p>(offered sent)</p>';
                }
            } else {
                if (!$app33) {
                    echo '<a href="./student.php?id=' . $_GET['id'] . '&offer=yes">Offer A Training</a>';
                } else {
                    echo '<p>(offered sent)</p>';
                }
            }
        } else if (isset($_SESSION['student_id']) && $_SESSION['type'] == 'student') {
            if ($_GET['id'] == $_SESSION['student_id']) {
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'approved' || $_GET['status'] == 'rejected') {
                        $sql5 = 'SELECT * FROM students_applications WHERE id=:id AND student_id=:student_id';
                        $stmt5 = $pdo->prepare($sql5);
                        $stmt5->bindValue(':student_id', $_SESSION['student_id']  ?? '');
                        $stmt5->bindValue(':id', $_GET['students_applications_id']  ?? '');
                        $stmt5->execute();
                        $user5 = $stmt5->fetch();
                        if (isset($user5)) {
                            // UPDATE `students_applications` SET `application_status` = 'rejected' WHERE `students_applications`.`id` = 1;
                            $sql3 = 'UPDATE students_applications SET application_status = :application_status WHERE students_applications.id = :id;';
                            $stmt3 = $pdo->prepare($sql3);
                            $stmt3->bindValue(':application_status', $_GET['status']  ?? '');
                            $stmt3->bindValue(':id', $_GET['students_applications_id']  ?? '');
                            $stmt3->execute();
                        }
                    }
                }
                echo '<h1>Training Offers</h1><hr>';
                $sql33 = 'SELECT * FROM students_applications  WHERE student_id  =:student_id ';
                $stmt33 = $pdo->prepare($sql33);
                $stmt33->bindValue(':student_id', $_SESSION['student_id']  ?? '');
                $stmt33->execute();
                // echo $_SESSION['student_id'];
                // echo $app['status'];
                while ($app33 = $stmt33->fetch()) {
                    echo '<dl>';
                    echo '<dt>Company Name<dt>';
                    $sql44 = 'SELECT c.* FROM company c WHERE c.id =:id';
                    $stmt44 = $pdo->prepare($sql44);
                    $stmt44->bindValue(':id', $app33['company_id']  ?? '');
                    $stmt44->execute();
                    $app22 = $stmt44->fetch(PDO::FETCH_ASSOC);
                    echo '<dd>' . $app22['name'] . '</dd>';
                    echo '<dt>Offer Date<dt>';
                    echo '<dd>' . $app33['apply_date'] . '</dd>';
                    echo '<dt>Status<dt>';
                    echo '<dd>' . $app33['application_status'] . '</dd>';
                    echo '<dt>Action<dt>';
                    echo '<dd><a href="./student.php?id=' . $_GET['id'] . '&status=approved&students_applications_id=' . $app33['id'] . '">Accept</a> |
                     <a href="./student.php?id=' . $_GET['id'] . '&status=rejected&students_applications_id=' . $app33['id'] . '">Reject</a></dd>';
                    echo '</dl><br>';
                }
            }
        }

        ?>
    </main>
    <aside>
        <h2>Similar Students</h2>
        <p>
            we have similar student that teach computer major.
        </p>
    </aside>
<?php
}
?>
<?php include "parts/_footer.php" ?>