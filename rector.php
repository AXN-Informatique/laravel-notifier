<?php

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector;
use RectorLaravel\Rector\MethodCall\EloquentWhereRelationTypeHintingParameterRector;
use RectorLaravel\Rector\MethodCall\EloquentWhereTypeHintClosureParameterRector;
use RectorLaravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    // register paths
    //----------------------------------------------------------
    $rectorConfig->paths([
        __DIR__.'/src',
    ]);

    $rectorConfig->parallel(
        processTimeout: 360,
        maxNumberOfProcess: 16,
        jobSize: 20
    );

    $rectorConfig->importNames();

    // cache settings
    //----------------------------------------------------------

    // Ensure file system caching is used instead of in-memory.
    $rectorConfig->cacheClass(FileCacheStorage::class);

    // Specify a path that works locally as well as on CI job runners.
    $rectorConfig->cacheDirectory(__DIR__.'/.rector_cache');

    // skip paths and/or rules
    //----------------------------------------------------------
    $rectorConfig->skip([
        // Rector transforme $foo++ en ++$foo et derrière Pint transforme ++$foo en $foo++
        // du coup je désactive, laissant pour le moment la priorité à Pint
        // @todo : voir qu'est-ce qui est le mieux
        PostIncDecToPreIncDecRector::class,

        // Transforme des faux-positifs, je préfère désactiver ça (PHP 8.1)
        NullToStrictStringFuncCallArgRector::class,

        // Je trouve la lecture plus difficile avec cette syntaxe, donc je désactive
        ArraySpreadInsteadOfArrayMergeRector::class,

        // Ne pas changer les closure et Arrow Function en Static
        StaticClosureRector::class,
        StaticArrowFunctionRector::class,

        // Particularités des classes mixins
        CompleteDynamicPropertiesRector::class => [__DIR__.'/src/Eloquent/Mixins'],
        RemoveExtraParametersRector::class => [__DIR__.'/src/Eloquent/Mixins'],
    ]);

    $rectorConfig->rules([
        EloquentWhereRelationTypeHintingParameterRector::class,
        EloquentWhereTypeHintClosureParameterRector::class,
        OptionalToNullsafeOperatorRector::class,
        RemoveDumpDataDeadCodeRector::class,
    ]);

    $rectorConfig->sets([
        LaravelSetList::LARAVEL_FACADE_ALIASES_TO_FULL_NAMES,
        SetList::PHP_82,
        SetList::DEAD_CODE,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
        //SetList::NAMING,
        SetList::TYPE_DECLARATION,
        //SetList::PRIVATIZATION,
        SetList::EARLY_RETURN,
        SetList::INSTANCEOF,
    ]);
};
