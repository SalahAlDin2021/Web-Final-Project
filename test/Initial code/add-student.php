<?php include "parts/_header.php" ?>
<?php if (isset($_SESSION['user_id'])) {
    if ($_SESSION['type'] == 'student') {
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
                            if (file_exists('./images/students/' . $_SESSION['student_id'] . '.' . $fileActualExt)) {
                                unlink('./images/students/' . $_SESSION['student_id'] . '.' . $fileActualExt);
                            }
                            //Place it into your "uploads" folder mow using the move_uploaded_file() function
                            move_uploaded_file($fileTmpName, './images/students/' . $_SESSION['student_id'] . '.' . $fileActualExt);
                            echo 'uploadeeeeeeeeeeeeeeeeeeeeed';
                        } else {
                            echo '<h5>Proplems with uplad image, check the mime type and extention of this image
                         (allowed:jpg,jpeg,png), and check the size of image (Max Size :20 MB) <h5>';
                        }
                    }


                    $sql = "UPDATE student s SET s.name = :name, s.city_id = :city,
     s.email = :email, s.tel = :tel, s.major = :major,
      s.projects = :projects, s.interests = :interests,
       s.photo_path = :photo_path, s.university = :university
        WHERE s.user_id = :id;";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':name', $_POST['name'] ?? '');
                    $stmt->bindValue(':city', $_POST['city'] ?? '');
                    $stmt->bindValue(':email', $_POST['email'] ?? '');
                    $stmt->bindValue(':tel', $_POST['tel'] ?? '');
                    $stmt->bindValue(':major', $_POST['major'] ?? '');
                    $stmt->bindValue(':projects', $_POST['projects'] ?? '');
                    $stmt->bindValue(':interests', $_POST['interests'] ?? '');
                    $stmt->bindValue(':photo_path', './images/students/' . $_SESSION['student_id'] . $fileActualExt ?? '');
                    $stmt->bindValue(':university', $_POST['university'] ?? '');
                    $stmt->bindValue(':id', $_SESSION['student_id'] ?? '');
                    $stmt->execute();
                    echo 'update executed';
                    header('Location: ./student.php?id=' . $_SESSION['student_id']);
                } catch (Exception $e) {
                    $pdo->rollback();
                    echo '<h5>We have proplem with this data<h5>';
                }
            } else {
                echo 'update failed';
            }
        } else {

            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                try {
                    $sst = $pdo->query("SELECT MAX(id) FROM student");
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
                            if (file_exists('./images/students/' . $maxid . '.' . $fileActualExt)) {
                                unlink('./images/students/' . $maxid . '.' . $fileActualExt);
                            }
                            //Place it into your "uploads" folder mow using the move_uploaded_file() function
                            move_uploaded_file($fileTmpName, './images/students/' . $maxid . '.' . $fileActualExt);
                            echo 'uploadeeeeeeeeeeeeeeeeeeeeed';
                        } else {
                            echo '<h5>Proplems with uplad image, check the mime type and extention of this image
                         (allowed:jpg,jpeg,png), and check the size of image (Max Size :20 MB) <h5>';
                        }
                    }
                    $sql = 'INSERT INTO student (id,name, city_id, email, tel, major, projects, interests,
                photo_path, user_id, university) VALUES (:id,:name,:city,:email,:tel,:major,:projects,
                :interests,:photo_path,:user_id,:university)';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':id', $maxid ?? '');
                    $stmt->bindValue(':name', $_POST['name'] ?? '');
                    $stmt->bindValue(':city', $_POST['city'] ?? '');
                    $stmt->bindValue(':email', $_POST['email'] ?? '');
                    $stmt->bindValue(':tel', $_POST['tel'] ?? '');
                    $stmt->bindValue(':major', $_POST['major'] ?? '');
                    $stmt->bindValue(':projects', $_POST['projects'] ?? '');
                    $stmt->bindValue(':interests', $_POST['interests'] ?? '');
                    $stmt->bindValue(':photo_path', './images/students/' . $maxid . '.png' ?? '');
                    $stmt->bindValue(':university', $_POST['university'] ?? '');
                    $stmt->bindValue(':user_id', $_SESSION['user_id'] ?? '');
                    $stmt->execute();
                    echo 'insert executed';
                    $_SESSION['type'] = 'student';
                    $_SESSION['student_id'] = $maxid;
                    header('Location: ./student.php?id=' . $maxid);
                } catch (Exception $e) {
                    $pdo->rollback();
                }
            }
        }
    }
}

