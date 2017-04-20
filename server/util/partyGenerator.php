<?php
function GenerateArmy($units, $partyHP, $partyAvgDamage, $options)
{
    global $party, $rangeAbove, $rangeBelow, $options, $maxUnitTypes;

    $i = -1;
    $removedFirst = false;
    $force = [];

    $maxIterations = 1000;
    $iterations = 0;

    $units = FilterStrongAndWeakFromParty($units, $party);

    $maxUnitTypes = count($units);

    $avgUnitDamage = GetPartyDamageAverage($units);
    $weakestUnit = GetLowestDamageMember($units);

    while(GetPartyHP($force) < $partyHP && $iterations <= $maxIterations)
    {
        $iterations++;
        $dmg = GetPartyDamageAverage($force);
        
        $maxTries = 100;

        if ($dmg * 1.1 < $partyAvgDamage)
        {
            if (!$options->swarmMode) 
            {
                usort($units, "CompareUnitSwarm");
            }
            else 
            {
                usort($units, "CompareUnit");
            }

            $added = false;
            $tries = 0;

            while (!$added && $tries <= $maxTries)
            {
                $tries++;

                $unitIndex = array_rand($units); //rand(0, count($units) - 1); 

                if ($weakestUnit != null && $weakestUnit->avgDamage > ($partyAvgDamage * 1.5))
                {
                    array_push($force, $weakestUnit);
                    $added = true;
                    break;
                }
                else if ($units[$unitIndex]->avgDamage >= $avgUnitDamage && GetPartyHP($force) + $units[$unitIndex]->hp < $partyHP * 1.1)
                {
                    array_push($force, $units[$unitIndex]);
                    $added = true;
                    break;
                }
            }
        }
        else
        {
            if ($options->swarmMode) 
            {
                usort($units, "CompareUnitSwarm");
            }
            else 
            {
                usort($units, "CompareUnit");
            }

            $added = false;
            $tries = 0;

            while (!$added && $tries <= $maxTries)
            {
                $tries++;

                $unitIndex = array_rand($units); //rand(0, count($units) - 1); 

                if ($weakestUnit->avgDamage > ($partyAvgDamage * 1.5))
                {
                    array_push($force, $weakestUnit);
                    $added = true;
                    break;
                }
                else if ($units[$unitIndex]->avgDamage < ($partyAvgDamage * 1.5) && GetPartyHP($force) + $units[$unitIndex]->hp < $partyHP * 1.1)
                {
                    array_push($force, $units[$unitIndex]);
                    $added = true;
                    break;
                }
            }
        }
    }

    return $force;
}

function FilterStrongAndWeakFromParty($party, $opposition)
{
    global $removedUnits, $options;

    $avgDamage = GetPartyDamageAverage($opposition);

    $result = [];

    $rangeAbove = $options->attackPowerUpperRangePercentage;
    $rangeBelow = $options->attackPowerLowerRangePercentage;

    $rangeBelow = max($rangeBelow, 0);
    $rangeAbove = max($rangeAbove, 0);

    $maxDamage = $avgDamage * $rangeAbove;
    $minDamage = $avgDamage * $rangeBelow;

    $options->limits = new stdClass();

    $options->limits->maxDamage = $maxDamage;
    $options->limits->minDamage = $minDamage;

    $options->limits->percentages = new stdClass();

    $options->limits->percentages->min = $rangeBelow;
    $options->limits->percentages->max = $rangeAbove;

    for ($i = 0; $i < count($party); $i++)
    {
        $tooStrong = $party[$i]->avgDamage > $maxDamage;

        $tooWeak = $party[$i]->avgDamage < $minDamage;

        if ($tooStrong || $tooWeak)
        {
            array_push($result, $party[$i]);

            $party[$i]->removedReason = $tooStrong? "Too Strong" : "Too Weak";
        }
    }

    for ($i = 0; $i < count($result); $i++)
    {
        $index = array_search($result[$i], $party, true);
        array_push($removedUnits, $result[$i]);
        array_splice($party, $index, 1);
    }

    return $party;
}

function GetLowestDamageMember($party)
{
    $dmg = INF;
    $result = null;
    global $lowestDamageUnit;
    global $lowestDamageSet;

    if ($lowestDamageSet && isset($lowestDamageUnit) && $lowestDamageUnit != null)
    {
        return $lowestDamageUnit;
    }
    else
    {
        for ($i = 0; $i < count($party); $i++)
        {
            if ($party[$i]->avgDamage < $dmg)
            {
                $dmg = $party[$i]->avgDamage;
                $result = $party[$i];
            }
        }

        $lowestDamageSet = true;
        $lowestDamageUnit = $result;
        return $result;
    }
}

function CompareUnit($a, $b)
{
    global $partyAvgDamage;

    if ($a->hp == $b->hp)
    {
        return abs($b->avgDamage - $partyAvgDamage) - abs($a->avgDamage - $partyAvgDamage);
    }
    else
    {
        return $b->hp - $a->hp;
    }
}

function CompareUnitSwarm($a, $b)
{
    global $partyAvgDamage;

    if ($a->hp == $b->hp)
    {
        return abs($b->avgDamage - $partyAvgDamage) - abs($a->avgDamage - $partyAvgDamage);
    }
    else
    {
        return $a->hp - $b->hp;
    }
}