<?php
namespace App\Validation;

use App\Models\UserModel;
use Exception;

class UserRules
{
    /*
    3 paramètres 
    ************
    - $str : une chaîne de caractères qu'on souhaite valider. Dans le contexte de la validation d'un formulaire
    d'authentification, il s'agit généralement du mot de passe que l'utilisateur a saisi 
    - $fields : une chaîne de caractères qui contient le nom du champ qu'on est en train de valider, il s'agit de 
    "password"
    - $data : un tableau associatif qui contient les données du formulaire, y compris l'e-mail et le mot de passe 
    pour un utilisateur donné
    */

    // Vérification de la correspondance du mot de passe saisit, au mot de passe sotcké en base de données pour un utilisateur donné
    public function validateUser(string $str, string $fields, array $data): bool
    {
        try {
            /*
            Création d'un nouvel objet du modèle "UserModel", cela permet d'intéragir avec la base de données 
            et de rechercher l'utilisateur correspondant à l'adresse e-mail spécifié dans le tableau
            */           
            $model = new UserModel();
            /*
            Méthode du modèle "UserModel" appelée pour rechercher l'utilisateur dans la base de données en fonction de son e-mail. 
            Cette méthode retourne un tableau avec les informations de l'utilisateur, y compris son mot de passe. 
            */            
            $user = $model->findUserByEmailAddress($data['email']);
            /*
            Méthode utilisée pour comparer le mot de passe saisit avec le mot de passe stocké dans la base de données. 
            Si les mots de passe correspondent, la méthode retourne "true", la validation a réussi, sinon, elle retourne "false". 
            */
            return password_verify($data['password'], $user['password']);
        } catch (Exception $e) {
            // Si une exception est levée pendant le processus,la méthode renvoie également "false". 
            return false;
        }
    }
}
