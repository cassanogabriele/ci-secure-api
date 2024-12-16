<?php 
namespace App\Controllers;

use App\Models\User;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use App\Controllers\ApiController;
use CodeIgniter\HTTP\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\DTO\Response\User\UserResponseDTO;
use App\DTO\Request\User\UserUpdateRequestDTO;
use App\DTO\Request\User\UserRegisterRequestDTO;
use App\DTO\Response\Role\RoleResponseDTO;
use Symfony\Component\HttpClient\Response\ResponseStream;

class UserController extends ApiController{
    use ResponseTrait;

    // Récupérer les rôles pour les afficher à la création d'un utilisateur
    public function getRoles()
    {
        $rolesModel = new RoleModel();
        $roles = $rolesModel->getRoles(); 
        
        // Création d'un tableau pour stocker les objets de réponse
        $response = [];

        // Parcourir les rôles récupérés pour créer des objets de réponse
        foreach ($roles as $role) {
            $roleResponse = new RoleResponseDTO();
            $roleResponse->id = $role['id'];
            $roleResponse->name = $role['name'];
            $roleResponse->created_at = $role['created_at'];

            // Ajoutez l'objet de réponse au tableau
            $response[] = $roleResponse;
        }

        return $this->success($response);
    }

    // Récupérer les rôles de l'utilisateur (pour l'ajout)
    public function getUserRoles($id)
    {
        $user_roles_model = new UserRoleModel();
        $userRoles = $user_roles_model->getRoleByUserId($id); 

        $response = [];

        // Parcourir les rôles récupérés pour créer des objets de réponse
        foreach ($userRoles as $userRole) {
            $userRoleResponse = new RoleResponseDTO();
            $userRoleResponse->id = $userRole['id'];
            $userRoleResponse->name = $userRole['name'];

            // Ajoutez l'objet de réponse au tableau
            $response[] = $userRoleResponse;
        }

        return $this->success($response);
    }

    // Création d'un utilisateur 
    public function create()
    {
        // Récupérer les données de la requête
        $json = $this->getRequestInput($this->request);
    
        // Valider les données avec le DTO
        $user = new UserRegisterRequestDTO($json);
    
        $errors = $user->validate();
    
        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }
    
        // Sauvegardez les informations de l'utilisateur
        $userModel = new UserModel();
       
        // Insérer l'utilisateur dans la table 'users'
        $userModel->save($this->getRequestInput($this->request, false));
    
        // Récupérer l'ID de l'utilisateur nouvellement inséré
        $userId = $userModel->getInsertID();

        // Récupérer les rôles à attribuer à l'utilisateur
        $roles = $this->request->getJSON(true)['roles'] ?? [];
      
        $userRoleModel = new UserRoleModel();
    
