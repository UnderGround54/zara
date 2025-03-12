<?php

namespace App\Provider;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGenerator;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationDataProvider implements ProviderInterface
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly iterable $collectionExtensions
    )
    {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        $resourceClass = $context['resource_class'] ?? null;
        if (!$resourceClass) {
            throw new \LogicException('Aucune classe de ressource définie.');
        }

        $repository   = $this->entityManager->getRepository($resourceClass);
        $queryBuilder = $repository->createQueryBuilder('e');

        $queryNameGenerator = new QueryNameGenerator();
        foreach ($this->collectionExtensions as $extension) {
            if ($extension instanceof QueryCollectionExtensionInterface) {
                $extension->applyToCollection($queryBuilder, $queryNameGenerator, $resourceClass, $operation, $context);
            }
        }

        $page = max(1, $context['pagination']['page'] ?? 1);
        $pageSize = max(1, $context['pagination']['pageSize'] ?? 10);

        $queryBuilder->setFirstResult(($page - 1) * $pageSize)
            ->setMaxResults($pageSize);

        $paginator  = new Paginator($queryBuilder);
        $totalItems = count($paginator);
        $totalPages = (int) ceil($totalItems / $pageSize);

        return [
            'data' => iterator_to_array($paginator),
            'meta' => [
                'page'       => $page,
                'pageSize'   => $pageSize,
                'totalPages' => $totalPages,
                'totalItems' => $totalItems,
            ],
        ];
    }
}
