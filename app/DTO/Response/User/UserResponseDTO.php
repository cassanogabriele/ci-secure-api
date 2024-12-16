<?php

namespace App\DTO\Response\User;

class UserResponseDTO
{
    public int $id = 0;
    public ?string $name = "";
    public ?string $email = "";
    public int $active;
    public string $role_user = "";
    public string $created_at = "";
}
