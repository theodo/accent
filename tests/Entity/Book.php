<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;

#[ApiResource(security: "is_granted('ROLE_USER_DEFAULT')")]
#[Get(security: "is_granted('ROLE_USER_GET')")]
#[Get(name: 'publication', routeName: 'book_get_publication', security: "is_granted('ROLE_USER_CUSTOM_CONTROLLER')")]
#[Post]
#[Patch(security: "is_granted('ROLE_USER_PATCH')")]
class Book
{
    public ?int $id = null;

    public ?string $author = null;

    public ?string $title = null;
}
