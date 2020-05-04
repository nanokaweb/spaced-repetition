<?php

/*
 * This file is part of <package name>.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace NanokaWeb\SpacedRepetition\Tests;

use Carbon\Carbon;
use NanokaWeb\SpacedRepetition\SuperMemo\SM2;
use PHPUnit\Framework\TestCase;

class SM2Test extends TestCase
{
    public function testItShouldScheduleNextRepetitionForTomorrow()
    {
        $sm2 = new SM2();
        $sm2->processRecallResult(4);
        $this->assertFalse($sm2->scheduledToRecall());
        $this->assertTrue($sm2->getNextRepetition()->isTomorrow());
        $this->assertTrue($sm2->getLastStudied()->isToday());
        $this->assertSame(2.5000000000000004, $sm2->getEasinessFactor());
        $this->assertSame(1, $sm2->getNumberRepetitions());
    }

    public function testItShouldScheduleNextRepetitionFor6Days()
    {
        $sm2 = new SM2();
        $sm2->processRecallResult(4);
        $sm2->processRecallResult(4);
        $this->assertFalse($sm2->scheduledToRecall());
        $this->assertTrue($sm2->getNextRepetition()->isSameDay(Carbon::now()->addDays(6)));
        $this->assertTrue($sm2->getLastStudied()->isToday());
        $this->assertSame(2.5000000000000004, $sm2->getEasinessFactor());
        $this->assertSame(2, $sm2->getNumberRepetitions());
    }

    public function testItShouldReportAsScheduledToRecallForToday()
    {
        $sm2 = new SM2();
        $sm2->setNextRepetition(Carbon::now());
        $this->assertTrue($sm2->scheduledToRecall());

        $sm2->setNextRepetition(Carbon::now()->subDay(1));
        $this->assertTrue($sm2->scheduledToRecall());
    }

    public function testItShouldNotBeScheduledToRecall()
    {
        $sm2 = new SM2();
        $sm2->setNextRepetition(null);
        $this->assertFalse($sm2->scheduledToRecall());

        $sm2->setNextRepetition(Carbon::tomorrow());
        $this->assertFalse($sm2->scheduledToRecall());

        $sm2->setNextRepetition(Carbon::now()->addDays(100));
        $this->assertFalse($sm2->scheduledToRecall());
    }

    public function testItRequireRepeatingItemsThatScored3()
    {
        $sm2 = new SM2();
        $sm2->processRecallResult(3);
        $this->assertTrue($sm2->getNextRepetition()->isToday());

        $sm2->processRecallResult(3);
        $this->assertTrue($sm2->getNextRepetition()->isToday());

        $sm2->processRecallResult(4);
        $this->assertTrue($sm2->getNextRepetition()->isTomorrow());
    }
}