?>

<?php if (isset($_SESSION['user_id'])) {
    if ($_SESSION['type'] == 'student') {
        if (isset($_GET['id'])) {
            if ($_SESSION['student_id'] == $_GET['id']) {
?>

                <main>
                    <h2>Edit Student</h2>
                    <form class="add_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $_SESSION['student_id'] ?>">
                        <?php
                        $sql2 = 'SELECT s.* FROM student s WHERE s.user_id=:user_id ;';
                        $stmt = $pdo->prepare($sql2);
                        $stmt->bindValue(':user_id', $_SESSION['user_id'] ?? '');
                        $stmt->execute();
                        while ($useri = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <label>personal Photo</label>
                            <input type="file" name="photo_path" id="photo_path"><!-- value="<?php echo $useri['photo_path'] ?> -->
                            <br>
                            <label>name</label>
                            <input type="text" name="name" id="name" value="<?php echo $useri['name'] ?>">
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
                            <input type="email" name="email" id="email" value="<?php echo $useri['email'] ?>">
                            <br>
                            <label>Tel</label>
                            <input type="tel" name="tel" id="tel" value="<?php echo $useri['tel'] ?>">
                            <br>
                            <label>University</label>
                            <input type="text" name="university" id="university" value="<?php echo $useri['university'] ?>">
                            <br>
                            <label>Major</label>
                            <input type="text" name="major" id="major" value="<?php echo $useri['major'] ?>">
                            <br>
                            <label>projects</label>
                            <textarea name="projects" id="projects"><?php echo $useri['projects'] ?></textarea>
                            <br>
                            <label>Interests</label>
                            <textarea name="interests" id="interests"><?php echo $useri['interests'] ?></textarea>
                            <br>
                            <input name="submit" type="submit" value="Edit Student">
                            <input type="reset" value="Clear">
                        <?php
                        } ?>
                    </form>
                    <a href="students.php">Cancel and return to Students List</a>
                </main>
                <aside>
                    <h2>Help</h2>
                    <p>
                        Add your student details including<br> projects and interests so that<br> companies can select you...
                    </p>
                </aside>
            <?php
            } else {
                echo 'Cant edit other students records  ,Cant edit other students records  ,Cant edit other students records';
            }
        } else {
            if (isset($_SESSION['student_id'])) {
                echo '<p>Sorry !! you have a student record for your account, you can edit it by this
         <a href="./add-student.php?id=' . $userj['id'] . '">link</a></p>';
            } else {
            ?>
                <main>
                    <h2>Add Student</h2>
                    <form class="add_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                        <label>personal Photo</label>
                        <input type="file" name="photo">
                        <br>
                        <label>name</label>
                        <input type="text" name="name">
                        <br>
                        <label> City </label>
                        <select name="city" id="city">
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
                        <br>
                        <label>University</label>
                        <input type="text" name="university">
                        <br>
                        <label>Major</label>
                        <input type="text" name="major">
                        <br>
                        <label>projects</label>
                        <textarea name="projects"></textarea>
                        <br>
                        <label>Interests</label>
                        <textarea name="interests"></textarea>
                        <br>
                        <input name="submit" type="submit" value="Add Student">
                        <input type="reset" value="Clear">
                    </form>
                    <a href="students.php">Cancel and return to Students List</a>
                </main>
                <aside>
                    <h2>Help</h2>
                    <p>
                        Add your student details including<br> projects and interests so that<br> companies can select you...
                    </p>
                </aside>
<?php echo 'ADD ADD ADD ADD ADD ADD ADD ADD ADD ADD ADD';
            }
        }
    } else {
        echo 'You have a company account, you can not add or change student recors';
    }
} else {
    echo 'Logged out Logged out Logged out Logged out Logged out Logged out';
}
?>
<?php include "parts/_footer.php" ?>