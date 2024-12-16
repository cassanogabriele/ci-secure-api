<?php
namespace App\DTO\Request;

use App\Interfaces\IValidator;
use Symfony\Component\Validator\Validation;

class DTORequest implements IValidator
{
    protected $validator;
    protected $violations;
    protected $errors;
    
    /**
     * Constructeur
     *
     * @return void
     */
    public function __construct($json = null)
    {
        $this->validator = Validation::createValidator();
        
        if($json != null)
        {
            $jsonArray = json_decode($json, true);
            foreach($jsonArray as $key=>$value)
                $this->$key = $value;
        }            
    }
        
    /**
     * Méthode de base de validator
     *
     * @return string[] messages d'erreur
     */
    public function validate()
    {
        throw new \Exception("Méthode validate() non redefinie");
    }

    protected function getErrors(){
        // Boucle pour alimenter le tableau de retour
        foreach($this->violations as $violation){
            if(count($violation) > 0)
            {
                foreach($violation as $error){
                    // getMessage() : fonction de Symfony qui renvoit les messages
                    $this->errors[] = $error->getMessage();
                }
            }
        }
        
        return $this->errors;
    }

}