<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>


<?php

	include('veritabanibaglanti.php');

	session_start();

	$message = '';

	if(isset($_SESSION['user_id']))
	{
	header('location:index.php');
	}

	if(isset($_POST["login"]))
	{
		$query = "
		SELECT * FROM login 
		WHERE username = :username
		";
		$statement = $connect->prepare($query);
		$statement->execute(
		array(
			':username' => $_POST["username"]
		)
		);
		$count = $statement->rowCount();
		if($count > 0)
		{
			$result = $statement->fetchAll();
			foreach($result as $row)
			{
				if($_POST["password"] == $row["password"])
				{
					$_SESSION['user_id'] = $row['user_id'];
					$_SESSION['username'] = $row['username'];
					$sub_query = "
					INSERT INTO login_details 
					(user_id) 
					VALUES ('".$row['user_id']."')
					";
					$statement = $connect->prepare($sub_query);
					$statement->execute();
					$_SESSION['login_details_id'] = $connect->lastInsertId();
					header("location:index.php");
				}
				else
				{
				$message = "<label>Hatalı Şifre</label>";
				}
			}
		}
		else
		{
		$message = "<label>Hatalı kullanıcı adı</labe>";
		}
	}

?>

<!DOCTYPE html>
<html>
    
<head>
	<title>Giriş Yap</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">


	
</head> 
<!--Coded with love by Mutiullah Samim-->
<body>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="resim/logo.png" class="brand_logo" alt="Logo">
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form method="post">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="username" class="form-control input_user"  placeholder="Kullanıcı Adı" required />
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="password" class="form-control input_pass"  placeholder="Şifre" required />
						</div>
						<p class="text-danger"><?php echo $message; ?></p>
							<div class="d-flex justify-content-center mt-3 login_container">
				 	<input type="submit" name="login" value="Giriş Yap" class="btn login_btn" />
				   </div>
					</form>
				</div>
		
				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						Hesabın yok mu ? <a href="register.php" class="ml-2">Kayıt Ol</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
