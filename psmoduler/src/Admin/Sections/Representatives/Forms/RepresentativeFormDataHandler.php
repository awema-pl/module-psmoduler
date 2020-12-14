<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Forms;

use Doctrine\DBAL\Exception\NonUniqueFieldNameException;
use Doctrine\ORM\EntityManagerInterface;
use Psmoduler\Exceptions\PsmodulerException;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService;
use Psmoduler\Entity\Admin\Sections\Representatives\PsmodulerRepresentative;
use PrestaShop\Module\DemoDoctrine\Entity\QuoteLang;
use Psmoduler\Admin\Sections\Representatives\Repositories\RepresentativeRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;

class RepresentativeFormDataHandler implements FormDataHandlerInterface
{
    /** @var RepresentativeRepository $representativeRepository */
    private $representativeRepository;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    public function __construct(RepresentativeRepository $representativeRepository, EntityManagerInterface $entityManager) {
        $this->representativeRepository = $representativeRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $data)
    {
        $representative = new PsmodulerRepresentative();
        $representative->setCode($data['code']);
        $representative->setPhone($data['phone']);
        $this->entityManager->persist($representative);
        $this->entityManager->flush();

        return $representative->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function update($id, array $data)
    {
        $representative = $this->representativeRepository->findOneById($id);
        $representative->setCode($data['code']);
        $representative->setPhone($data['phone']);
        $this->entityManager->flush();
        return $representative->getId();
    }
}
