<?php 
namespace App\DTO\Request\User;

class UserConstraint
{
    // Name
    const NAME_MIN_LENGTH = 5;
    const NAME_MAX_LENGTH = 10;

    // Email
    const EMAIL_MIN_LENGTH = 15;
    const EMAIL_MAX_LENGTH = 255;

    // Password 
    const PASSWORD_MIN_LENGTH = 10;
    const PASSWORD_MAX_LEGNTH = 255;
}

