<?php


namespace App\Form;

use App\Entity\Firm;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FirmType extends AbstractType
{
    public function buildForm ( FormBuilderInterface $builder, array $options )
    {
        $builder
            ->add('ico', TextType::class, [
                'label' => 'Otestujte společnost',
                'attr' => array(
                    'placeholder' => 'IČO společnosti...'
                )
            ]);
    }

    public function configureOptions ( OptionsResolver $resolver )
    {
        $resolver->setDefaults([
            'data_class' => Firm::class
        ]);
    }
}
