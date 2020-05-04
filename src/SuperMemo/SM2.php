<?php

/*
 * This file is part of <package name>.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace NanokaWeb\SpacedRepetition\SuperMemo;

use Carbon\Carbon;

class SM2
{
    /**
     * @var float the easiness factor (E-Factor) used in last calculation
     */
    protected $easinessFactor = 2.5;

    /**
     * @var int the current repetition counter
     */
    protected $numberRepetitions = 0;

    protected $repetitionInterval = null;

    protected $nextRepetition = null;

    protected $lastStudied = null;

    public function __construct()
    {
    }

    public function processRecallResult($qualityOfRecall)
    {
        if ($qualityOfRecall > 5 || $qualityOfRecall < 0) {
            throw new \Exception($qualityOfRecall.' must be a number between 0 and 5');
        }
        if ($qualityOfRecall < 3) {
            $this->numberRepetitions = 0;
            $this->repetitionInterval = 0;
        } else {
            $this->easinessFactor = self::calculateEasinessFactor(
                $this->easinessFactor,
                $qualityOfRecall
            );
            if (3 == $qualityOfRecall) {
                $this->repetitionInterval = 0;
            } else {
                ++$this->numberRepetitions;
                switch ($this->numberRepetitions) {
                    case 1:
                        $this->repetitionInterval = 1;
                        break;
                    case 2:
                        $this->repetitionInterval = 6;
                        break;
                    default:
                        $this->repetitionInterval = $this->repetitionInterval * $this->easinessFactor;
                        break;
                }
            }
        }
        $this->nextRepetition = Carbon::now()->addDays($this->repetitionInterval);
        $this->lastStudied = Carbon::now();
    }

    public function scheduledToRecall()
    {
        return null !== $this->nextRepetition && $this->nextRepetition <= Carbon::now();
    }

    protected function calculateEasinessFactor($easinessFactor, $qualityOfRecall)
    {
        $q = $qualityOfRecall;
        $efOld = $easinessFactor;
        $ef = $efOld - 0.8 + (0.28 * $q) - (0.02 * $q * $q);

        return $ef < 1.3 ? 1.3 : $ef;
    }

    public function getRepetitionInterval()
    {
        return $this->repetitionInterval;
    }

    /**
     * @return Carbon
     */
    public function getNextRepetition()
    {
        return $this->nextRepetition;
    }

    public function getEasinessFactor()
    {
        return $this->easinessFactor;
    }

    public function getNumberRepetitions()
    {
        return $this->numberRepetitions;
    }

    /**
     * @return Carbon
     */
    public function getLastStudied()
    {
        return $this->lastStudied;
    }

    public function setEasinessFactor($easinessFactor)
    {
        $this->easinessFactor = $easinessFactor;
    }

    public function setNumberRepetitions($numberRepetitions)
    {
        $this->numberRepetitions = $numberRepetitions;
    }

    public function setRepetitionInterval($repetitionInterval)
    {
        $this->repetitionInterval = $repetitionInterval;
    }

    public function setNextRepetition($nextRepetition)
    {
        $this->nextRepetition = $nextRepetition;
    }

    public function setLastStudied($lastStudied)
    {
        $this->lastStudied = $lastStudied;
    }
}
