<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class UserRoleModel extends Model
{
    // Permet au modèle de savoir avec quelle table de base de données, il fonctionne principalement
    protected $table = 'user_roles';

    // Permet au modèle de savoir quelles colonnes de table peuvent être mis à jour
    protected $allowedFields = [
        'user_id',
        'role_id',
    ];

    protected $updatedField = 'updated_at';

    public function getRoleByUserId($user_id)
    {
        $roles = $this
                ->asArray()
                ->select('role.name, role.id')
                ->join('role', 'role.id = user_roles.role_id')
                ->where(['user_id' => $user_id])
                ->findAll();
                
        return $roles;
    }    

    public function deleteExistantUserRoles($user_id)
    {
        // Supprimer tous les rôles de l'utilisateur 
        $this->where('user_id', $user_id)->delete();

        return true;
    }
}
