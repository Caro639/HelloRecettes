<?php

namespace App\Api;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\QueryBuilder;

class FilterPublishedRecipeQueryExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(
        QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator,
        string $recipeList, Operation $operation = null, array $context = []
    ): void {
        if (Recipe::class === $recipeList) {
            $queryBuilder->andWhere(sprintf("%s.state = 'isPublic = 1'", $queryBuilder->getRootAliases()[0]));
        }
    }

    public function applyToItem(
        QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator,
        string $recipeList, array $identifiers, Operation $operation = null, array $context = []
    ): void {
        if (Recipe::class === $recipeList) {
            $queryBuilder->andWhere(sprintf("%s.state = 'isPublic = 1'", $queryBuilder->getRootAliases()[0]));
        }
    }
}