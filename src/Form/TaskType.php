<?php

namespace App\Form;

use Bartender;
use App\Entity\Tag;
use App\Entity\Task;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class TaskType extends AbstractType
{
    /**
     *
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $bartender = new Bartender();
        $filteredBeerListNameName = $bartender->filterBeerList();

        $builder
            ->add('name', ChoiceType::class, [
                'choices' => $filteredBeerListNameName,
                'label' => $this->translator->trans('general.name')
            ])
            ->add('description', TextareaType::class, [
                'label' => $this->translator->trans('general.description')
            ])
            ->add('dueAt', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => $this->translator->trans('general.due_date')
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'label' => $this->translator->trans('general.category'),
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name'
            ])
            ->add('save', SubmitType::class, [
                'label' => $this->translator->trans('general.button.success'),
                'attr' => [
                    'class' => 'btn-danger'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
