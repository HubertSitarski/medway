<?php

namespace App\Form;

use App\Entity\Order;
use App\Model\OrderModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'first_name',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ])
                ],
            ])
            ->add('lastName', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'last_name',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ])
                ],
            ])
            ->add('address', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'address',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ])
                ],
            ])
            ->add('city', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'city',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ])
                ],
            ])
            ->add('postcode', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'postcode',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ])
                ],
            ])
            ->add('phone', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'phone',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ]),
                    new Length([
                        'max' => 9,
                        'min' => 9,
                        'minMessage' => $this->translator->trans('phone_error'),
                        'maxMessage' => $this->translator->trans('phone_error')
                    ])
                ],
            ])
            ->add('email', EmailType::class, [
                'translation_domain' => 'messages',
                'label' => 'email',
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('not_blank_error'),
                    ]),
                    new Email([
                        'message' => $this->translator->trans('email_error')
                    ]),
                ],
            ])
            ->add('nip', TextType::class, [
                'translation_domain' => 'messages',
                'label' => 'nip',
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
