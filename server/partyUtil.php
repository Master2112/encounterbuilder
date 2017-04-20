<?php

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

function GetGroupFromParty($party, $groupNum)
{
    $group = [];

    for ($i = 0; $i < count($party); $i++)
    {
        if ($party[$i]->group == $groupNum)
            array_push($group, $party[$i]);
    }

    return $group;
}