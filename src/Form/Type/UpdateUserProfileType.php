<?php

namespace App\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class UpdateUserProfileType extends UserProfileType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->setMethod('PATCH');
    }
}