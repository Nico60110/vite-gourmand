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

            <a href="/vite_gourmand/index.php">Accueil</a>

            <a href="/vite_gourmand/pages/menu/menus.php">Nos Menus</a>

            <a href="/vite_gourmand/pages/contact.php">Contact</a>

            <?php if(!isset($_SESSION['user'])): ?>

                <a href="/vite_gourmand/pages/auth/inscription.php">
                    Inscription
                </a>

                <a href="/vite_gourmand/pages/auth/login.php">
                    Connexion
                </a>

            <?php else: ?>

            <?php if($_SESSION['user']['idRole'] == 3): ?>

                <div class="dropdown">

                    <button class="dropdown-btn">
                        Mon profil ▼
                    </button>

                    <div class="dropdown-content">

                        <a href="/vite_gourmand/pages/client/profile.php">
                            Mon profil
                        </a>

                        <a href="/vite_gourmand/pages/client/commande-client.php">
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

                        <a href="/vite_gourmand/pages/admin/commandes.php">
                            Admin Commande
                        </a>

                        <a href="/vite_gourmand/pages/admin/admin-menu.php">
                            Admin menu
                        </a>

                        <a href="/vite_gourmand/pages/admin/plat.php">
                            Admin plat
                        </a>

                        <a href="/vite_gourmand/pages/admin/admin-avis.php">
                            Admin avis
                        </a>

                        <?php if($_SESSION['user']['idRole'] == 1): ?>

                            <a href="/vite_gourmand/pages/admin/statistiques.php">
                                Statistiques
                            </a>

                            <a href="/vite_gourmand/pages/admin/employes.php">
                                Employés
                            </a>

                            <a href="/vite_gourmand/pages/admin/add-employer.php">
                                Ajouter employé
                            </a>

                        <?php endif; ?>

                        <a href="/vite_gourmand/pages/admin/horaire.php">
                            Admin horaire
                        </a>

                    </div>

                </div>

            <?php endif; ?>

                <a href="/vite_gourmand/pages/auth/logout.php">
                    Déconnexion
                </a>

            <?php endif; ?>
        </div>
    </nav>
    
