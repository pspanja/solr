<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\Solr\Search\Query\Common\AggregationVisitor;

use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation;
use Ibexa\Contracts\Core\Repository\Values\Content\Query\Aggregation\UserMetadataTermAggregation;
use Ibexa\Contracts\Solr\Query\AggregationVisitor;
use Ibexa\Solr\Query\Common\AggregationVisitor\UserMetadataTermAggregationVisitor;

final class UserMetadataTermAggregationVisitorTest extends AbstractAggregationVisitorTest
{
    protected function createVisitor(): AggregationVisitor
    {
        return new UserMetadataTermAggregationVisitor();
    }

    public function dataProviderForCanVisit(): iterable
    {
        yield 'true' => [
            new UserMetadataTermAggregation('foo', UserMetadataTermAggregation::OWNER),
            self::EXAMPLE_LANGUAGE_FILTER,
            true,
        ];

        yield 'false' => [
            $this->createMock(Aggregation::class),
            self::EXAMPLE_LANGUAGE_FILTER,
            false,
        ];
    }

    public function dataProviderForVisit(): iterable
    {
        yield UserMetadataTermAggregation::OWNER => [
            new UserMetadataTermAggregation('foo', UserMetadataTermAggregation::OWNER),
            self::EXAMPLE_LANGUAGE_FILTER,
            [
                'type' => 'terms',
                'field' => 'content_owner_user_id_id',
                'limit' => UserMetadataTermAggregation::DEFAULT_LIMIT,
                'mincount' => UserMetadataTermAggregation::DEFAULT_MIN_COUNT,
            ],
        ];

        yield UserMetadataTermAggregation::MODIFIER => [
            new UserMetadataTermAggregation('foo', UserMetadataTermAggregation::MODIFIER),
            self::EXAMPLE_LANGUAGE_FILTER,
            [
                'type' => 'terms',
                'field' => 'content_version_creator_user_id_id',
                'limit' => UserMetadataTermAggregation::DEFAULT_LIMIT,
                'mincount' => UserMetadataTermAggregation::DEFAULT_MIN_COUNT,
            ],
        ];

        yield UserMetadataTermAggregation::GROUP => [
            new UserMetadataTermAggregation('foo', UserMetadataTermAggregation::GROUP),
            self::EXAMPLE_LANGUAGE_FILTER,
            [
                'type' => 'terms',
                'field' => 'content_owner_user_group_ids_mid',
                'limit' => UserMetadataTermAggregation::DEFAULT_LIMIT,
                'mincount' => UserMetadataTermAggregation::DEFAULT_MIN_COUNT,
            ],
        ];
    }
}

class_alias(UserMetadataTermAggregationVisitorTest::class, 'EzSystems\EzPlatformSolrSearchEngine\Tests\Search\Query\Common\AggregationVisitor\UserMetadataTermAggregationVisitorTest');
