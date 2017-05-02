<?php

namespace SocNet\Communities\Form;

use SocNet\Core\UuidGenerator\UuidGeneratorInterface;
use SocNet\Users\User;
use SocNet\Communities\Commands\StoreCommunityCommand;
use SocNet\Core\Form\DataTransformers\NullToEmptyStringDataTransformer;
use Symfony\Component\Form\Extension\Core\Type\BaseType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommunityType extends BaseType
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
        $builder->add('name', TextType::class);
        $builder->add('description', TextareaType::class, [
            'required' => false
        ]);

        $transformer = new NullToEmptyStringDataTransformer();

        $builder->get('name')->addModelTransformer($transformer);
        $builder->get('description')->addModelTransformer($transformer);
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $uuidGenerator = $this->uuidGenerator;

        $resolver->setDefaults([
            'data_class' => StoreCommunityCommand::class,
            'empty_data' => function (FormInterface $form) use ($uuidGenerator) : StoreCommunityCommand {
                return new StoreCommunityCommand(
                    $uuidGenerator->generate(),
                    $form->get('name')->getData(),
                    $this->author,
                    $form->get('description')->getData()
                );
            }
        ]);
    }
}
