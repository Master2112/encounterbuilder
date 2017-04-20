<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type:application/json");

require_once("clustering.php");
require_once("partyGenerator.php");
require_once("partyUtil.php");

set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) 
{
    if (0 === error_reporting()) 
    {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

$options;
$maxUnitTypes = 1;

try
{
    $json = file_get_contents("php://input");

    $lowestDamageSet = false;

    $data = json_decode($json);

    $removedUnits = [];
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

    $options->attackPowerUpperRangePercentage;
    $options->attackPowerLowerRangePercentage;

    $result->data->removedUnits = $removedUnits;

    if (!isset($options->groups))
        $options->groups = 1;

    if ($options->groups > 1)
    {
        SetUnitGroup($result->generatedForce, min($options->groups, $maxUnitTypes));
    }
    else
    {
        for ($i = 0; $i < count($result->generatedForce);  $i++)
        {
            $result->generatedForce[$i]->group = 0;
        }
    }

    $result->data->groups = [];

    for ($i = 0; $i < $options->groups; $i++)
    {
        $groupUnits = GetGroupFromParty($result->generatedForce, $i);

        $result->data->groups[$i] = new stdClass();
        $result->data->groups[$i]->HP = GetPartyHP($groupUnits);
        $result->data->groups[$i]->avgDamage = GetPartyDamageAverage($groupUnits);
        $result->data->groups[$i]->avgRange = GetPartyRangeAverage($groupUnits);
    }

    $result->data->input = $options;

    echo json_encode($result);
}
catch (Exception $e)
{
    header("HTTP/1.1 400 Bad Request");

    $res = new stdClass();
    $res->error = $e->getMessage() . ":" . $e->getLine();
    $res->cause = "Missing/incorrect parameter in input JSON";
    $res->debug = $options;
    echo json_encode($res);
}