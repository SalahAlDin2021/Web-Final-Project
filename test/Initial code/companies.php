<?php include "parts/_header.php" ?>
<main>
    <h2>Companies List</h2>
    <form class="simple_form" method="get" action="companies.php">
        Company Name:
        <input type="nameSearch" name="nameSearch">
        City:<select name="city" id="city">
            <option value=''>Chose a country</option>
            <?php
            $sql = "select * from city";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($row['id'] == ($_GET['city'])) {
                    echo '<option value="' . $row['id'] . '" selected>' . $row['name'] . '</option>';
                } else {
                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                }
            }
            ?>
        </select>
        <input type="submit" value="Search">
    </form>
    <table class="table_companies">
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
                    Open Position
                </th>

            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT m.* FROM company m,city c WHERE m.city_id=c.id AND ( c.id=:city OR :city='' )
         AND (upper(m.name) LIKE upper(:nameSearch) OR :nameSearch='');";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':city', $_GET['city'] ?? '');
            $mjrsrch = '';
            if (isset($_GET['nameSearch'])) $mjrsrch = '%' . $_GET['nameSearch'] . '%';
            $stmt->bindValue(':nameSearch', $mjrsrch ?? '');
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                    <tr>
                        <td>
                            <img src="<?php echo $row['photo_path']; ?>" alt="Company LOGO">
                        </td>
                        <td>
                            <a href="./company.php?id=<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a>
                        </td>
                        <td>
                            <?php
                            $sql2 = "select * from city where id=:id";
                            $stmt2 = $pdo->prepare($sql2);
                            $stmt2->bindValue(':id', $row['city_id']);
                            $stmt2->execute();
                            $city = $stmt2->fetch();
                            echo $city['name'];
                            ?>
                        </td>
                        <td>
                            <?php echo $row['positions_count']; ?>
                        </td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php if (!isset($_SESSION['company_id'])) { ?>
        <a href="add-company.php">Add Company</a>
    <?php } ?>
</main>
<aside>
    <h2>Highlighted Company</h2>
    <p>
        This will contain<br> a random special<br> company details...
    </p>
</aside>
<?php include "parts/_footer.php" ?>