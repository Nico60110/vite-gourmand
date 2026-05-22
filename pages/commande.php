<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Commande - Vite & Gourmand</title>

    <!-- GOOGLE FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600&family=Roboto:wght@400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="../css/commande.css">

</head>

<body>

    <!-- HERO -->

    <section class="hero">

        <div>

            <h1>Commander un menu</h1>

            <p>
                Finalisez votre commande traiteur en quelques étapes
            </p>

        </div>

    </section>

    <!-- CONTAINER -->

    <main class="container">

        <div class="order-layout">

            <!-- LEFT -->

            <div>

                <!-- CLIENT INFORMATIONS -->

                <section class="card">

                    <h2>Informations client</h2>

                    <div class="form-grid">

                        <input type="text" placeholder="Prénom">

                        <input type="text" placeholder="Nom">

                        <input type="email" placeholder="Adresse email">

                        <input type="tel" placeholder="Téléphone">

                        <input type="date">

                        <input type="time">

                        <input type="text"
                            placeholder="Adresse de livraison"
                            class="full-width">

                        <textarea
                            placeholder="Informations complémentaires"
                            class="full-width"></textarea>

                    </div>

                </section>

                <!-- MENU -->

                <section class="card">

                    <h2>Menu sélectionné</h2>

                    <div class="selected-menu">

                        <img
                            src="https://images.unsplash.com/photo-1547592180-85f173990554?q=80&w=1400"
                            alt="Menu gastronomique">

                        <div>

                            <h3>Menu Signature</h3>

                            <p>
                                Menu gastronomique composé
                                d’une entrée raffinée, d’un plat premium
                                et d’un dessert maison.
                            </p>

                            <span class="badge">
                                Minimum 10 personnes
                            </span>

                        </div>

                    </div>

                </section>

                <!-- ORDER DETAILS -->

                <section class="card">

                    <h2>Détails de la commande</h2>

                    <div class="form-grid">

                        <input type="number"
                            placeholder="Nombre de personnes">

                        <select>

                            <option>
                                Choisir un régime
                            </option>

                            <option>
                                Classique
                            </option>

                            <option>
                                Végétarien
                            </option>

                            <option>
                                Vegan
                            </option>

                        </select>

                    </div>

                    <!-- CONDITIONS -->

                    <div class="conditions">

                        <h3>Conditions du menu</h3>

                        <p>
                            Ce menu doit être commandé
                            au minimum 72h avant la prestation.
                            Conserver les produits au frais après livraison.
                        </p>

                    </div>

                </section>

            </div>

            <!-- RIGHT -->

            <aside class="summary card">

                <h2>Résumé</h2>

                <div class="summary-item">

                    <span>Menu Signature</span>

                    <span>250 €</span>

                </div>

                <div class="summary-item">

                    <span>Livraison</span>

                    <span>15 €</span>

                </div>

                <div class="summary-item">

                    <span>Réduction</span>

                    <span>-25 €</span>

                </div>

                <div class="summary-item total">

                    <span>Total</span>

                    <span>240 €</span>

                </div>

                <button class="btn">
                    Valider la commande
                </button>

            </aside>

        </div>

    </main>

</body>

</html>