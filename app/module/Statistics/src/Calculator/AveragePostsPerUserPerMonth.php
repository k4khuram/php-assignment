<?php

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

/**
 * Class TotalPosts
 *
 * @package Statistics\Calculator
 */
class AveragePostsPerUserPerMonth extends AbstractCalculator
{

    protected const UNITS = 'posts';

    /**
     * @var array
     */
    private $totals = array();

    /**
     * @param SocialPostTo $postTo
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $monthKey = $postTo->getDate()->format('M, Y');
        $authorIdKey = $postTo->getAuthorId();
        $this->totals[$monthKey][$authorIdKey] = ($this->totals[$monthKey][$authorIdKey] ?? 0) + 1;
    }

    /**
     * @return StatisticsTo
     */
    protected function doCalculate(): StatisticsTo
    {

        $stats = new StatisticsTo();

        foreach ($this->totals as $month=>$arr){


           $totalPostsPerMonth = 0;
           $totalUsersPerMonth = count($arr);

            foreach($arr as $key => $value ){

                $totalPostsPerMonth += $value;
               
            }

            $avgPostsPerUser = round($totalPostsPerMonth / $totalUsersPerMonth,2);

          
            $child = (new StatisticsTo())
            ->setName($this->parameters->getStatName())
            ->setSplitPeriod($month)
            ->setValue($avgPostsPerUser)
            ->setUnits(self::UNITS);

             $stats->addChild($child);

        }


        return $stats;
    }
}
