<?php
namespace App\Classe;

use App\Entity\CategoryAlbum;
use App\Entity\Tag;

class Search
{
    /**
     * @var string
     */
    public $string = '';

    /**
     * @var CategoryAlbum[]
     */
    public $CategoriesAlbum = [];

    /**
     * @var Tag[]
     */
    public $Tags = [];
}