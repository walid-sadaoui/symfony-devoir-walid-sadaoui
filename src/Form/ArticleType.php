<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ArticleType extends AbstractType
{

    private function getConfiguration($label, $placeholder, $options = []){
        return array_merge( [
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ] 
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, 
            $this->getConfiguration('Titre', 'Tapez un super titre pour votre article'))
            ->add('slug', TextType::class,
            $this->getConfiguration('Chaîne URL', 'Adresse web (automatique)', [
                'required' => false
            ]))
            ->add('excerpt', TextType::class,
            $this->getConfiguration('Résumé', "Donnez une description globale de l'article"))
            ->add('content', TextType::class,
            $this->getConfiguration('Description détaillée', 'Tapez une descriptio qui donne envie de venir chez vous'))
            ->add('coverImage', UrlType::class,
            $this->getConfiguration('URL de l\'image', "Donnez l'adresse de l'image qui vous donne envie"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
