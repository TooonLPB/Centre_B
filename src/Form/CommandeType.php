<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\Menu;
use App\Entity\Plat;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $starterIds = $options['starterIds'] ?? [];
        $maincourseIds = $options['maincourseIds'] ?? [];
        $dessertIds = $options['dessertIds'] ?? [];
        $builder
 
            ->add('Type', EntityType::class, [
            'class' => Plat::class,
            'choice_label' => 'nom',
            'query_builder' => function ($repository) use ($maincourseIds) {
                return $repository->createQueryBuilder('d')
                ->where('d.id IN (:ids)')
                ->setParameter('ids', $maincourseIds);
                        },])
    
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
            'starterIds' => [], // Ajoutez ceci
            'maincourseIds' => [], // Ajoutez ceci
            'dessertIds' => [], // Ajoutez ceci
        ]);
    }
}
