<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
              'label' => 'Adresse email',
              'required' => true,
              'attr' => [
              'placeholder' => 'adresse@email.fr'
              ]
             ])
            
             ->add('password', RepeatedType::class, [
              'type' => PasswordType::class,
              'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
              'label' => 'Saisissez un mot de passe',
              'required' => true,
              'first_options' => ['label' => 'Mot de passe'],
              'second_options' => ['label' => 'Confirmez le mot de passe']
              ]
             )
            
             ->add('submit', SubmitType::class, [
              'label' => 'Ok',
              'attr' => [
              'class' => 'btn btn-success w-100 mt-5'
              ]
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
