<?php
// Gestion de l'authentification d'utilisateurs
namespace App\Controllers;

use stdClass;
use Exception;
use App\Models\UserModel;
use App\Controllers\ApiController;
use App\DTO\Response\HttpResponse;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\DTO\Response\User\UserResponseDTO;
use App\DTO\Request\User\UserLoginRequestDTO;
use App\DTO\Response\User\UserLoginResponseDTO;
use App\DTO\Request\User\UserRegisterRequestDTO;

class AuthController extends ApiController
{
    use ResponseTrait;

    // Enregistrement d'un nouvel utilisateur 
    public function register()
    {
        // Récupération des données de la requête sous format JSON, elle tente de récupérer les données à partir du body de la requête "post".
        $json = $this->getRequestInput($this->request);

        // Transformation du JSON en objet.
        $user = new UserRegisterRequestDTO($json);

        // Validation de l'objet
        $errors = $user->validate();

        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }

        // Enregistrement utilisant les modèles
        $userModel = new UserModel();
        $userModel->save($this->getRequestInput($this->request, false));

        // Construction de la réponse
        $response = new UserResponseDTO();
        $response->id = 1; // Exemple
        $response->name = $user->name;
        $response->email = $user->email;

        return $this->success($response);
    }

    // Authentifier les utilisateurs existants 
    public function login()
    {        
        // Déclarations des variables
        $json = "";
        $errors = array();
        $user = null;
        $userModel = new UserModel(); 
        $data = new UserLoginResponseDTO();

        $json = $this->getRequestInput($this->request);
        $user = new UserLoginRequestDTO($json);

        // Validation de l'objet "Request"
        $errors = $user->validate();        
        if (count($errors) > 0) 
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        
        $loginResult = $userModel->logUser($user->email, $user->password);     
        if($loginResult == null)
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST,'Identifiants incorrects');
       
        // Création du JWT et retour
        helper('jwt');
        $data->access_token = getSignedJWTForUser($loginResult);

        return $this->success($data);        
    }    
}
