<?php
$page_title = "Authentification";
//require_once 'config.php';
require_once 'loader/init.php';
//Autoloader::Load(FS_PATH.'/classes');
Autoloader::Load('classes');
include_once 'core.php'; /*
include_once 'include/database_pdo.php'; 
include_once 'classes/class.utilisateur.php'; */
// get database connection  notika
$database = new Database();
$db = $database->getConnection();

$utilisateur = new Utilisateur($db);
$is_mobile  = isset($_GET['mobile']) ? "?mobile=" . $_GET['mobile'] : '';
$user = isset($_POST['username']) ? $_POST['username'] : '';
$response =  array();
//$_SESSION['last_acted_on'] = time();


// var_dump($utilisateur->is_logged_in());

if ($utilisateur->is_logged_in() == true) {
	$utilisateur->readOne();

	// var_dump($utilisateur);

	//Acceder au dashboard web
	// if($utilisateur->HasDroits("10_290"))
	// { 
	// $utilisateur->redirect('accueil.php');	 
	// }else if($utilisateur->HasDroits("10_340"))
	// { 
	// $utilisateur->redirect('lst_install.php');
	// $utilisateur->redirect('lst_identifs.php');	
	// }else{
	$utilisateur->redirect('accueil.php' . $is_mobile);
	// }	
} else if (isset($_POST['username']) && isset($_POST['password'])) {
	$email = addslashes($_POST['username']);
	$upass = addslashes($_POST['password']);
	//if(filter_var($email,FILTER_VALIDATE_EMAIL)) { 
	$response = $utilisateur->login($email, $upass);
	if (isset($response['login']) && $response['login'] == true) {
		$utilisateur->readOne();
		// $utilisateur->redirect('accueil.php');	
		$utilisateur->redirect('accueil.php' . $is_mobile);
		//exit;
	}
	// else if(isset($response['login']) && $response['login'] == false)
	// {
	// $error = $response['error'];
	// }		
}
// var_dump($_POST);
// exit;
?>

<!DOCTYPE html>
<html lang="fr">

<head>

	<style>
		html,
		body {
			height: 100%;
		}

		body {
			display: -ms-flexbox;
			display: flex;
			-ms-flex-align: center;
			align-items: center;
			padding-top: 40px;
			padding-bottom: 40px;
		}
	</style>
	<?php
	include_once "layout_style.php";
	?>
</head>

<body>
	<div class="block-login">
		<div class="container-fluid">
			<div class="row justify-content-center align-items-center">
				<div class="col-lg-6 pl-0">
					<div class="bg-">
						<img src="assets/imgs/logo-white.png" alt="">
					</div>
				</div>
				<div class="col-lg-6">
					<div class="row justify-content-center">
						<div class="col-xxl-7 col-lg-8">
							<div class="card card-login">
								<div class="text-center">
									<h1>Connexion</h1>
									<p class="mb-2">Veuillez saisir les informations ci-dessous <br> pour vous connecter</p>
								</div>
								<?php if (isset($response['error'])) {
									echo '<div class="alert alert-danger" role="alert">';
									// if($error == 100){
									// echo 'Mot de passe ou compte non valide';
									// }else if($error == 105){
									// echo 'Compte non activé';
									// }else if($error == 110){
									// echo 'Accès non autorisé';
									// }
									echo $response['message'];
									echo '</div>';
								}

								?>
								<form action="login.php<?php echo $is_mobile; ?>" method="post">

									<div class="form-group row g-3 g-lg-4">
										<div class="col-12 position-relative mb-4">

											<input class="form-control" id="email" name="username" type="text" placeholder="Utilisateur" autocomplete="off" value="<?php echo $user; ?>">
											<span class="icon">
												<i class="fa fa-user"></i>
											</span>
										</div>
										<div class="col-12 position-relative mb-4">
											<input class="form-control password" id="password" name="password" type="password" placeholder="Mot de passe">
											<span class="icon">
												<i class="fa fa-lock"></i>
											</span>
											<div class="btn-see-password">
												<i class="fas fa-eye"></i>
												<i class="fas fa-eye-slash"></i>
                                            </div>
										</div>
										<div class="col-12">
										<button class="btn btn-login"  type="submit">
											Se connecter
										</button>

										</div>
									</div>

								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- <div class="splash-container">
		<div class="card ">
			<div class="card-header text-center">
					<a href="../index.html"><img class="logo-img" src="../assets/images/logo.png" alt="logo"></a> 
				<h5 class="navbar-brand"><?php echo $APP_NAME; ?></h5>
				<span class="splash-description">AUTHENTIFICATION</span>
			</div>
			<div class="card-body">

				<form action="login.php<?php echo $is_mobile; ?>" method="post">
					<div class="form-group">
						<input class="form-control form-control-lg" name="username" type="text" placeholder="Utilisateur" autocomplete="off" value="<?php echo $user; ?>">
					</div>
					<div class="form-group">
						<input class="form-control form-control-lg" name="password" type="password" placeholder="Mot de passe">
					</div>
					     <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input class="custom-control-input" type="checkbox"><span class="custom-control-label">Remember Me</span>
                        </label>
                    </div> 
					<button type="submit" class="btn btn-primary btn-lg btn-block">Se connecter</button>
				</form>
			</div>
		</div>
	</div> -->

	<script src="assets/js/jquery-3.3.1.min.js"></script>
	<script src="assets/js/bootstrap.bundle.js"></script>
	<script>
        const btn_show_password = document.querySelectorAll(".btn-see-password")
        console.log(btn_show_password);
        btn_show_password.forEach(btn => {
            $(btn).click(function() {
                console.log($(this).parent().children().eq(1).attr('type'));
                if ($(this).parent().find($('.password')).attr('type') === 'password') {
                    $(this).parent().find($('.password')).attr('type', 'text')
                    $(this).addClass('clicked')
                } else {
                    $(this).parent().find($('.password')).attr('type', 'password')
                    $(this).removeClass('clicked')
                }
            })
        });
    </script>
</body>

<?php
$db = null;
?>

</html>