        // Si des rôles sont spécifiés, les attribuer à l'utilisateur
        if (!empty($roles)) {
            // Créer une instance du modèle UserRoleModel
            $userRoleModel = new UserRoleModel();
    
            // Attribuer les rôles à l'utilisateur
            foreach ($roles as $roleId) {
                // Insérer l'association dans la table de liaison 'user_roles'
                $userRoleModel->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId,
                ]);
            }
        }
    
        $response = new UserResponseDTO();
        $response->id = $userId;
        $response->name = $user->name;
        $response->email = $user->email;
    
        return $this->success($response);
    }
    
    public function read($id)
    {
        $userModel = new UserModel();
        $user = $userModel->findUserById($id);

        if($user == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun utilisateur n'a été trouvé.");

        $response = new UserResponseDTO();
        $response->id = $id;
        $response->name = $user['name'];
        $response->email = $user['email'];

        return $this->success($response);        
    }   

    public function update($id)
    {
        $userModel = new UserModel();
        $user = $userModel->findUserById($id);

        if($user == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun utilisateur ne correspond à cet id.");

        $json = $this->getRequestInput($this->request);      
        $user =  new UserUpdateRequestDTO($json);

        if($userModel->emailExist($user->email,$id))
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "L'email existe déjà");

        $errors = $user->validate();

        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }

        $userModel->update($id, $this->getRequestInput($this->request, false));   
        
        $response = new UserResponseDTO();
        $response->id = $id;
        $response->name = $user->name;
        $response->email = $user->email;

        return $this->success($response);
    }

    // Activer l'utilisateur s'il n'est pas actif 
    public function activateUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->findUserById($id); 

        if($user == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun utilisateur ne correspond à cet id.");

        // Remplacez $userId par l'ID de l'utilisateur que vous souhaitez activer
        $userModel->activate($id);

        return $this->respond(['message' => 'Utilisateur activé avec succès.'], 200);
    }

    // Désactiver l'utilisateur si il est actif
    public function deactivateUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->findUserById($id); 

        if($user == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun utilisateur ne correspond à cet id.");

        // Remplacez $userId par l'ID de l'utilisateur que vous souhaitez activer
        $userModel->deactivate($id);

        return $this->respond(['message' => 'Utilisateur désactivé avec succès.'], 200);
    }

    public function delete($id)
    {
        $userModel = new UserModel();
        $user = $userModel->findUserById($id); 

        if($user == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun utilisateur ne correspond à cet id.");

        $userModel->softDeleteUser($id);  
        
        return $this->success([],ResponseInterface::HTTP_NO_CONTENT, "L'utilisateur à bien été supprimé.");
    }

    public function list()
    {
        $userModel = new UserModel();
        $users = $userModel->list();
    
        $response = array();
    
        foreach ($users as $user) {
            $userDTO = new UserResponseDTO();
            $userDTO->id = $user['id'];
            $userDTO->name = $user['name'];
            $userDTO->email = $user['email'];
            $userDTO->active = $user['active'];
            $userDTO->role_user = $user['role_name']; 
            $userDTO->created_at = date('d-m-Y', strtotime($user['created_at']));
    
            $response[] = $userDTO;
        }
    
        return $this->success($response);
    }

    public function listRecovered()
    {
        $userModel = new UserModel();
        $users = $userModel->listRecovered();
    
        $response = array();
    
        foreach ($users as $user) {
            $userDTO = new UserResponseDTO();
            $userDTO->id = $user['id'];
            $userDTO->name = $user['name'];
            $userDTO->email = $user['email'];
            $userDTO->active = $user['active'];
            $userDTO->role_user = $user['role_name']; 
            $userDTO->created_at = date('d-m-Y', strtotime($user['created_at']));
    
            $response[] = $userDTO;
        }
    
        return $this->success($response);
    }

    // Récupérer les administrateurs pour les afficher
    public function listAdministrators()
    {
        $userModel = new UserModel();
        $administrators = $userModel->listAdministrators();

        $response = array();
    
        foreach ($administrators as $administrator) {
            $userDTO = new UserResponseDTO();
            $userDTO->id = $administrator['id'];
            $userDTO->name = $administrator['name'];
            $userDTO->email = $administrator['email'];
            $userDTO->active = $administrator['active'];
            $userDTO->role_user = $administrator['role_name']; 
            $userDTO->created_at = date('d-m-Y', strtotime($administrator['created_at']));
    
            $response[] = $userDTO;
        }

        return $this->success($response);
    }    

    // Récupérer les administrateurs supprimés pour les afficher
    public function listRecoverdAdministrators()
    {
        $userModel = new UserModel();
        $administrators = $userModel->listRecoveredAdministrators();

        $response = array();
    
        foreach ($administrators as $administrator) {
            $userDTO = new UserResponseDTO();
            $userDTO->id = $administrator['id'];
            $userDTO->name = $administrator['name'];
            $userDTO->email = $administrator['email'];
            $userDTO->active = $administrator['active'];
            $userDTO->role_user = $administrator['role_name']; 
            $userDTO->created_at = date('d-m-Y', strtotime($administrator['created_at']));
    
            $response[] = $userDTO;
        }

        return $this->success($response);
    }    

    public function editRolesUser($id)
    {
        // Supprimer tous les rôles de l'utilisateur 
        $user_roles_model = new UserRoleModel();
        $userRoles = $user_roles_model->deleteExistantUserRoles($id); 

        // Ajouter tous les rôles cochés
        $roles = $this->request->getJSON(true)['roles'] ?? [];

        foreach ($roles as $roleId) {
            // Insérer l'association dans la table de liaison 'user_roles'
            $user_roles_model->insert([
                'user_id' => $id,
                'role_id' => $roleId,
            ]);
        }

        // Envoyer une réponse avec le code de statut HTTP 200 OK
        return $this->respond(['message' => 'Les rôles ont été modifiés avec succès'], 200);
    }

    public function reactivateUsers($id)
    {
        $userModel = new UserModel();
        $user = $userModel->reactivateUser($id); 
        
        return $this->respond(['message' => 'L\'utilisateur a été réactivé avec succès.'], 200);
    }


    // Création d'un administrateur
    public function createAdministrator()
    {
        // Récupérer les données de la requête
        $json = $this->getRequestInput($this->request);
    
        // Valider les données avec le DTO
        $user = new UserRegisterRequestDTO($json);
    
        $errors = $user->validate();
    
        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }
    
        // Sauvegardez les informations de l'utilisateur
        $userModel = new UserModel();
       
        // Insérer l'utilisateur dans la table 'users'
        $userModel->save($this->getRequestInput($this->request, false));
    
        // Récupérer l'ID de l'utilisateur nouvellement inséré
        $userId = $userModel->getInsertID();

        $userRoleModel = new UserRoleModel();

        // Attribuer le rôle d'administrateur
        $userRoleModel->insert([
            'user_id' => $userId,
            'role_id' => 1,
        ]);

        // Récupérer les rôles à attribuer à l'utilisateur
        $roles = $this->request->getJSON(true)['roles'] ?? [];
          
        $response = new UserResponseDTO();
        $response->id = $userId;
        $response->name = $user->name;
        $response->email = $user->email;
    
        return $this->success($response);
    }
}
