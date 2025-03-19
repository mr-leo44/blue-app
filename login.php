<?php
session_start();
// Titre de la page
$page_title = "Authentification";

// Inclusion des fichiers nécessaires
require_once 'vendor/autoload.php';
require_once 'loader/init.php';

// Chargement des classes nécessaires
Autoloader::Load('classes');


// Initialisation de la connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Création de l'objet Utilisateur
$utilisateur = new Utilisateur($db);

// Vérification si la requête vient d'un appareil mobile
$is_mobile = isset($_GET['mobile']) ? "?mobile=" . $_GET['mobile'] : '';

// Initialisation des variables
$user = isset($_POST['username']) ? $_POST['username'] : '';
$response = array();

// Démarrage de la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}





// Si l'utilisateur est déjà connecté
if ($utilisateur->is_logged_in() == true) {
	// Récupérer les informations de l'utilisateur
	$utilisateur->readOne();

	// Rediriger l'utilisateur vers la page d'accueil
	$utilisateur->redirect('accueil.php' . $is_mobile);
}
// Si l'utilisateur soumet un formulaire de connexion
else if (isset($_POST['username']) && isset($_POST['password'])) {
	// Récupérer les données du formulaire de connexion
	$email = addslashes($_POST['username']);
	$upass = addslashes($_POST['password']);

	// Authentification de l'utilisateur
	$response = $utilisateur->login($email, $upass);

	// Vérifier si la connexion a réussi
	if (isset($response['login']) && $response['login'] == true) {
		// Récupérer les informations de l'utilisateur après la connexion
		$utilisateur->readOne();

		// Rediriger l'utilisateur vers la page d'accueil
		$utilisateur->redirect('accueil.php' . $is_mobile);
	}
}
// Commentaire pour future débogage si nécessaire
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
	<div class="block-login d-flex d-lg-block flex-column justify-content-center">
		<div class="container-fluid">
			<div class="row justify-content-center align-items-center">
				<div class="col-lg-6 pl-0 d-none d-lg-block">
					<div class="bg-">
						<img src="assets/imgs/logo-white.png" alt="">
					</div>
				</div>
				<div class="col-lg-6">
					<div class="row justify-content-center">
						<div class="col-xxl-7 col-lg-8 col-md-7">
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
											<button class="btn btn-login" type="submit">
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