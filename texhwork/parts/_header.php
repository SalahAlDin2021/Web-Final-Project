<?php require_once './parts/_db.php' ?>
<?php 
if (isset($_SESSION['user_id'])) {
	$sql = 'update users set last_hit = now() where id = :user_id';
	$s = $pdo->prepare($sql);
	$s->bindValue('user_id', $_SESSION['user_id']);
	$s->execute();
}

?>
<!doctype html>
<html lang="en">
	<head>
		<title>Main page</title>
	</head>
	<body>
	<?php if (isset($_SESSION['user_id'])) : ?>
		<header>
		<?php
		$sql = "select * from users 
			where id = :user_id";
		$stmt = $pdo->prepare($sql);
		$stmt->bindValue('user_id', $_SESSION['user_id']);
		$stmt->execute();
		$res=$stmt->fetch(PDO::FETCH_ASSOC);
		echo '<a href="google.com">'.$res['displayname'].'</a>';
	?>
	
	</header>
<?php endif; ?>