<?php include "parts/_header.php" ?>
<?php if (isset($_SESSION['user_id'])) {
    if ($_SESSION['type'] == 'company') {
        if (isset($_GET['id'])) {
            if ($_SERVER['REQUEST_METHOD'] == "POST") {

                try {

                    if (isset($_FILES['photo_path'])) {
                        $file = $_FILES['photo_path'];
                        $fileName = $_FILES['photo_path']['name'];
                        $fileTmpName = $_FILES['photo_path']['tmp_name'];
                        $fileSize = $_FILES['photo_path']['size'];
                        $fileError = $_FILES['photo_path']['error'];
                        $fileType = $_FILES['photo_path']['type'];
                        $fileActualExt = strtolower(end(explode('.', $fileName)));
                        $fileMimiType = strtolower(end(explode('/', $fileTmpName)));
                        echo $fileActualExt;
                        echo $fileMimiType;
                        echo $fileError;
                        echo strcmp($fileMimiType, $fileActualExt);
                        echo implode(" ", $_FILES['photo_path']);
                        $allowed = array('jpg', 'jpeg', 'png');
                        if (in_array($fileActualExt, $allowed) && $fileError == 0 && strcmp($fileMimiType, $fileActualExt) == 0) {
                            if (file_exists('./images/companies/' . $_SESSION['company_id'] . '.' . $fileActualExt)) {
                                unlink('./images/companies/' . $_SESSION['company_id'] . '.' . $fileActualExt);
                            }
                            //Place it into your "uploads" folder mow using the move_uploaded_file() function
                            move_uploaded_file($fileTmpName, './images/companies/' . $_SESSION['company_id'] . '.' . $fileActualExt);
                            echo 'uploadeeeeeeeeeeeeeeeeeeeeed';
                        } else {
                            echo '<h5>Proplems with uplad image, check the mime type and extention of this image
                         (allowed:jpg,jpeg,png), and check the size of image (Max Size :20 MB) <h5>';
                        }
                    }



                    $sql = "UPDATE company c SET c.name = :name, c.city_id = :city,
    c.email = :email, c.tel = :tel, c.positions_count = :positions_count,
     c.positions_details = :positions_details, c.photo_path = :photo_path
       WHERE c.id = :id ;";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':name', $_POST['name'] ?? '');
                    $stmt->bindValue(':city', $_POST['city'] ?? '');
                    $stmt->bindValue(':email', $_POST['email'] ?? '');
                    $stmt->bindValue(':tel', $_POST['tel'] ?? '');
                    $stmt->bindValue(':positions_count', $_POST['positions_count'] ?? '');
                    $stmt->bindValue(':positions_details', $_POST['positions_details'] ?? '');
                    $stmt->bindValue(':photo_path', $_POST['photo_path'] ?? '');
                    $stmt->bindValue(':id', $_SESSION['company_id'] ?? '');
                    $stmt->execute();
                    echo 'update executed';
                    header('Location: ./company.php?id=' . $_SESSION['company_id']);
                } catch (Exception $e) {
                    $pdo->rollback();
                }
            } else {
                echo 'update failed';
            }
        } else {

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                try {
                    $sst = $pdo->query("SELECT MAX(id) FROM company");
                    $maxid = $sst->fetch();
                    $maxid = intval($maxid['MAX(id)']);
                    $maxid = $maxid + 1;
                    echo $maxid;

                    if (isset($_FILES['photo_path'])) {
                        $file = $_FILES['photo_path'];
                        $fileName = $_FILES['photo_path']['name'];
                        $fileTmpName = $_FILES['photo_path']['tmp_name'];
                        $fileSize = $_FILES['photo_path']['size'];
                        $fileError = $_FILES['photo_path']['error'];
                        $fileType = $_FILES['photo_path']['type'];
                        $fileActualExt = strtolower(end(explode('.', $fileName)));
                        $fileMimiType = strtolower(end(explode('/', $fileTmpName)));
                        echo $fileActualExt;
                        echo $fileMimiType;
                        echo $fileError;
                        echo strcmp($fileMimiType, $fileActualExt);
                        echo implode(" ", $_FILES['photo_path']);
                        $allowed = array('jpg', 'jpeg', 'png');
                        if (in_array($fileActualExt, $allowed) && $fileError == 0 && strcmp($fileMimiType, $fileActualExt) == 0) {
                            if (file_exists('./images/companies/' . $maxid . '.' . $fileActualExt)) {
                                unlink('./images/companies/' . $maxid . '.' . $fileActualExt);
                            }
                            //Place it into your "uploads" folder mow using the move_uploaded_file() function
                            move_uploaded_file($fileTmpName, './images/companies/' . $maxid . '.' . $fileActualExt);
                            echo 'uploadeeeeeeeeeeeeeeeeeeeeed';
                        } else {
                            echo '<h5>Proplems with uplad image, check the mime type and extention of this image
                         (allowed:jpg,jpeg,png), and check the size of image (Max Size :20 MB) <h5>';
                        }
                    }


                    $sql = 'INSERT INTO company (id,name, city_id, email, tel, positions_count, positions_details,
                     photo_path, user_id) VALUES (:id,:name,:city,:email,:tel,:positions_count,:positions_details,
                :photo_path,:user_id)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $maxid ?? '');
                    $stmt->bindValue(':name', $_POST['name'] ?? '');
                    $stmt->bindValue(':city', $_POST['city'] ?? '');
                    $stmt->bindValue(':email', $_POST['email'] ?? '');
                    $stmt->bindValue(':tel', $_POST['tel'] ?? '');
                    $stmt->bindValue(':positions_count', $_POST['positions_count'] ?? '');
                    $stmt->bindValue(':positions_details', $_POST['positions_details'] ?? '');
                    $stmt->bindValue(':photo_path', './images/companies/' . $maxid ?? '');
                    $stmt->bindValue(':user_id', $_SESSION['user_id'] ?? '');
                    $stmt->execute();
                    echo 'insert executed';
                    header('Location: ./company.php?id=' . $maxid);
                } catch (Exception $e) {
                    $pdo->rollback();
                }
            }
        }
    }
}

