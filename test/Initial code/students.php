<?php include "parts/_header.php" ?>
<main>
    <h2>Companies List</h2>
    <hr>
    <form class="simple_form" method="get" action="students.php">
        Student Study major:
        <input type="majorSearch" name="majorSearch" id="majorSearch">
        City:<select name="city" id="city">
            <option value=''>Chose a country</option>
            <?php
            $sql = "select * from city";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (isset($_GET['city']) && ($row['id'] == ($_GET['city']))) {
                    echo '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
                } else {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            }
            ?>
        </select>
        <input type="submit" value="Search">
    </form>
    <table class="table_student">
        <thead>
            <tr>
                <th>
                    Photo
                </th>
                <th>
                    Name
                </th>
                <th>
                    City
                </th>
                <th>
                    University
                </th>
                <th>
                    Major
                </th>

            </tr>
        </thead>
        <tbody>

            <?php
            $sql = "SELECT s.* FROM student s,city c WHERE s.city_id=c.id AND ( c.id=:city OR :city='' )
         AND (upper(s.major) LIKE upper(:majorSearch) OR :majorSearch='');";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':city', $_GET['city'] ?? '');
            $mjrsrch = '';
            if (isset($_GET['majorSearch'])) {
                $mjrsrch = '%' . $_GET['majorSearch'] . '%';
            }
            $stmt->bindValue(':majorSearch', $mjrsrch ?? '');
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td>
                            <img src="<?php echo $row['photo_path']; ?>.png" alt="personal Photo">
                        </td>
                        <td>
                            <a href="student.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                        </td>
                        <td>
                            <?php
                            $sql2 = "select * from city where id=:id";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->bindValue(':id', $row['city_id']);
                            $stmt2->execute();
                            $city = $stmt2->fetch();
                            echo $city['name']; ?>
                        </td>
                        <td>
                            <?php echo $row['university']; ?>
                        </td>
                        <td>
                            <?php echo $row['major']; ?>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php if (!isset($_SESSION['student_id'])) { ?>
        <a href="add-student.php">Add Student</a>
    <?php } ?>
</main>
<aside>
    <h2>Distinguished Students</h2>
    <p>
        Student Salah Aldin from Birzeit<br> is very special and<br> he is looking for training<br> in Computer Science...
    </p>
</aside>
<?php include "parts/_footer.php" ?>