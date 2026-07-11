<?php

if(session_status() === PHP_SESSION_NONE){
    session_start();
}

?>

    <nav>
        <div class="logo">
           
            
            <div class="titre"> 
                
                <h2>Vite &<br>Gourmand</h2>
                <p>Traiteur à bordeaux</p>
            </div>
        </div>
           
        
        <div class="link">

            <a href="/index.php">Accueil</a>

            <a href="/pages/menu/menus.php">Nos Menus</a>

            <a href="/pages/contact.php">Contact</a>

            <?php if(!isset($_SESSION['user'])): ?>

                <a href="/pages/auth/inscription.php">
                    Inscription
                </a>

                <a href="/pages/auth/login.php">
                    Connexion
                </a>

            <?php else: ?>

            <?php if($_SESSION['user']['idRole'] == 3): ?>

                <div class="dropdown">

                    <button class="dropdown-btn">
                        Mon profil ▼
                    </button>

                    <div class="dropdown-content">

                        <a href="/pages/client/profile.php">
                            Mon profil
                        </a>

                        <a href="/pages/client/commande-client.php">
                            Mes commandes
                        </a>

                    </div>

                </div>

            <?php endif; ?>

            <?php if(
                $_SESSION['user']['idRole'] == 1
                || $_SESSION['user']['idRole'] == 2): ?>

                <div class="dropdown">

                    <button class="dropdown-btn">
                        Admin ▼
                    </button>

                    <div class="dropdown-content">

                        <a href="/pages/admin/commandes.php">
                            Admin Commande
                        </a>

                        <a href="/pages/admin/admin-menu.php">
                            Admin menu
                        </a>

                        <a href="/pages/admin/plat.php">
                            Admin plat
                        </a>

                        <a href="/pages/admin/admin-avis.php">
                            Admin avis
                        </a>

                        <?php if($_SESSION['user']['idRole'] == 1): ?>

                            <a href="/pages/admin/statistiques.php">
                                Statistiques
                            </a>

                            <a href="/pages/admin/employes.php">
                                Employés
                            </a>

                            <a href="/pages/admin/add-employer.php">
                                Ajouter employé
                            </a>

                        <?php endif; ?>

                        <a href="/pages/admin/horaire.php">
                            Admin horaire
                        </a>

                    </div>

                </div>

            <?php endif; ?>

                <a href="/pages/auth/logout.php">
                    Déconnexion
                </a>

            <?php endif; ?>
        </div>
    </nav>
    
