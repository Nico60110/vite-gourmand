<?php

class Menu
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function filtrer(float $prixMax = 0, string $theme = '', string $regime = '', int $personnesMin = 0): array
    {
        $sql = 'SELECT * FROM menu WHERE 1=1';
        $params = [];

        if ($prixMax > 0) {
            $sql .= ' AND (prixParPersonne * nbPersonnesMin) <= ?';
            $params[] = $prixMax;
        }
        if ($theme !== '') {
            $sql .= ' AND theme = ?';
            $params[] = $theme;
        }
        if ($regime !== '') {
            $sql .= ' AND regime = ?';
            $params[] = $regime;
        }
        if ($personnesMin > 0) {
            $sql .= ' AND nbPersonnesMin <= ?';
            $params[] = $personnesMin;
        }

        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $idMenu): array|false
    {
        $query = $this->pdo->prepare('SELECT * FROM menu WHERE idMenu = ?');
        $query->execute([$idMenu]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getPlats(int $idMenu): array
    {
        $sql = 'SELECT p.*, mp.typeMenu
                FROM menu_plat mp
                INNER JOIN plat p ON mp.idPlat = p.idPlat
                WHERE mp.idMenu = ?';
        $query = $this->pdo->prepare($sql);
        $query->execute([$idMenu]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllergenesParPlat(array $plats): array
    {
        if ($plats === []) {
            return [];
        }

        $ids = array_values(array_unique(array_map('intval', array_column($plats, 'idPlat'))));
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT pa.idPlat, a.nom
                FROM plat_allergene pa
                INNER JOIN allergene a ON pa.idAllergene = a.idAllergene
                WHERE pa.idPlat IN ($placeholders)";
        $query = $this->pdo->prepare($sql);
        $query->execute($ids);
        $allergenesParPlat = [];
        foreach ($query->fetchAll(PDO::FETCH_ASSOC) as $allergene) {
            $allergenesParPlat[$allergene['idPlat']][] = $allergene['nom'];
        }
        return $allergenesParPlat;
    }

    public function getAll(): array
    {
        $query = $this->pdo->query('SELECT * FROM menu');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function creer(string $titre, string $description, string $theme, string $regime, int $nbPersonnesMin, float $prixParPersonne, int $stock, string $conditions): bool
    {
        $query = $this->pdo->prepare('INSERT INTO menu (titre, description, theme, regime, nbPersonnesMin, prixParPersonne, stock, conditions) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        return $query->execute([$titre, $description, $theme, $regime, $nbPersonnesMin, $prixParPersonne, $stock, $conditions]);
    }

    public function modifier(int $idMenu, string $titre, string $description, string $theme, string $regime, int $nbPersonnesMin, float $prixParPersonne, int $stock, string $conditions): bool
    {
        $query = $this->pdo->prepare('UPDATE menu SET titre = ?, description = ?, theme = ?, regime = ?, nbPersonnesMin = ?, prixParPersonne = ?, stock = ?, conditions = ? WHERE idMenu = ?');
        return $query->execute([$titre, $description, $theme, $regime, $nbPersonnesMin, $prixParPersonne, $stock, $conditions, $idMenu]);
    }

    public function supprimer(int $idMenu): bool
    {
        $query = $this->pdo->prepare('DELETE FROM menu WHERE idMenu = ?');
        return $query->execute([$idMenu]);
    }
}
