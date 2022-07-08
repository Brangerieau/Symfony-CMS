<?php

namespace Brangerieau\SymfonyCmsBundle\Form;

use Brangerieau\SymfonyCmsBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResetPasswordType extends AbstractType
{
    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => $this->translator->trans('The password fields must match.', [], 'symfonycms'),
                'required' => true,
                'first_options' => [
                    'label' => $this->translator->trans('New Password', [], 'symfonycms'),
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('Enter your new password', [], 'symfonycms'),
                    ],
                    'row_attr' => ['class' => 'mb-3'],
                ],
                'second_options' => [
                    'label' => $this->translator->trans('Repeat Password', [], 'symfonycms'),
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => $this->translator->trans('Retype your new password', [], 'symfonycms'),
                    ],
                    'row_attr' => ['class' => 'mb-3'],
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('Modify', [], 'symfonycms'),
                'attr' => ['class' => 'btn btn-primary w-md'],
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
