<?php

declare(strict_types = 1);

namespace Tests\unit;
use SocialPost\Dto\SocialPostTo;
use DateTime;
use Statistics\Calculator\AveragePostsPerUserPerMonth;
use Statistics\Builder\ParamsBuilder;
use PHPUnit\Framework\TestCase;


class doAcumulateTest extends TestCase
{
    public function testDoAccumulate()
    {
        $postTo = new SocialPostTo();
        $postTo->setDate(new DateTime('2023-01-18'));
        $postTo->setAuthorId("user_1");

        
        $postTo2 = new SocialPostTo();
        $postTo2->setDate(new DateTime('2023-01-23'));
        $postTo2->setAuthorId("user_1");

        
        $postTo3 = new SocialPostTo();
        $postTo3->setDate(new DateTime('2023-01-29'));
        $postTo3->setAuthorId("user_2");

        $accumulator = new AveragePostsPerUserPerMonth();
        
        $params = ["start_date" => new DateTime("2023-01-01"), "end_date" => new DateTime("2023-04-01")];

        $startDate = $params['start_date'];
        $endDate   = $params['end_date'];
        $params    = ParamsBuilder::reportStatsParams($startDate, $endDate);

        $accumulator->setParameters($params[0]);
        $accumulator->setParameters($params[1]);
        $accumulator->accumulateData($postTo);
        $accumulator->accumulateData($postTo2);
        $accumulator->accumulateData($postTo3);
        
        $stats = $accumulator->calculate();
        $children = $stats->getChildren();
        

        $this->assertEquals(1, count($stats->getChildren()));
        $this->assertEquals('Jan, 2023', $children[0]->getSplitPeriod());
        $this->assertEquals(1.5, $children[0]->getValue());



    }
}