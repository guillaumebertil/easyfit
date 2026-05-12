<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formulaire d'inscription d'un nouvel utilisateur.
 * Le champ plainPassword est haché automatiquement via hash_property_path et écrit dans User::$password.
 * Contraintes : 6 à 16 caractères, au moins une majuscule, et acceptation des CGV obligatoire.
 */
class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr'  => [
                    'placeholder' => 'John'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr'  => [
                    'placeholder' => 'Doe'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr'  => [
                    'placeholder' => 'john.doe@example.com'
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => '******',
                    ],
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 6,
                            'minMessage' => 'Le mot de passe doit contenir au minimum {{ limit }} caractères',
                            'max' => 16,
                            'maxMessage' => 'Le mot de passe ne doit pas dépasser {{ limit }} caractères'
                        ]),
                        new Assert\Regex([
                            'pattern' => '/[A-Z]/',
                            'message' => 'Le mot de passe doit contenir au moins une majuscule'
                        ])
                    ],
                    'hash_property_path' => 'password',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                'attr' => [
                    'placeholder' => '******',
                ],
                ],
                'mapped' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte'
            ])
            ->add('cgv', CheckboxType::class, [
                'label' => 'J\'accepte les conditions générales de vente',
                'mapped' => false,
                'constraints' => [
                    new Assert\IsTrue(),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [

            ],
            'data_class' => User::class,
        ]);
    }
}
