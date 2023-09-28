<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;

#[ApiResource()]
#[Get(security: "is_granted('ROLE_USER_GET')")]
#[Put(securityPostDenormalize: "is_granted('ROLE_USER_PUT')")]
class Author
{
    public ?int $id = null;

    public ?string $name = null;
}
