<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");

$json = file_get_contents("php://input");

$data = json_decode($json);

$options = $data->options;
$party = $data->party;
$unitTypes = $data->availableUnits;

$partyHP = GetPartyHP($party);
$partyAvgDamage = GetPartyDamageAverage($party);
$partyAvgRange = GetPartyRangeAverage($party);
SetUnitDamageAverage($unitTypes);
GetPartyRangeAverage($unitTypes);

$result = new stdClass();

$result->data = new stdClass();
$result->data->partyHP = $partyHP;
$result->data->partyAvgDamage = $partyAvgDamage;

$result->generatedForce = GenerateArmy($unitTypes, $partyHP, $partyAvgDamage, $options);

$result->data->generatedForceHP = GetPartyHP($result->generatedForce);
$result->data->generatedForceAvgDamage = GetPartyDamageAverage($result->generatedForce);
$result->data->generatedForceAvgRange = GetPartyRangeAverage($result->generatedForce);

if (isset($options->groups))
    SetUnitGroup($result->generatedForce, $options->groups);

$result->data->input = $options;

echo json_encode($result);

function GenerateArmy($units, $partyHP, $partyAvgDamage, $options)
{
    $i = -1;
    $removedFirst = false;
    $force = [];

    $maxIterations = 1000;
    $iterations = 0;

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

                if ($units[$unitIndex]->avgDamage < $partyAvgDamage && GetPartyHP($force) + $units[$unitIndex]->hp < $partyHP * 1.1)
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

                if ($units[$unitIndex]->avgDamage > $partyAvgDamage && GetPartyHP($force) + $units[$unitIndex]->hp < $partyHP * 1.1)
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

function GetPartyHP($party)
{
    $hp = 0;

    for($i = 0; $i < count($party); $i++)
        $hp += $party[$i]->hp;

    return $hp;
}

function GetPartyDamageAverage($party)
{
    $dmg = 0;
    $i = 0;

    for($i = 0; $i < count($party); $i++)
    {
        $memberAvg = 0;
        $e = 0;

        for($e = 0; $e < count($party[$i]->equipment); $e++)
        {
            if (isset($party[$i]->equipment[$e]->damage))
                $memberAvg += $party[$i]->equipment[$e]->damage;
        }

        $party[$i]->avgDamage = $memberAvg / $e;

        $dmg += $party[$i]->avgDamage;
    }
    if ($i == 0)
        return 0;
    
    return $dmg / $i;
}

function GetPartyRangeAverage($party)
{
    $range = 0;
    $i = 0;

    for($i = 0; $i < count($party); $i++)
    {
        $party[$i]->range = new stdClass();
        $memberAvg = 0;
        $e = 0;

        $party[$i]->range->min = -1;
        $party[$i]->range->max = 0;
        $party[$i]->range->average = 0;

        for($e = 0; $e < count($party[$i]->equipment); $e++)
        {
            if (isset($party[$i]->equipment[$e]->range))
            {
                $memberAvg += $party[$i]->equipment[$e]->range;

                if ($party[$i]->range->min > $party[$i]->equipment[$e]->range || $party[$i]->range->min == -1)
                    $party[$i]->range->min = $party[$i]->equipment[$e]->range;

                if ($party[$i]->range->max < $party[$i]->equipment[$e]->range)
                    $party[$i]->range->max = $party[$i]->equipment[$e]->range;
            }
        }
        
        $party[$i]->range->average = $memberAvg / $e;

        $range += $party[$i]->range->average;
    }
    if ($i == 0)
        return 0;
    
    return $range / $i;
}

function SetUnitGroup($party, $groupAmount)
{
    $changed = true;

    for ($i = 0; $i < $groupAmount; $i++)
    {
        $set = false;

        while (!$set)
        {
            $pI = rand(0, count($party) - 1);
            
            if (!isset ($party[$pI]->group))
            {
                $party[$pI]->group = $i;
                $set = true;
            }
        }
    }

    while ($changed)
    {
        $changed = false;
        $groups = [];

        for ($i = 0; $i < $groupAmount; $i++)
        {
            $group = new stdClass();
            $group->id = $i;
            $group->positions = [];

            array_push($groups, $group);
        }

        for($i = 0; $i < count($party); $i++)
        {
            $pos = GetUnitPos($party[$i]);

            if (isset($party[$i]->group))
            {
                array_push($groups[$party[$i]->group]->positions, $pos);
            }
        }

        for ($i = 0; $i < $groupAmount; $i++)
        {
            $groups[$i]->center = [];

            for ($n = 0; $n < count($groups[$i]->positions[0]); $n++)
            {
                $groups[$i]->center[$n] =  0;
            }

            for ($p = 0; $p < count($groups[$i]->positions); $p++)
            {
                for ($n = 0; $n < count($groups[$i]->positions[$p]); $n++)
                {
                    $groups[$i]->center[$n] += $groups[$i]->positions[$p][$n];
                }
            }

            for ($n = 0; $n < count($groups[$i]->positions[0]); $n++)
            {
                $groups[$i]->center[$n] /= count($groups[$i]->positions);
            }
        }

        for($i = 0; $i < count($party); $i++)
        {
            $pos = GetUnitPos($party[$i]);
            $oldGroup = -1;

            if (isset($party[$i]->group))
            {
                $oldGroup = $party[$i]->group;
            }

            $currDist = INF;

            for ($g = 0; $g < count($groups); $g++)
            {
                $dist = nDistance($groups[$g]->center, $pos);

                if ($currDist > $dist)
                {
                    $currDist = $dist;
                    $party[$i]->group = $groups[$g]->id;
                }
            }

            if ($party[$i]->group != $oldGroup)
            {
                $changed = true;
            }
        }
    }
}

function GetUnitPos($unit)
{
    return  [
                $unit->hp,
                $unit->avgDamage,
                $unit->range->average
            ];
}

/*
    nDistance(float[], float[])
    Calculates the euclidian distance in n-dimensional space.
    Ported from an answer on http://stackoverflow.com/questions/23353977/calculate-euclidean-distance-between-4-dimensional-vectors
    by D.R.Bendanillo http://stackoverflow.com/users/4681630/d-r-bendanillo
*/
function nDistance($a, $b) 
{
    $total = 0;

    for ($i = 0; $i < count($a); $i++) 
    {
        $diff = (float)$b[$i] - (float)$a[$i];
        $total += $diff * $diff;
    }

    return (float)sqrt($total);
}

function SetUnitDamageAverage($party)
{
    for($i = 0; $i < count($party); $i++)
    {
        $memberAvg = 0;
        $e = 0;

        for($e = 0; $e < count($party[$i]->equipment); $e++)
        {
            if (isset($party[$i]->equipment[$e]->damage))
                $memberAvg += $party[$i]->equipment[$e]->damage;
        }

        $party[$i]->avgDamage = $memberAvg / $e;
    }
}