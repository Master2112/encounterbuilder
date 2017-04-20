<?php

function SetUnitGroup($party, $groupAmount)
{
    $changed = true;

    for ($i = 0; $i < $groupAmount; $i++)
    {
        $set = false;

        while (!$set)
        {
            $pI = rand(0, count($party) - 1);
            
            if (isset($party[$pI]) && !isset ($party[$pI]->group))
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