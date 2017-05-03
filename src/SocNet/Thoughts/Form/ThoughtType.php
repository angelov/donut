<?php

namespace SocNet\Thoughts\Form;

use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Thoughts\Commands\StoreThoughtCommand;
use SocNet\Users\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThoughtType extends AbstractType
{
    private $author;
    private $uuidGenerator;

    public function __construct(User $author, UuidGeneratorInterface $uuidGenerator)
    {
        $this->author = $author;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder->add('content', TextareaType::class);

        $builder->get('content')->addModelTransformer(new NullToEmptyStringDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => StoreThoughtCommand::class,
            'empty_data' => function (FormInterface $form) : StoreThoughtCommand {
                return new StoreThoughtCommand(
                    $this->uuidGenerator->generate(),
                    $this->author,
                    $form->get('content')->getData()
                );
            }
        ]);
    }
}
