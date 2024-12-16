<?php 
namespace App\DTO\Request\Client;

class ClientConstraint
{
    // Name
    const NAME_MIN_LENGTH = 5;
    const NAME_MAX_LENGTH = 10;

    // Email
    const EMAIL_MIN_LENGTH = 15;
    const EMAIL_MAX_LENGTH = 255;    

}
