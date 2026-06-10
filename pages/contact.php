<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact - Vite & Gourmand</title>

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/contact.css">
    <link rel="stylesheet" href="../css/vite&gourmand.css">

</head>

<body>

    <?php require '../includes/navbar.php';?>

    <!-- HERO -->

    <section class="hero">

        <h1>Contactez-nous</h1>

    </section>

    <!-- CONTAINER -->

    <main class="container">

        <!-- CONTACT CARD -->

        <section class="contact-card">

        <span><img class="logo-contact" src="../images/contact.png" alt=""></span>

            <!-- CONTACT INFO -->

            <div class="contact-info">

                <div class="info-item">

                    <div class="icon">📍</div>

                    <div>

                        <h3>Adresse</h3>

                        <p>
                            12 Rue des Saveurs<br>
                            33000 Bordeaux
                        </p>

                    </div>

                </div>

                <div class="info-item">

                    <div class="icon">📞</div>

                    <div>

                        <h3>Téléphone</h3>

                        <p>05 56 00 00 00</p>

                    </div>

                </div>

                <div class="info-item">

                    <div class="icon">✉</div>

                    <div>

                        <h3>Email</h3>

                        <p>contact@vitegourmand.fr</p>

                    </div>

                </div>

                <div class="info-item">

                    <div class="icon">🕒</div>

                    <div>

                        <h3>Horaire d'ouverture</h3>

                        <p>
                            Lundi - Samedi : 9h - 18h
                        </p>

                    </div>

                </div>

            </div>

            <!-- FORM -->

            <form class="contact-form">

                <div class="row">

                    <input type="text" placeholder="Nom complet">

                    <input type="email" placeholder="Email">

                </div>

                <input type="tel" placeholder="Téléphone">

                <input type="text" placeholder="Objet">

                <textarea placeholder="Votre Message"></textarea>

                <button class="btn">
                    Envoyer le message →
                </button>

            </form>

        </section>

        <!-- FEATURES -->

        <section class="features">

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">🎧</div>

                <h3>Une équipe à votre écoute</h3>

                <p>
                    Nous vous accompagnons dans
                    l’organisation de vos événements
                </p>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">📋</div>

                <h3>Devis personnalisé</h3>

                <p>
                    Recevez une réponse adaptée à vos
                    besoins dans les plus brefs délais
                </p>

            </div>

            <!-- FEATURE -->

            <div class="feature">

                <div class="feature-icon">👍</div>

                <h3>Services sur mesure</h3>

                <p>
                    Des menus créés pour sublimer vos
                    moments d’exception
                </p>

            </div>

        </section>

    </main>

    <?php require '../includes/footer.php'; ?>

</body>

</html>