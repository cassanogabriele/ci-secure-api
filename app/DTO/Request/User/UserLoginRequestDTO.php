<?php
namespace App\DTO\Request\User;

use App\DTO\Request\DTORequest;
use App\DTO\Request\User\UserConstraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;

class UserLoginRequestDTO extends DTORequest
{
    public string $email = "";
    public string $password = "";

    public function __construct($json = null)
    {
        parent::__construct($json);
    }

    public function validate(): iterable 
    {
        $this->errors = [];  
        
        $this->violations[] = $this->validator->validate($this->email, [
            new NotBlank(["message" => "L'email est obligatoire."]),
            new Email(["message" => "L'email  '{{value}}' entré n'est pas valable."]),
            new Length([
                "min" => UserConstraint::EMAIL_MIN_LENGTH,
                "max" => UserConstraint::EMAIL_MAX_LENGTH,
                "minMessage" => "Votre email doit être de minimum " . UserConstraint::EMAIL_MIN_LENGTH . " caractères.",
                "maxMessage" => "Votre email doit être de maximum " . UserConstraint::EMAIL_MAX_LENGTH. " caractères",
            ]),
        ]);

        $this->violations[] = $this->validator->validate($this->password, [
            new NotBlank(["message" => "Le mot de passe est obligatoire."]),
            new Length([
                "min" => UserConstraint::PASSWORD_MIN_LENGTH,
                "max" => UserConstraint::PASSWORD_MAX_LEGNTH,
            ]),            
        ]);    

        return $this->getErrors();
    }
}