?>


<?php if (isset($_SESSION['user_id'])) {
    if ($_SESSION['type'] == 'company') {
        if (isset($_GET['id'])) {
            if ($_SESSION['company_id'] == $_GET['id']) {
?>
                <main>
                    <h2>Edit Company</h2>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $_SESSION['company_id'] ?>">
                        <?php
                        $sql2 = 'SELECT c.* FROM company c WHERE c.user_id=:user_id ;';
                        $stmt = $pdo->prepare($sql2);
                        $stmt->bindValue(':user_id', $_SESSION['user_id'] ?? '');
                        $stmt->execute();
                        while ($useri = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <label>Logo</label>
                            <input type="file" name="photo_path" id="photo_path" value="<?php echo $useri['photo_path']; ?>">
                            <br>
                            <label>name</label>
                            <input type="text" name="name" id="name" value="<?php echo $useri['name']; ?>">
                            <br>
                            <label> City </label>
                            <select name="city" id="city">
                                <option value=''>Chose a country</option>
                                <?php
                                $sql = "select * from city";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    if ($row['id'] == ($useri['city_id'])) {
                                        echo '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
                                    } else {
                                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                            <br>
                            <label>Email</label>
                            <input type="email" name="email" id="email" value="<?php echo $useri['email']; ?>">
                            <br>
                            <label>Tel</label>
                            <input type="tel" name="tel" id="tel" value="<?php echo $useri['tel']; ?>">
                            <br>
                            <label>Positions Count</label>
                            <input type="text" name="positions_count" id="positions_count" value="<?php echo $useri['positions_count']; ?>">

                            <br>
                            <label>Positions Details </label>
                            <textarea name="positions_details" id="positions_details"><?php echo $useri['positions_details']; ?></textarea>

                            <br>
                            <input type="submit" value="Edit Student">
                            <input type="reset" value="Clear">

                        <?php
                        } ?>

                    </form>
                    <a href="companies.php">Cancel and return to Companies List</a>
                </main>
                <aside>
                    <h2>Help</h2>
                    <p>
                        Add company and positions<br> details so that students<br> can find you...
                    </p>
                </aside>
            <?php
            } else {
                echo 'Cant edit other Companies records  ,Cant edit other Companies records
                  ,Cant edit other Companies records';
            }
        } else {
            if (isset($_SESSION['student_id'])) {
                echo '<p>Sorry !! you have a student record for your account, you can edit it by this
         <a href="./add-student.php?id=' . $userj['id'] . '">link</a></p>';
            } else {
            ?>
                <main>
                    <h2>Add Company</h2>
                    <form method="post" action="./addCompany.php">
                        <label>Logo</label>
                        <input type="file" name="logo">
                        <br>
                        <label>name</label>
                        <input type="text" name="name">

                        <label> City </label>
                        <select name="where">
                            <option value=''>Chose a country</option>
                            <?php
                            $sql = "select * from city";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>

                        <br>
                        <label>Email</label>
                        <input type="email" name="email">

                        <br>
                        <label>Tel</label>
                        <input type="tel" name="tel">

                        <label>Positions Count</label>
                        <input type="text" name="positions_count">

                        <br>
                        <label>Positions Details</label>
                        <textarea name="positions_details"></textarea>

                        <br>
                        <input type="submit" value="Add Student">
                        <input type="reset" value="Clear">

                    <?php
                } ?>

                    </form>
                    <a href="companies.php">Cancel and return to Companies List</a>
                </main>
                <aside>
                    <h2>Help</h2>
                    <p>
                        Add company and positions<br> details so that students<br> can find you...
                    </p>
                </aside>
    <?php echo 'ADD ADD ADD ADD ADD ADD ADD ADD ADD ADD ADD';
        }
    } else {
        echo 'You have a Student account, you can not add or change Company recors';
    }
} else {
    echo 'Logged out Logged out Logged out Logged out Logged out Logged out';
}
    ?>
    <?php include "parts/_footer.php" ?>