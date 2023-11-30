  <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
		       <a class="navbar-brand" href="index.php"><?php echo $APP_NAME; ?></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="image/avatar-1.jpg" alt="" class="user-avatar-md rounded-circle"><span class="ml-2 d-none d-lg-inline text-primary small"><?php echo $utilisateur->nom_utilisateur; ?></span><span class="fas fa-caret-down ml-3"></span></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <a class="dropdown-item edit-pwd" href="#" data-toggle="modal" data-target="#dlg_edit_pwd"><i class="fas fa-user mr-2"></i>Modifier mot de passe</a>
                                <a class="dropdown-item" href="controller.php?view=logout"><i class="fas fa-power-off mr-2"></i>Déconnexion</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>   
   		
