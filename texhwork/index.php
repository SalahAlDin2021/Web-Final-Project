<?php require_once './parts/_header.php' ?>
<?php
	$msg = '';
	$arr=[];
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		$sql = "select * from users 
			where username = :username and passwd = sha1(:passwd)";
		$stmt = $pdo->prepare($sql);
		$userrInf=$_POST['username'];
		$stmt->bindValue(':username',$userrInf  ?? '');
		$stmt->bindValue(':passwd', $_POST['passwd'] ?? '');
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		$arr=$user;
		if ($user) {
			$msg=$user['username'];
			#$msg = '<h1>' . $user['id'] . ', ' . $user['username'] . ', ' . $user['passwd'] . ', ' . $user['displayname'] . ',  </h1>';
			$_SESSION['user_id'] = $user['id'];
			header('Location: /texhwork');
		} else {
			$msg = "Invalid";
		}
	}
?>
<h1>My Page</h1>
<?php if (isset($_SESSION['user_id'])) : ?>
<br>
&lt;h1&gt;12341234&lt;/h1&gt;
<br>
Logged in <a href="logout.php"> Logout </a>
<?php else : echo $msg; ?>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<label for="username">Username</label>
	<input type="text" name="username" id="username" value="<?php echo $_POST['username'] ?? '' ?>"/>
	<br/>
	<label for="passwd">Password</label>
	<input type="password" name="passwd" id="passwd" value="<?php echo $_POST['passwd'] ?? '' ?>" />
	<br/>
	<input name="submit" type="submit" value="Login" />
</form>
<?php endif; ?>
<?php require_once './parts/_footer.php' ?>