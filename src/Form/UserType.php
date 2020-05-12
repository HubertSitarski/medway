<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'translation_domain' => 'messages',
                'label' => 'email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Wprowadź email',
                    ]),
                    new Email([
                        'message' => 'Pole musi być poprawnym adresem e-mail'
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'translation_domain' => 'messages',
                'label' => 'password',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Wprowadź hasło',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Twoje hasło powinno zawierać minimum {{ limit }} znaków',
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
