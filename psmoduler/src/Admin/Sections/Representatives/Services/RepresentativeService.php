<?php

namespace Psmoduler\Admin\Sections\Representatives\Services;

use Doctrine\ORM\EntityManagerInterface;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataHandler\FormDataHandlerInterface;
use PrestaShopBundle\Entity\Repository\LangRepository;
use Psmoduler\Admin\Sections\Representatives\Services\Contracts\RepresentativeService as RepresentativeServiceContract;

class RepresentativeService implements RepresentativeServiceContract
{
    /** @var LangRepository $langRepository */
    private $langRepository;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * @param LangRepository $langRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(LangRepository $langRepository, EntityManagerInterface $entityManager) {
        $this->langRepository = $langRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Generate code
     *
     * @return string
     */
    public function generateCode(){
        $newCode = substr(str_shuffle(str_repeat("123456789ABCDEFGHIJKLMNPRSTUWYZ", 6)), 0, 6);
        //$this->entityManager->fi
    }
}
