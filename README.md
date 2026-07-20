## Présentation

Vite & Gourmand est une application web développée en PHP permettant la gestion d'un service de traiteur.

L'application permet :

- La consultation des menus.
- La création de commandes en ligne.
- La gestion des commandes.
- La gestion des employés.
- L'administration de l'application.
- La consultation de statistiques grâce à MongoDB Atlas.

---

# Technologies utilisées

- PHP 8
- HTML5
- CSS3
- JavaScript
- MySQL (MariaDB)
- PhpMyAdmin
- MongoDB Atlas
- PHPMailer
- Composer

---

# Prérequis


- PHP 8 ou supérieur
- Apache (XAMPP)
- MariaDB
- Composer
- MongoDB Atlas

---

# Installation

## 1. Cloner le dépôt

git clone https://github.com/Nico60110/vite_gourmand.git

Puis :

cd vite_gourmand

---

## 2. Installer les dépendances

Dans le dossier du projet :

```
composer install
```

---

## 3. Importer la base de données

Créer une base MySQL nommée :

```
vite_gourmand
```

Importer ensuite le fichier :

```
sql/vite_gourmand.sql
```

---

## 4. Configurer MySQL

Modifier le fichier :

```
config/database.php
```

Exemple :

```php
$host = "localhost";
$dbname = "vite_gourmand";
$user = "root";
$password = "";
```

---

## 5. Configurer MongoDB Atlas

Créer un cluster MongoDB Atlas.

Modifier ensuite :

```
config/mongo.php
```

Exemple :

```php
$client = new MongoDB\Client(
    "mongodb+srv://utilisateur:motdepasse@cluster.mongodb.net/"
);
```

---

## 6. Configurer PHPMailer

Modifier le fichier :

```
config/mail.php
```


Exemple :

```php
$mail->Host = "smtp.gmail.com";
$mail->Username = "mon_email@gmail.com";
$mail->Password = "mot_de_passe_application";
$mail->Port = 587;
```

---

## 7. Lancer l'application

Depuis Apache :

```
http://localhost/vite_gourmand/
```

---

# Comptes de démonstration

## Administrateur

Email :

```
admin@vitegourmand.fr
```

Mot de passe :

```
Admin-123456
```

---

## Employé

Email :

```
employe@gmail.com
```

Mot de passe :

```
Employe-123456
```

---

## Fonctionnalités

### Client

- Consulter les menus
- Passer une commande
- Contacter le traiteur

### Employé

- Consulter les commandes
- Gestion des menus
- Modifier le statut des commandes
- Gestion des avis
- Gestion des horaires
- Gestions des plats

### Administrateur

- Gestion des employés
- Consultation des statistiques
- Activation / désactivation des employés

---

# Structure du projet

```
config/
css/
images/
includes/
js/
pages/
sql/
vendor/
composer.json
composer.lock
git.ignore
index.php
test.php
```

---

# Déploiement

L'application est hébergée sur Alwaysdata.

La base MySQL est hébergée sur Alwaysdata.

Les statistiques sont stockées sur MongoDB Atlas.

---

