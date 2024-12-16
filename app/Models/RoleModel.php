<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class RoleModel extends Model
{
    // Permet au modèle de savoir avec quelle table de base de données, il fonctionne principalement
    protected $table = 'role';

    // Permet au modèle de savoir quelles colonnes de table peuvent être mis à jour
    protected $allowedFields = [
        'id',
        'name',
    ];

    protected $updatedField = 'updated_at';

    public function getById($id)
    {
        $role = $this
                ->asArray()
                ->where(['id' => $id])
                ->first();

        return $role;
    }

    // Récupérer les rôles
    public function getRoles()
    {
        $roles = $this
            ->findAll();

        return $roles;
    }
}
