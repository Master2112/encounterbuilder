<?php

function SetPartyTargets($party, $opposition, $groups)
{
    $allHaveRoles = false;
    $tries = 0;

    while (!$allHaveRoles && $tries < 100)
    {
        $allHaveRoles = true;
        $tries++;

        SetRangedGroup($groups);
        SetTankGroup($groups);
        SetDpsGroup($groups);

        for ($i = 0; $i < count($groups); $i++)
        {
            if (!isset($groups[$i]->role))
                $allHaveRoles = false;
        }
    }

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
            $groups[$i]->role = "None";
    }

    $allHaveRoles = false;
    $tries = 0;

    while (!$allHaveRoles && $tries < 100)
    {
        $allHaveRoles = true;
        $tries++;

        SetRangedUnit($opposition);
        SetTankUnit($opposition);
        SetDpsUnit($opposition);

        for ($i = 0; $i < count($opposition); $i++)
        {
            if (!isset($opposition[$i]->role))
                $allHaveRoles = false;
        }
    }

    for ($i = 0; $i < count($opposition); $i++)
    {
        if (!isset($opposition[$i]->role))
            $opposition[$i]->role = "None";
    }
}

// Units

function SetRangedUnit($units)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->range->max > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->range->max;
            }

            if ($units[$i]->range->max < $worstValue)
                $worstValue = $units[$i]->range->max;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Ranged";
}

function SetDpsUnit($units)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->avgDamage > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->avgDamage;
            }

            if ($units[$i]->avgDamage < $worstValue)
                $worstValue = $units[$i]->avgDamage;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Dps";
}

function SetTankUnit($units)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->hp > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->hp;
            }

            if ($units[$i]->hp < $worstValue)
                $worstValue = $units[$i]->hp;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Tank";
}

// Groups

function SetRangedGroup($groups)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->avgRange > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->avgRange;
            }

            if ($groups[$i]->avgRange < $worstValue)
                $worstValue = $groups[$i]->avgRange;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Ranged";
}

function SetDpsGroup($groups)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->avgDamage > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->avgDamage;
            }

            if ($groups[$i]->avgDamage < $worstValue)
                $worstValue = $groups[$i]->avgDamage;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Dps";
}

function SetTankGroup($groups)
{
    $best = null;
    $bestValue = 0;
    $worstValue = INF;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->HP > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->HP;
            }

            if ($groups[$i]->HP < $worstValue)
                $worstValue = $groups[$i]->HP;
        }
    }

    if ($best != null && $bestValue > $worstValue)
        $best->role = "Tank";
}