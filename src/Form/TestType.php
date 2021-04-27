<?php


namespace App\Form;

use App\Entity\Firm;

use App\Entity\TestJednatelu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('test_jednatelu', ChoiceType::class, array(
                'choices' => array(
                    'Počet jednatelů' => 'pocet_jednatelu',
                //     'Jine subjekty' => 'jine_subjekty',
                //     'Insolvence' => 'insolvence',
                    'Netypický věk jednatelů' => 'atypycky_vek',
                //     'Bydliste jednatelu' => 'bydliste'
                ),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Test jednatelů:',
                'choice_attr' => [
                    'Počet jednatelů' => ['text' => 'Chcete zahrnout kontrolu počtu vlastníků do testování?'],
                    'Netypický věk jednatelů' => ['text' => 'Chcete do testování zahrnout ověření vlastníků pro nestandardní věk?'],
                ],

            ))
            ->add('test_subjektu', ChoiceType::class, array(
                'choices' => array(
                 //   'Pocet zamestnancu' => 'pocet_zamestnancu',
                 //   'Pocet let na trhu' => 'pocet_let_na_trhu',
                 //   'Nespolehlivy platce' => 'dph',
                    'Jiné subjekty na sídle' => 'jine_subjekty_na_sidle'
                ),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Test subjektu:',
                'choice_attr' => [
                    'Jiné subjekty na sídle' => ['text' => 'Zahrnout do testování položky kontrolu počtu dalších subjektů na této adrese?'],
                ],


            ))
            ->add('test_domeny', ChoiceType::class, array(
                'choices' => array(
                //    'Existnce' => 'existence',
                //    'Pocet let v provozu' => 'pocet_let_v_provozu',
                    'Poslední modifikace' => 'posledni_modifikace'
                ),
                'expanded' => true,
                'multiple' => true,
                'label' => 'Test domény:',
                'choice_attr' => [
                    'Poslední modifikace' => ['text' => 'Zahrnout do testování položky kontrolu data poslední úpravy domény?'],
                ],

            ))
            ->add('test_bonusovy', ChoiceType::class, array(
                'choices' => array(
                    'Počet provozoven' => 'pocet_provozoven',
                    'Aktuální nabídky práce' => 'aktualni_nabidky_prace',
                    'Ochranné známky' => 'oz',
                    'Růst základního kapitála' => 'rust_zakladniho_kapitala'
                ),
                'choice_attr' => [
                    'Počet provozoven' => ['text' => 'Zahrnout do testování vyhledávací položku pro počet provozoven?'],
                    'Aktuální nabídky práce' => ['text' => 'Zahrnout do testování kontrolu volných míst? '],
                    'Ochranné známky' => ['text' => 'Zahrnout do testování kontrolu ochranné známky? '],
                    'Růst základního kapitála' => ['text' => 'Zahrnout do testování kontrolu zvýšení základního kapitálu po celou dobu práce firmy?'],
                ],
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'label' => 'Test bonusovy:',
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}