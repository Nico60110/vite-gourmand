<?php

class Utilisateur
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function emailExiste(string $email): bool
    {
        $sql = "SELECT idUtilisateur
                FROM utilisateur
                WHERE email = ?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$email]);

        return (bool) $query->fetch();
    }

    public function inscrire(
        string $prenom,
        string $nom,
        string $email,
        string $password,
        string $telephone,
        string $adresse,
        string $codePostal,
        string $ville,
        string $pays
    ): bool {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $idRole = 3;

        $sql = "INSERT INTO utilisateur
        (
            prenom,
            nom,
            email,
            motDePasse,
            telephone,
            adresse,
            codePostal,
            ville,
            pays,
            idRole
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            $prenom,
            $nom,
            $email,
            $hashedPassword,
            $telephone,
            $adresse,
            $codePostal,
            $ville,
            $pays,
            $idRole
        ]);
    }

    public function connexion(string $email, string $password)
    {
        $sql = "SELECT *
                FROM utilisateur
                WHERE email = ?
                AND actif = 1";

        $query = $this->pdo->prepare($sql);
        $query->execute([$email]);

        $user = $query->fetch();

        if (
            $user &&
            password_verify(
                $password,
                $user['motDePasse']
            )
        ) {
            return $user;
        }

        return false;
    }

    public function getByEmail(string $email)
    {
        $sql = "SELECT *
                FROM utilisateur
                WHERE email = ?";

        $query = $this->pdo->prepare($sql);
        $query->execute([$email]);

        return $query->fetch();
    }

    public function creerResetToken(
        string $email,
        string $token,
        string $expire
    ): bool {

        $sql = "UPDATE utilisateur
                SET resetToken = ?,
                    resetExpire = ?
                WHERE email = ?";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            $token,
            $expire,
            $email
        ]);
    }

    public function getByResetToken(string $token)
    {
        $sql = "SELECT *
                FROM utilisateur
                WHERE resetToken = ?
                AND resetExpire > NOW()";

        $query = $this->pdo->prepare($sql);
        $query->execute([$token]);

        return $query->fetch();
    }

    public function modifierMotDePasse(
        int $idUtilisateur,
        string $password
    ): bool {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE utilisateur
                SET motDePasse = ?,
                    resetToken = NULL,
                    resetExpire = NULL
                WHERE idUtilisateur = ?";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            $hashedPassword,
            $idUtilisateur
        ]);
    }

    public function getById(int $idUtilisateur)
    {
        $sql = "
            SELECT *
            FROM utilisateur
            WHERE idUtilisateur = ?
        ";

        $query = $this->pdo->prepare($sql);
        $query->execute([$idUtilisateur]);

        return $query->fetch();
    }

    public function emailUtiliseParAutre(
        string $email,
        int $idUtilisateur
    ): bool {

        $sql = "
            SELECT idUtilisateur
            FROM utilisateur
            WHERE email = ?
            AND idUtilisateur != ?
        ";

        $query = $this->pdo->prepare($sql);

        $query->execute([
            $email,
            $idUtilisateur
        ]);

        return (bool) $query->fetch();
    }

    public function modifierProfil(
        int $idUtilisateur,
        string $nom,
        string $prenom,
        string $email,
        string $telephone,
        string $adresse,
        string $codePostal,
        string $ville,
        string $pays
    ): bool {

        $sql = "
            UPDATE utilisateur
            SET
                nom = ?,
                prenom = ?,
                email = ?,
                telephone = ?,
                adresse = ?,
                codePostal = ?,
                ville = ?,
                pays = ?
            WHERE idUtilisateur = ?
        ";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            $nom,
            $prenom,
            $email,
            $telephone,
            $adresse,
            $codePostal,
            $ville,
            $pays,
            $idUtilisateur
        ]);
    }

    public function creerEmploye(
        string $prenom,
        string $nom,
        string $email,
        string $password
        ): bool {

        $hashedPassword = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        // Rôle employé
        $idRole = 2;

        $sql = "
            INSERT INTO utilisateur
            (
                prenom,
                nom,
                email,
                motDePasse,
                idRole
            )
            VALUES (?, ?, ?, ?, ?)
        ";

        $query = $this->pdo->prepare($sql);

        return $query->execute([
            $prenom,
            $nom,
            $email,
            $hashedPassword,
            $idRole
        ]);
    }
}