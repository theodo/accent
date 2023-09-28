<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class BookController extends AbstractController
{
    public function __invoke(): Book
    {
        return new Book();
    }
}
