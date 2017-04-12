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
SetUnitDamageAverage($unitTypes);

$result = new stdClass();

$result->data = new stdClass();
$result->data->partyHP = $partyHP;
$result->data->partyAvgDamage = $partyAvgDamage;

$result->generatedForce = GenerateArmy($unitTypes, $partyHP, $partyAvgDamage, $options);

$result->data->generatedForceHP = GetPartyHP($result->generatedForce);
$result->data->generatedForceAvgDamage = GetPartyDamageAverage($result->generatedForce);

echo json_encode($result);

function GenerateArmy($units, $partyHP, $partyAvgDamage, $options)
{
    $force = [];

    while(GetPartyHP($force) < $partyHP)
    {
        $dmg = GetPartyDamageAverage($force);
        
        if ($dmg * 1.1 > $partyAvgDamage)
        {
            if (!$options->swarmMode) 
            {
                usort($units, "CompareUnitSwarm");
            }
            else 
            {
                usort($units, "CompareUnit");
            }

            for($i = 0; $i < count($units); $i++)
            {
                if ($units[$i]->avgDamage < $partyAvgDamage && GetPartyHP($force) + $units[$i]->hp < $partyHP * 1.1)
                {
                    array_push($force, $units[$i]);
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

            for($i = 0; $i < count($units); $i++)
            {
                if ($units[$i]->avgDamage > $partyAvgDamage && GetPartyHP($force) + $units[$i]->hp < $partyHP * 1.1)
                {
                    array_push($force, $units[$i]);
                    break;
                }
            }
        }
    }

    return $force;
}

function CompareUnit($a, $b)
{
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