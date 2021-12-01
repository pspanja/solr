<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Solr\Query\Content\CriterionVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Criterion\Operator;
use Ibexa\Contracts\Solr\Query\CriterionVisitor;
use Ibexa\Core\Repository\Values\Content\Query\Criterion\PermissionSubtree;

/**
 * Visits the Subtree criterion.
 */
class SubtreeIn extends CriterionVisitor
{
    /**
     * CHeck if visitor is applicable to current criterion.
     *
     * @return bool
     */
    public function canVisit(Criterion $criterion)
    {
        return
            ($criterion instanceof Criterion\Subtree || $criterion instanceof PermissionSubtree) &&
            (($criterion->operator ?: Operator::IN) === Operator::IN ||
              $criterion->operator === Operator::EQ);
    }

    /**
     * Map field value to a proper Solr representation.
     *
     * @param \Ibexa\Contracts\Solr\Query\CriterionVisitor $subVisitor
     *
     * @return string
     */
    public function visit(Criterion $criterion, CriterionVisitor $subVisitor = null)
    {
        return '(' .
            implode(
                ' OR ',
                array_map(
                    static function ($value) {
                        return 'location_path_string_mid:' . str_replace('/', '\\/', $value) . '*';
                    },
                    $criterion->value
                )
            ) .
            ')';
    }
}

class_alias(SubtreeIn::class, 'EzSystems\EzPlatformSolrSearchEngine\Query\Content\CriterionVisitor\SubtreeIn');
