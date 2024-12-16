<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ClientModel extends Model
{
    // Permet au modèle de savoir avec quelle table de base de données, il fonctionne principalement
    protected $table = 'client';

    // Permet au modèle de savoir quelles colonnes de table peuvent être mis à jour
    protected $allowedFields = [
        'name',
        'email',
        'active',
        'softdelete'
    ];

    protected $updatedField = 'updated_at';

    // Extraire un client de la base de données sen fonction de l'id fournit
    public function findClientById($id)
    {
        $client = $this
            // Méthode pour définir le mode de retour de données lors de la récupération d'un enregistrement 
            // de la base de données
            ->asArray()
            ->where(['id' => $id])
            ->first();

        if (!$client) throw new Exception("Pas trouvé pour cet id.");

        return $client;
    }

    public function list()
    {
        $clients = $this
            ->asArray()
            ->where(['softdelete' => 0])
            ->findAll();

        return $clients;
    }

    public function listRecovered()
    {
        $clients = $this
            ->asArray()
            ->where(['softdelete' => 1])
            ->findAll();

        return $clients;
    }

    public function findClientByEmail($email)
    {
        $client = $this
            ->asArray()
            ->where(['email' => $email])
            ->first();

        return $client;
    }

    public function emailExist(string $email, $id = 0)
    {
        $client = null;

        if ($id > 0) {
            $client = $this
                ->asArray()
                ->where(['email' => $email])
                ->where('id !=' , $id)
                ->first();
        } else {
            $client = $this
                ->asArray()
                ->where(['email' => $email])
                ->first();
        }

        return $client;
    }

    public function activate($clientId)
    {
        $this
            ->where('id', [$clientId])
            ->set(['active' => 1])
            ->update();

        return true;
    }

    public function deactivate($clientId)
    {
        $this
            ->where('id', [$clientId])
            ->set(['active' => 0])
            ->update();

        return true;
    }


    public function softDeleteClient($clientId)
    {
        $this
            ->where('id', [$clientId])
            ->set(['softdelete' => 1])
            ->update();

        return true;
    }   

    public function reactivateClient($clientId)
    { 
        $this
            ->where('id', [$clientId])
            ->set(['softdelete' => 0])
            ->update();

        return true;
    }
}
