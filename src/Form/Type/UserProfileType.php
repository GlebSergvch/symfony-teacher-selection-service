<?php

namespace App\Form\Type;

use App\Enum\UserProfileGender;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Логин пользователя',
                'attr' => [
                    'placeholder' => 'Имя пользователя',
                    'class' => 'user-profile-firstname',
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Фамилия пользователя',
                'attr' => [
                    'placeholder' => 'Фамилия пользователя',
                    'class' => 'user-profile-lastname',
                ],
            ])
            ->add('middlename', TextType::class, [
                'label' => 'Отчество пользователя',
                'attr' => [
                    'placeholder' => 'Отчество пользователя',
                    'class' => 'user-profile-middlename',
                ],
            ])
            ->add('gender', EnumType::class, [
                'class' => UserProfileGender::class,
                'choice_label' => fn ($choice) => match ($choice) {
                    UserProfileGender::MALE => 'male',
                    UserProfileGender::FEMALE => 'female'
                },
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Возраст',
            ])
            ->add('submit', SubmitType::class);
    }
}