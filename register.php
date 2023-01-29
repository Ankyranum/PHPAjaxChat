<?php 
include('veritabanibaglanti.php');
session_start();
if(isset($_SESSION['user_id']))
	{
	header('location:index.php');
	}
?>

<?php
	$message = '';
	if (isset($_POST['register'])){
	require_once('veritabanibaglanti.php');
	$kullanici_username= ($_POST["username"]);
	$kullanici_password = ($_POST["password"]);
	$query1 = $connect->query("SELECT * FROM login WHERE username = '".$kullanici_username."'");
	$dizi = $query1->fetchAll(\PDO::FETCH_ASSOC);
	if ( !$dizi[0]['username'] == $kullanici_username){ 
	            $query = $connect->prepare("INSERT INTO login SET 
	            username = ?,
	            password = ?");
	            $insert  = $query->execute(array(
	            $kullanici_username,$kullanici_password
	            ));
	            if (isset($insert)){
	                $last_id = $connect->lastInsertId();
	                header("Location: login.php"); 
	            }
	            else{
	                $message = "<label>Kayıt işleminde hata oluştu.</label>";
	            }
	        }
	        else {
	        $message = "<label>Bu kullanıcı adı başkası tarafından kullanılıyor.</label>";
	        }
	    }
?>

<head>
	<title>Giriş Yap</title>
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head> 
<html>
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
				 	<input type="submit" name="register" value="Kayıt Ol" class="btn login_btn" />
				   </div>
					</form>
				</div>
		
				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						Hesabın yok mu ? <a href="login.php" class="ml-2">Giriş Yap</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>