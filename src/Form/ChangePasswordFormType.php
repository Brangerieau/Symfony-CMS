<?php

namespace Brangerieau\SymfonyCmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class ChangePasswordFormType extends AbstractType
{

    public function __construct(
        private TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank([
                            'message' => $this->translator->trans('Please enter a password', [], 'symfonycms'),
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => $this->translator->trans('Your password should be at least {limit} characters', ['{limit}' => 6], 'symfonycms'),
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'label' => $this->translator->trans('New password', [], 'symfonycms'),
                ],
                'second_options' => [
                    'attr' => ['autocomplete' => 'new-password'],
                    'label' => $this->translator->trans('Repeat Password', [], 'symfonycms'),
                ],
                'invalid_message' => $this->translator->trans('The password fields must match.', [], 'symfonycms'),
                // Instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
