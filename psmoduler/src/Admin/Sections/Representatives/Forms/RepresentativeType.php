<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use PrestaShopBundle\Service\Routing\Router;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use Psmoduler\Exceptions\PsmodulerException;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\CallbackValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Context;

class RepresentativeType extends TranslatorAwareType
{
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var RequestStack $requestStack */
    private $requestStack;
    
    public function __construct(TranslatorInterface $translator, array $locales, RepresentativeRepository $representativeRepository,RequestStack $requestStack )
    {
        parent::__construct($translator, $locales);
        $this->representativeRepository = $representativeRepository;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('code', TextType::class, [
                'label' => 'Representative code',
                'help' => 'Representative code (e.g. KR0001).',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => 255]
                        ),
                    ]),
                    new NotBlank(),
                    new Callback(function ($fieldValue, ExecutionContextInterface $context, $payload) use($options) {
                        $idRepresentative = (int)$this->requestStack->getCurrentRequest()->get('idRepresentative');
                        $representative = $this->representativeRepository->findOneByCode($fieldValue);
                        if ((!$idRepresentative && $representative) || ($idRepresentative && $representative && $idRepresentative !== $representative->getId())){
                            $context->buildViolation($this->trans('This representative code already exists.', 'Modules.Psmoduler.Admin'))->atPath('code')->addViolation();
                        }
                    }),
                ]
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'translation_domain' => 'Modules.Psmoduler.Admin',
                'required' =>false,
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => $this->trans(
                            'This field cannot be longer than %limit% characters',
                            'Admin.Notifications.Error',
                            ['%limit%' => 255]
                        ),
                    ]),
                ]
            ]);
    }
}
