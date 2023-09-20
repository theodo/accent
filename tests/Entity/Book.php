<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

#[ApiResource(security:"is_granted('ROLE_USER_DEFAULT')")]
#[Get(security:"is_granted('ROLE_USER_GET')")]
#[Post]
#[Patch(security:"is_granted('ROLE_USER_PATCH')")]
class Book
{
    public ?int $id = null;

    public ?string $author = null;

    public ?string $title = null;
}
