<?php 

// Pour faciliter la génération et la vérification des tokens, cela permet de séparer les préoccupations dans l'application.
use App\Models\UserModel;
use Config\Services;
use Firebase\JWT\JWT;
use Firebase\JWT\Key; 


// Fonction pour générer un JWT signé pour un utilisateur
function getSignedJWTForUser($user)
{
    // Obtenir le timestamp de l'instant actuel
    $issuedAtTime = time();

    // Obtenir la durée de vie du token à partir de la configuration de l'application
    $tokenTimeToLive = getenv('JWT_TIME_TO_LIVE');

    // Calcul de l'expiration du token en ajoutant la durée de vie au timestamp d'émission
    $tokenExpiration = $issuedAtTime + $tokenTimeToLive;

    // Création du contenu (payload) du token avec l'e-mail de l'utilisateur, le timestamp d'émission et l'expiration
    $payload = [
        'email' => $user['email'],
        'roles' =>$user['roles'],
        'iat' => $issuedAtTime,
        'exp' => $tokenExpiration,
    ];

    // Encodage du payload en utilisant la clé secrète et l'algorithme HS256 pour créer le JWT
    $jwt = JWT::encode($payload, Services::getSecretKey(), 'HS256');

    // Renvoi du JWT généré
    return $jwt;
}