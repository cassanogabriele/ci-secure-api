<?php
// La classe "UserModel" est définie dans ce namespace et étend la classe "CodeIgniter\Model", c'est un modèle qui interagit avec la table de la base de données "user".
namespace App\Models;

use CodeIgniter\Model;
use Exception;


class UserModel extends Model
{
    // Permet au modèle de savoir quelles colonnes de la table peuvent être mises à jour
    protected $table = 'user';

    // Tableau spécifiant quelles colonnes de la table peut être mises à jour via le modèle. 
    protected $allowedFields = [
        'id',
        'name',
        'email',
        'password',
        'active',
        'softdelete'
    ];

    // Utilisé pour spécifier le nom du champ qui enregistrera la date de la dernière mise à jour.
    protected $updatedField = 'updated_at';
    /*
    Crochets d'événements permettant d'exécuter des méthodes spécifiques avant d'insérer ou de mettre à jour 
    des données dans la base de données. 
    */
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    // Fonctions permettant d'effectuer une opération sur l'entité "User" avant de l'enregistrer dans la base de données.

    /*
    Ces méthodes sont exécutées avant d'insérer ou de mettre à jour des données dans la base de données. Elles prennent un tableau 
    de données en entrée et retournent un tableau de données modifié. Elles appellent la méthode "getUpdatedDataWithHashedPassword"
    permettant de hasher le mot de passe. 
    */
    protected function beforeInsert(array $data): array
    {
        // Le mot de passe de l'utilisateur est hashé avant d'être enregistré en base de données
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    protected function beforeUpdate(array $data): array
    {
        return $this->getUpdatedDataWithHashedPassword($data);
    }

    /*
    Elle prend un tableau de données en entrée et vérifie s'il contient un clé "password". Si c'est le case, 
    elle prend le mot de passe en texte clair et le hashe, en utilisant la méthode "hashPassword", puis 
    met à jour le tableau de données avec le mot de passe hashé. 
    */
    private function getUpdatedDataWithHashedPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $plaintextPassword = $data['data']['password'];
            $data['data']['password'] = $this->hashPassword($plaintextPassword);
        }
        return $data;
    }

    // Elle prend un mot de passe en texte clair et le hashe en utilisant l'algorithme de hachage bcrypt
    private function hashPassword(string $plaintextPassword): string
    {
        return password_hash($plaintextPassword, PASSWORD_BCRYPT);
    }

    public function getAllData()
    {
        return $this->get()->getResultArray();
    }

    /*
    Méthode qui prend une adresse e-mail en entrée, recherche un utilisateur dans la base de données
    avec cette adresse e-mail et renvoie un tableau associatif représentant l'utilisateur trouvé. Si aucun 
    utilisateur n'est trouvé, une exception est déclenché. 
    */
    public function findUserByEmailAddress(string $emailAddress)
    {
        $user = $this
            ->asArray()
            ->where(['email' => $emailAddress])
            ->first();

        if (!$user)
            throw new Exception("L'utilisateur n'existe pas pour cet adresse email");

        return $user;
    }

    public function logUser($email, $password)
    {
        $user_roles_model = new UserRoleModel();
        $user = $this->findUserBy(['email' => $email]);

        //Si user n'existe pas
        if($user == null)
            return null;

        //Si ne corresponds pas, alors on renvoi null sinon on renvoi l'user avec ses rôles
        if (!password_verify($password, $user['password']))
            return null;
         
        // On récupèrera un token avec un "payload" qui est la partie contenant les identifiants et les rôles de l'utilisateur
        $user['roles'] = $user_roles_model->getRoleByUserId($user['id']);
        return $user;
    }
    
    public function list()
    {               
        $user = $this
            ->asArray()
            ->select('u.id, u.name, u.email, u.active, u.created_at, r.name AS role_name')
            ->from('user u')
            ->join('user_roles urole', 'urole.user_id = u.id')
            ->join('role r', 'r.id = urole.role_id')
            ->where(['u.softdelete' => 0])
            ->findAll();

        return $user;
    }

    public function listRecovered()
    {               
        $user = $this
            ->asArray()
            ->select('u.id, u.name, u.email, u.active, u.created_at, r.name AS role_name')
            ->from('user u')
            ->join('user_roles urole', 'urole.user_id = u.id')
            ->join('role r', 'r.id = urole.role_id')
            ->where(['u.softdelete' => 1])
            ->findAll();

        return $user;
    }
    
    public function listAdministrators()
    {               
        $administrator = $this
            ->asArray()
            ->distinct()
            ->select('u.id, u.name, u.email, u.active, u.created_at, r.name AS role_name')
            ->from('user u')
            ->join('user_roles urole', 'urole.user_id = u.id')
            ->join('role r', 'r.id = urole.role_id')
            ->where(['urole.role_id' => 1, 'u.softdelete' => 0])
            ->findAll();

        return $administrator;
    }

    public function listRecoveredAdministrators()
    {               
        $recoveredAdministrator = $this
            ->asArray()
            ->distinct()
            ->select('u.id, u.name, u.email, u.active, u.created_at, r.name AS role_name')
            ->from('user u')
            ->join('user_roles urole', 'urole.user_id = u.id')
            ->join('role r', 'r.id = urole.role_id')
            ->where(['urole.role_id' => 1, 'u.softdelete' => 1])
            ->findAll();

        return $recoveredAdministrator;
    }

    public function findUserById($id)
    {
        $user = $this
            ->asArray()
            ->where('id', $id)
            ->first();

        return $user;
    }

    public function findUserBy($associativeArray)
    {
        $key = array_keys($associativeArray)[0];
        $value = $associativeArray[$key];

        if (!is_string($key) || !is_string($value))
            throw new Exception('The associative array must contain string keys and values.');

        $user = $this
            ->asArray()
            ->where($key, $value)
            ->first();

        return $user;
    }

    public function emailExist(string $email, $id = 0)
    {
        $user = null;

        if ($id > 0) {
            $user = $this
                ->asArray()
                ->where(['email' => $email])
                ->where('id !=', $id)
                ->first();
        } else {
            $user = $this
                ->asArray()
                ->where(['email' => $email])
                ->first();
        }

        return $user;
    }

    public function activate($userId)
    {
        $this
            ->where('id', [$userId])
            ->set(['active' => 1])
            ->update();

        return true;
    }

    public function deactivate($userId)
    {
        $this
            ->where('id', [$userId])
            ->set(['active' => 0])
            ->update();

        return true;
    }

    public function softDeleteUser($userId){
        $this
        ->where('id', [$userId])
        ->set(['softdelete' => 1])
        ->update();

        return true;
    }

    public function reactivateUser($userId)
    {
        $this
        ->where('id', [$userId])
        ->set(['softdelete' => 0])
        ->update();

        return true;
    }
}
