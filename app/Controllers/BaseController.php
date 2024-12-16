<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\Validation\Exceptions\ValidationException;
use Config\Services;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */


/*
Etend le Controller de CodeIgniter, qui fournit des assistatnts et d'autres fonctions facilitant le traitement 
des requêtes entrantes. L'une des fonctions est "validate" qui utilise le service de validation de CodeIgniter pour vérifier 
une requête par rapport aux règles et aux messages d'erreur si nécessaire, spécifiées dans les fonctions du contrôleur. Cette fonction 
donne de bons résultats avec les requêtes de formulaire. Elle ne serait pas en mesure de valider les requêtes JSON brutes envoyées à 
l'API. Le contenu de la requête JSON est stocké dans le champ "body", tandis que le contenu de la requête "form-data" est 
stocké dnas le champ "post". 
*/
abstract class BaseController extends Controller
{    
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }

    public function getResponse(array $responseBody, int $code = ResponseInterface::HTTP_OK)
    {
        return $this
            ->response
            ->setStatusCode($code)
            ->setJSON($responseBody);
    }

    /*
    Contournement du problème de requête JSON stockée dans le champs "body" et du contenu de la requête "form-data" 
    stocké dans le champ "post". 
    */
    public function getRequestInput(IncomingRequest $request, $jsonFormat = true)
    {
        $input = $request->getPost();

        if (empty($input)) {

            if ($jsonFormat) {
                $input = $this->request->getBody();
            } else {
                // Convertit le corps de la requête en tableau associatif 
                $input = json_decode($request->getBody(), true);
            }
        }
        return $input;
    }
}
