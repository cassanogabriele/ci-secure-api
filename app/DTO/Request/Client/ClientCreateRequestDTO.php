<?php
namespace App\DTO\Request\Client;

use App\DTO\Request\DTORequest;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

class ClientCreateRequestDTO extends DTORequest{
    public string $name = "";
    public string $email = "";
    public int $retainer_fee = 0;

    public function __construct($json = null)
    {
        parent::__construct($json);
    }

    public function validate(): iterable  
    {
        $this->errors = [];        

        $this->violations[] = $this->validator->validate($this->name, [
            new NotBlank(["message" => "Le nom est obligatoire."]),
            new Length([
                "min" => ClientConstraint::NAME_MIN_LENGTH,
                "max" => ClientConstraint::NAME_MAX_LENGTH,
                "minMessage" => "Votre nom doit être de minimum ". ClientConstraint::NAME_MIN_LENGTH . " caractères.",
                "maxMessage" => "Votre nom doit être de maximum " . ClientConstraint::NAME_MAX_LENGTH . " caractères.",
            ]),
        ]);

        $this->violations[] = $this->validator->validate($this->email, [
            new NotBlank(["message" => "L'email est obligatoire."]),
            new Email(["message" => "L'email ".$this->email." entré n'est pas valable."]),
            new Length([
                "min" => ClientConstraint::EMAIL_MIN_LENGTH,
                "max" => ClientConstraint::EMAIL_MAX_LENGTH,
                "minMessage" => "Votre email doit être de minimum " . ClientConstraint::EMAIL_MIN_LENGTH . " caractères.",
                "maxMessage" => "Votre email doit être de maximum " . ClientConstraint::EMAIL_MAX_LENGTH. " caractères",
            ]),
        ]);

        return $this->getErrors();        
    }
}
