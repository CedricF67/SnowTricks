<?php 

namespace App\Form;

use App\Entity\TrickPicture;
use App\Form\PictureType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickPicturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pictures', CollectionType::class, ['entry_type' => PictureType::class, 'allow_add' => true, 'allow_delete' => true, 'label' => false, 'by_reference' => false, 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
        ;
    }
}