$.ajax({
    type: "POST",
    url: 'http://localhost/encounterbuilder/server/',
    dataType: "json",
    data: JSON.stringify(data),
    complete: function(msg)
    {
        console.log("received:");
        console.log(msg.responseJSON);
        console.log(msg.responseText);

        var data = msg.responseJSON;

        $("body").append('<div style="float:left;padding:2px;border:1px solid black;margin:3px;width:412px;height:100px;background-color: ' + intToRGB(hashCode("stats")) + '">' + 
        "Party HP: " + data.data.partyHP + "<br>" +
        "Party Avg. Damage: " + data.data.partyAvgDamage + "<br>" +
        "Generated Force HP: " + data.data.generatedForceHP + "<br>" +
        "Generated Force Avg. Damage: " + data.data.generatedForceAvgDamage + "<br>" +
        "Generated Force Avg. Range: " + data.data.generatedForceAvgRange + "<br>" +
        '</div>')

        RenderForce(msg.responseJSON);
    }
});

function RenderForce(data)
{
    for (var i = 0; i < data.generatedForce.length; i++)
    {
        $("body").append('<div style="float:left;padding:2px;border:1px solid black;margin:3px;width:200px;height:80px;background-color: ' + intToRGB(hashCode(data.generatedForce[i].name)) + '">' + 
        data.generatedForce[i].name + "<br>" +
        data.generatedForce[i].hp + "HP<br>" +
        data.generatedForce[i].avgDamage + " average damage<br>" +
        "Range: " + data.generatedForce[i].range.min + "-" + data.generatedForce[i].range.max + " (" + data.generatedForce[i].range.average + ")<br>" +
        '</div>')
    }
}

function hashCode(str) { // java String#hashCode
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
       hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
} 

function intToRGB(i){
    var c = (i & 0x00FFFFFF)
        .toString(16)
        .toUpperCase();

    return "00000".substring(0, 6 - c.length) + c;
}