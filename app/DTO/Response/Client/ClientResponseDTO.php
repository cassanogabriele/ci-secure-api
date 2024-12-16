<?php

namespace App\DTO\Response\Client;

class ClientResponseDTO
{
    public int $id;
    public ?string $name = "";
    public ?string $email = "";
    public ?int  $retainer_fee = 0;
    public ?int $active = 0;
    public ?string $created_at = "";
}
