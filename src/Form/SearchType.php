<?php

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

    class SearchType extends AbstractType
    {

        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('string', TextType::class,[
                    'label' => false,
                    'required' => false,
                    'attr' =>
                    [
                        'placeholder' => 'Votre recherche...',
                        'class' => 'form-control-sm'
                    ]
                ])
                ->add('categories', EntityType::class,[
                    'label' => false,
                    'required' => false,
                    'class' => Category::class,
                    'multiple' => true,
                    'expanded' => true // Vue sous forme de checkbox
                ])
                ->add('submit', SubmitType::class,[
                    'label' => 'Filtrer',
                    'attr' =>[
                        'class' => 'btn-block btn-info'
                    ]
                ])
            ;
        }



        public function configureOptions(OptionsResolver $resolver): void
        {
            $resolver->setDefaults([
                'data_class' => Search::class,
                'method' => 'GET', // Permet de copier/coller l'URL à des personnes en gardant le filtre activé
                'crsf_protection' => false // Variable liée à la cybersécurité et la protection des informations à l'envoi du formulaire, on la désactive par manque de temps
            ]);
        }

        public function getBlockPrefix()
        {
            return ''; // Pas besoin du nom de la classe dans l'URL
        }
    }