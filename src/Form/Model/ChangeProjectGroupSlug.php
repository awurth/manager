<?php

namespace App\Form\Model;

use App\Validator\UniqueProjectGroupSlug;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueProjectGroupSlug()
 */
class ChangeProjectGroupSlug
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     * @Assert\Regex("/^[0-9a-z-]+$/")
     */
    public $slug;
}