<?php

declare(strict_types=1);
namespace Psmoduler\Admin\Sections\Representatives\Forms;

use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;

class AssignRepresentativeFormDataProvider implements FormDataProviderInterface
{
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var RepresentativeService $representativeService */
    private $representativeService;
    
    public function __construct(RepresentativeRepository $representativeRepository, RepresentativeService $representativeService)
    {
        $this->representativeRepository = $representativeRepository;
        $this->representativeService = $representativeService;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($idPrepresentative)
    {
        /** @var PsmodulerRepresentative $representative */
        $representative = $this->representativeRepository->findOneById($idPrepresentative);
        $representativeData = [
            'id_employee' => $representative->getIdEmployee(),
            'code' => $representative->getCode(),
            'phone' => $representative->getPhone(),
        ];
        return $representativeData;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultData()
    {
        return [
            'code' => $this->representativeService->generateCode(),
            'phone' => '',
        ];
    }
}
