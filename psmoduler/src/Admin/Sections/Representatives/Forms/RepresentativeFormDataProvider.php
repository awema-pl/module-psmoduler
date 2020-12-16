<?php

declare(strict_types=1);
namespace Psmoduler\Admin\Sections\Representatives\Forms;

use Psmoduler\Admin\Sections\Employees\Repositories\EmployeeRepository;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;

class RepresentativeFormDataProvider implements FormDataProviderInterface
{
    /** @var int $idLang */
    private $idLang;

    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var EmployeeRepository $employeeRepository */
    private $employeeRepository;

    /** @var RepresentativeService $representativeService */
    private $representativeService;
    
    public function __construct($idLang, RepresentativeRepository $representativeRepository, EmployeeRepository $employeeRepository, RepresentativeService $representativeService)
    {
        $this->idLang = $this->idLang;
        $this->representativeRepository = $representativeRepository;
        $this->employeeRepository = $employeeRepository;
        $this->representativeService = $representativeService;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($idPrepresentative)
    {
        $representative = $this->representativeRepository->findOneById($idPrepresentative);
        $employee = $this->employeeRepository->find($representative->getIdEmployee());
        $representativeData = [
            'code' => $representative->getCode(),
            'firstname' =>$employee['firstname'],
            'lastname' => $employee['lastname'],
            'email' => $employee['email'],
            'password' => '',
            'id_lang' => (int)$employee['id_lang'],
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
            'firstname' =>'',
            'lastname' => '',
            'email' => '',
            'password' => '',
            'id_lang' =>$this->idLang,
            'phone' => '',
        ];
    }
}
