<?php

function SetPartyTargets($party, $opposition, $groups)
{
    $allHaveRoles = false;

    while (!$allHaveRoles)
    {
        $allHaveRoles = true;

        SetRangedGroup($groups);
        SetTankGroup($groups);
        SetDpsGroup($groups);

        for ($i = 0; $i < count($groups); $i++)
        {
            if (!isset($groups[$i]->role))
                $allHaveRoles = false;
        }
    }

    $allHaveRoles = false;

    while (!$allHaveRoles)
    {
        $allHaveRoles = true;

        SetRangedUnit($opposition);
        SetTankUnit($opposition);
        SetDpsUnit($opposition);

        for ($i = 0; $i < count($opposition); $i++)
        {
            if (!isset($opposition[$i]->role))
                $allHaveRoles = false;
        }
    }
}

// Units

function SetRangedUnit($units)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->range->max > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->range->max;
            }
        }
    }

    if ($best != null)
        $best->role = "Ranged";
}

function SetDpsUnit($units)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->avgDamage > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->avgDamage;
            }
        }
    }

    if ($best != null)
        $best->role = "Dps";
}

function SetTankUnit($units)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($units); $i++)
    {
        if (!isset($units[$i]->role))
        {
            if ($units[$i]->hp > $bestValue)
            {
                $best = $units[$i];
                $bestValue = $units[$i]->hp;
            }
        }
    }

    if ($best != null)
        $best->role = "Tank";
}

// Groups

function SetRangedGroup($groups)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->avgRange > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->avgRange;
            }
        }
    }

    if ($best != null)
        $best->role = "Ranged";
}

function SetDpsGroup($groups)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->avgDamage > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->avgDamage;
            }
        }
    }

    if ($best != null)
        $best->role = "Dps";
}

function SetTankGroup($groups)
{
    $best = null;
    $bestValue = 0;

    for ($i = 0; $i < count($groups); $i++)
    {
        if (!isset($groups[$i]->role))
        {
            if ($groups[$i]->HP > $bestValue)
            {
                $best = $groups[$i];
                $bestValue = $groups[$i]->HP;
            }
        }
    }

    if ($best != null)
        $best->role = "Tank";
}