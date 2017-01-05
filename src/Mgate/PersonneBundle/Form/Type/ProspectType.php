<?php

/*
 * This file is part of the Incipio package.
 *
 * (c) Florian Lefevre
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mgate\PersonneBundle\Form\Type;

use Mgate\PersonneBundle\Entity\Prospect;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nom', 'text')
                ->add('entite', 'choice', array('choices' => Prospect::getEntiteChoice(), 'required' => false))
                ->add('adresse', 'textarea', array('required' => false))
                ->add('codepostal', 'text', array('required' => false, 'attr' => array('placeholder' => 'Code Postal')))
                ->add('ville', 'text', array('required' => false, 'attr' => array('placeholder' => 'Ville')))
                ->add('pays', 'text', array('required' => false, 'attr' => array('placeholder' => 'Pays')));
    }

    public function getName()
    {
        return 'Mgate_suivibundle_prospecttype';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Mgate\PersonneBundle\Entity\Prospect',
        ));
    }
}
