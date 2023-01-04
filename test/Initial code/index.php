<?php require_once './parts/_header.php' ?>
<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$sql = "select * from users 
			where username = :username and passwd = sha1(:passwd)";
	$stmt = $pdo->prepare($sql);
	$stmt->bindValue(':username', $_POST['username']  ?? '');
	$stmt->bindValue(':passwd', $_POST['passwd'] ?? '');
	$stmt->execute();
	$user = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($user) {
		$msg = $user['username'];
		$_SESSION['user_id'] = $user['id'];
		$_SESSION['displayname'] = $user['displayname'];
		$_SESSION['type'] = $user['type'];
		if ($user['type'] == 'student') {
			$sql2 = "select * from student where user_id=:id";
			$stmt2 = $pdo->prepare($sql2);
			$stmt2->bindValue(':id', $user['id']  ?? '');
			$stmt2->execute();
			$user2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if (isset($user2)) {
				$_SESSION['student_id'] = $user2['id'];
			}
		} else {
			$sql2 = "select * from company where user_id=:id";
			$stmt2 = $pdo->prepare($sql2);
			$stmt2->bindValue(':id', $user['id']  ?? '');
			$stmt2->execute();
			$user2 = $stmt2->fetch(PDO::FETCH_ASSOC);
			if (isset($user2)) {
				$_SESSION['company_id'] = $user2['id'];
			}
		}
		// header('Location: http://web1192404.studentswebprojects.ritaj.ps/index.php');
		header('Location: http://localhost/test/Initial%20code/index.php');
	} else {
		$msg = "Invalid";
	}
}
?>
<main>
	<?php if (isset($_SESSION['user_id'])) : ?>
		<br>
		<?php
		$sql = "select * from students_applications 
where student_id = :student_id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue(':student_id', $_SESSION['user_id']  ?? '');
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				echo '<p>Company Number ' . $row['company_id'] . '</p><br>';
			}
		}


		?>
		<br>
		<p>user id:<?php echo $_SESSION['user_id']; ?></p>
		<p>display name:<?php echo $_SESSION['displayname']; ?></p>
		<p>type:<?php echo $_SESSION['type']; ?></p>
		<?php
		if ($_SESSION['type'] == 'student') {
			echo '<p>student id:' . $_SESSION['student_id'] . '</p>';
		} else {
			echo '<p>company id:' . $_SESSION['company_id'] . '</p>';
		}
		?>
		<br>
		<!-- <?php echo $_SESSION['user_id'] ?> -->
		<br>
	<?php else : echo $msg; ?>
		<form id="login_from" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
			<label for="username">Username</label>
			<input type="text" name="username" id="username" value="<?php echo $_POST['username'] ?? '' ?>" />
			<br />
			<label for="passwd">Password</label>
			<input type="password" name="passwd" id="passwd" value="<?php echo $_POST['passwd'] ?? '' ?>" />
			<br />
			<input name="submit" type="submit" value="Login" />
		</form>
	<?php endif; ?>
</main>
<aside>
	<h2>Aside</h2>
	<p>
		The aside will have information related to the current page or ads.
	</p>
</aside>
<?php include "./parts/_footer.php" ?>