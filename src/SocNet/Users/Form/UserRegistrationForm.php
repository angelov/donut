<?php

namespace SocNet\Users\Form;

use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use SocNet\Users\Commands\StoreUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Please confirm your password.'
        ]);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('email')->addModelTransformer($transformer);
        $builder->get('plainPassword')->get('first')->addModelTransformer($transformer);
        $builder->get('plainPassword')->get('second')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationForm::class,
            'empty_data' => function(FormInterface $form) {
                new StoreUserCommand(
                    $form->get('name')->getData(),
                    $form->get('email')->getData(),
                    $form->get('plainPassword')->get('first')->getData()
                );
            }
        ]);
    }
}
