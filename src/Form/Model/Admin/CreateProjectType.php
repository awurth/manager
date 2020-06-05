<?php

namespace App\Form\Model\Admin;

use Symfony\Component\Validator\Constraints as Assert;

class CreateProjectType
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=255)
     */
    public $name;
}