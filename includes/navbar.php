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

                <a href="/vite_gourmand/pages/commande/mes-commandes.php">
                    Mes commandes
                </a>

            <?php endif; ?>

            <?php if(
                $_SESSION['user']['idRole'] == 1
                || $_SESSION['user']['idRole'] == 2): ?>

                <a href="/vite_gourmand/pages/admin/commandes.php">
                    Administration
                </a>

            <?php endif; ?>

                <a href="/vite_gourmand/pages/auth/logout.php">
                    Déconnexion
                </a>

            <?php endif; ?>
        </div>
    </nav>
    
