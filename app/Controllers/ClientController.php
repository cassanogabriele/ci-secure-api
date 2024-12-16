<?php 
namespace App\Controllers;

use App\Models\ClientModel;
use App\Controllers\ApiController;

use CodeIgniter\HTTP\ResponseInterface;
use App\DTO\Response\Client\ClientResponseDTO;
use App\DTO\Request\Client\ClientReadRequestDTO;
use App\DTO\Request\Client\ClientCreateRequestDTO;
use App\DTO\Request\Client\ClientUpdateRequestDTO;

class ClientController extends ApiController
{    
    public function create()
    {
        $json = $this->getRequestInput($this->request);
        $client =  new ClientCreateRequestDTO($json);

        $errors = $client->validate();
        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }

        $clientModel = new ClientModel();
        $result = $clientModel->findClientByEmail($client->email);

        if($result)
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, 'Il existe déjà un client possédant cet email.');

        
        $clientModel->save($this->getRequestInput($this->request, false));

        $response = new ClientResponseDTO();
        $response->id = $clientModel->insertID;
        $response->name = $client->name;
        $response->email = $client->email;
            
        return $this->success($response);
    }

    public function read($id)
    {
        // Méthode de modèle
        $clientModel = new ClientModel();
        $client = $clientModel->findClientById($id);      

        if($client == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, 'Aucun client ne correspond à cet id.');

        $response = new ClientResponseDTO();
        $response->id = $client["id"];
        $response->name = $client["name"];
        $response->email = $client["email"];
        $response->retainer_fee = $client["retainer_fee"];

        return $this->success($response);
    }       

    public function update($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->findClientById($id);    

        if($client == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun client ne correspond à cet id.");

        $json = $this->getRequestInput($this->request);
        $client =  new ClientUpdateRequestDTO($json);

        if($clientModel->emailExist($client->email,$id))
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "L'email existe déjà");

        $errors = $client->validate();
        if (count($errors) > 0) {
            return $this->error(ResponseInterface::HTTP_BAD_REQUEST, $errors);
        }

        $clientModel->update($id, $this->getRequestInput($this->request, false));   
        
        $response = new ClientResponseDTO();
        $response->id = $id;
        $response->name = $client->name;
        $response->email = $client->email;
        $response->retainer_fee = $client->retainer_fee;

        return $this->success($response);
    }

     // Activer le client s'il n'est pas actif 
     public function activateClient($id)
     {
        $clientModel = new ClientModel();
        $client = $clientModel->findClientById($id); 
 
        if($client == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun client ne correspond à cet id.");
 
        $clientModel->activate($id);
 
        return $this->respond(['message' => 'Client activé avec succès.'], 200);
     }
 
    // Désactiver le client si il est actif
    public function deactivateClient($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->findClientById($id); 
 
        if($client == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, "Aucun client ne correspond à cet id.");

        $clientModel->deactivate($id);
 
        return $this->respond(['message' => 'Client désactivé avec succès.'], 200);
    }

    public function delete($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->findClientById($id); 

        if($client == null)
            return $this->error(ResponseInterface::HTTP_NOT_FOUND, 'Aucun client ne correspond à cet id.');

        $clientModel->softDeleteClient($id);  
        
        return $this->success([],ResponseInterface::HTTP_NO_CONTENT,"Le client à bien été supprimé.");
    }   

    public function list()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->list();

        $response = array();
        
        foreach($clients as $client){
            $clientDTO = new ClientResponseDTO();
            $clientDTO->id = $client['id'];
            $clientDTO->name = $client['name'];
            $clientDTO->email = $client['email'];
            $clientDTO->created_at = date('d-m-Y', strtotime($client['created_at']));
            $clientDTO->active = $client['active'];

            $response[] = $clientDTO;
        }

        // Utilisation de la méthode json() pour renvoyer la réponse au format JSON
        return $this->response->setJSON($response);
    }    

    public function listRecovered()
    {
        $clientModel = new ClientModel();
        $clients = $clientModel->listRecovered();
    
        $response = array();
    
        foreach($clients as $client){
            $clientDTO = new ClientResponseDTO();
            $clientDTO = new ClientResponseDTO();
            $clientDTO->id = $client['id'];
            $clientDTO->name = $client['name'];
            $clientDTO->email = $client['email'];
            $clientDTO->created_at = date('d-m-Y', strtotime($client['created_at']));
            $clientDTO->active = $client['active'];
    
           $response[] = $clientDTO;
        }
    
        return $this->success($response);
    }

    public function reactivateClients($id)
    {
        $clientModel = new ClientModel();
        $client = $clientModel->reactivateClient($id); 
        
        return $this->respond(['message' => 'L\'utilisateur a été réactivé avec succès.'], 200);
    }
}