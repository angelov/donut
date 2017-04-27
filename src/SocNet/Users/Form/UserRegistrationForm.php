<?php

namespace SocNet\Users\Form;

use Doctrine\ORM\EntityManagerInterface;
use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use SocNet\Places\City;
use SocNet\Users\Commands\StoreUserCommand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// @todo Extract data mapper to separate class

class UserRegistrationForm extends AbstractType implements DataMapperInterface
{
    private $entityManager;

    // @todo use repository instead of entity manager
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder->add('name', TextType::class);
        $builder->add('email', EmailType::class);
        $builder->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Please confirm your password.'
        ]);
        $builder->add('city', ChoiceType::class, [
            'choices' => $this->entityManager->getRepository(City::class)->findAll(),
            'choice_label' => function(City $city) : string {
                return $city->getName();
            }
        ]);

        $builder->setDataMapper($this);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('email')->addModelTransformer($transformer);
        $builder->get('password')->get('first')->addModelTransformer($transformer);
        $builder->get('password')->get('second')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => StoreUserCommand::class,
            'empty_data' => null
        ]);
    }

    public function mapDataToForms($data, $forms) : void
    {
        $forms = iterator_to_array($forms);

        $forms['name']->setData($data ? $data->getName() : '');
        $forms['email']->setData($data ? $data->getEmail() : '');
        $forms['password']->get('first')->setData('');
        $forms['password']->get('second')->setData('');
    }

    public function mapFormsToData($forms, &$data) : void
    {
        $forms = iterator_to_array($forms);

        $data = new StoreUserCommand(
            $forms['name']->getData(),
            $forms['email']->getData(),
            $forms['password']->get('second')->getData(),
            $forms['city']->getData()
        );
    }
}
