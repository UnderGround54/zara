<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class OrFilterProvider implements ProviderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RequestStack           $requestStack)
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityClass = $context['resource_class'] ?? null;

        if (!$entityClass) {
            throw new \RuntimeException("Impossible de déterminer l'entité.");
        }

        $queryBuilder = $this->entityManager->getRepository($entityClass)->createQueryBuilder('e');

        $filters = $request->query->all();
        $firstCondition = true;

        foreach ($filters as $field => $value) {
            if (property_exists($entityClass, $field)) {
                if ($firstCondition) {
                    $queryBuilder->where('1 = 0'); /* Pour éviter WHERE vide */
                    $firstCondition = false;
                }
                if (is_numeric($value)) {
                    $queryBuilder->orWhere("e.$field = :$field")->setParameter($field, $value);
                } else {
                    $queryBuilder->orWhere("e.$field LIKE :$field")->setParameter($field, "%$value%");
                }
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
