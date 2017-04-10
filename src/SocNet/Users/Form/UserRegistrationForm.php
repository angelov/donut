<?php

namespace SocNet\Users\Form;

use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use SocNet\Users\Commands\StoreUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// @todo Extract data mapper to separate class

class UserRegistrationForm extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Please confirm your password.'
        ]);

        $builder->setDataMapper($this);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('email')->addModelTransformer($transformer);
        $builder->get('password')->get('first')->addModelTransformer($transformer);
        $builder->get('password')->get('second')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StoreUserCommand::class,
            'empty_data' => null
        ]);
    }

    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);

        $forms['name']->setData($data ? $data->getName() : '');
        $forms['email']->setData($data ? $data->getEmail() : '');
        $forms['password']->get('first')->setData('');
        $forms['password']->get('second')->setData('');
    }

    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        $data = new StoreUserCommand(
            $forms['name']->getData(),
            $forms['email']->getData(),
            $forms['password']->get('second')->getData()
        );
    }
}
