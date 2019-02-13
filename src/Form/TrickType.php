<?php 

namespace App\Form;

use App\Entity\Trick;
use App\Entity\TrickGroup;
use App\Form\PictureType;
use App\Form\VideoType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class, ['required' => false])
            ->add('trickGroup', EntityType::class, ['class' => TrickGroup::class, 'choice_label' => 'name'])
            ->add('pictures', CollectionType::class, ['entry_type' => PictureType::class, 'allow_add' => true, 'allow_delete' => true, 'label' => false, 'by_reference' => false])
            ->add('videos', CollectionType::class, ['entry_type' => VideoType::class, 'allow_add' => true, 'allow_delete' => true, 'label' => false, 'by_reference' => false])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Trick::class]);
    }
}