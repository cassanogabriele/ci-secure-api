<?php 

namespace App\Controllers;

use App\DTO\Response\HttpResponse;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;

class ApiController extends BaseController
{
    use ResponseTrait; 

    // public function endpoint()
    // {
    //     $data = [
    //         'message' => 'Ceci est une réponse JSON depuis l\'endpoint',
    //         'status' => 'success'
    //     ];

    //     return $this->response->setJSON($data);
    // }

   
    // Configuration CORS 
    public function before(RequestInterface $request, $arguments = null)
    {
        // Configuration CORS pour ce contrôleur
        $this->response->setHeader('Access-Control-Allow-Origin', 'http://votre-application-angular.com');
        $this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST');
        $this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        $this->response->setHeader('Access-Control-Max-Age', '3600'); // Durée du cache CORS en secondes
        $this->response->setHeader('Access-Control-Allow-Credentials', 'true');

        if ($request->getMethod(true) === 'OPTIONS') {
            // Réponse immédiate pour les requêtes OPTIONS
            return $this->response;
        }

        return $request;
    }

    protected function error($status, $errors = null){
        $response = null;

        if($errors != null){
            // Si l'erreur n'est pas un tableau ou une châine
            if(!is_array($errors) && !is_string($errors)){
                // On déclenche une erreur
                throw new \Exception("Errors parameter need to be a string or an array of strings.");
            }

            // Si on reçoit une chaîne, on la convertir en tableau
            if(!is_array($errors)){
                $errors = [$errors];
            }

            $response = new HttpResponse($status, null, $errors);
            return $this->respond($response, $response->status);
        }
        else{
            $response = new HttpResponse($status);
            return $this->respond($response, $response->status);
        }
    }

    protected function success($data, $status = 200, $message = "")
    {
        // Si il n'y a pas contenu
        if($status == 204){
            $response = new HttpResponse($status, [], $message);
            return $this->respond($response, 200);
        }

        // Vérifie si c'est un tableau d'objets
        if(is_array($data)){
            foreach($data as $item){
                if(!is_object($item)){
                    throw new \Exception("Data parameter need to be an object.");
                }
            }
        } else if(!is_object($data)){
            throw new \Exception("Data parameter need to be an object.");
        }

        $response = new HttpResponse($status, $data, $message);
        return $this->respond($response, $response->status);        
    }
}