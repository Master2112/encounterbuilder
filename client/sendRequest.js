$(document).ready(function(){
    $("#input").val( JSON.stringify(data, null, 3) );
});
function sendRequestButton()
{
    LoadData($("#input").val());
}

function LoadData(dataJson)
{
    $.ajax({
        type: "POST",
        url: 'http://localhost/encounterbuilder/server/',
        dataType: "json",
        data: dataJson,
        complete: function(msg)
        {
            console.log("received:");
            console.log(msg.responseJSON);
            console.log(msg.responseText);

            var data = msg.responseJSON;

            $("#output").html("");

            $("#output").append('<div style="float:left;padding:2px;border:1px solid black;margin:3px;width:412px;height:100px;background-color: ' + intToRGB(hashCode("stats")) + '">' + 
            "Party HP: " + data.data.partyHP + "<br>" +
            "Party Avg. Damage: " + data.data.partyAvgDamage + "<br>" +
            "Generated Force HP: " + data.data.generatedForceHP + "<br>" +
            "Generated Force Avg. Damage: " + data.data.generatedForceAvgDamage + "<br>" +
            "Generated Force Avg. Range: " + data.data.generatedForceAvgRange + "<br>" +
            '</div>')

            for (i = 0; i < data.data.input.groups; i++)
            {
                $("#output").append('<div style="width: calc(80%);min-height:20%;margin:1%;border: 1px solid black;float:left;background-color: ' + intToRGB(hashCode("group" + i + 1000 * i)) + ';" id="group' + i + '"></div>');    
                
                $("#group" + i).append('<div style="float:left;padding:2px;border:1px solid black;margin:3px;width:calc(100% - 12px);height:150px;background-color: ' + intToRGB(hashCode("stats" + i + 1000 * i)) + '">' + 
                "<h2>Group " + i + "</h2>" +
                "Group HP: " + data.data.groups[i].HP + "<br>" +
                "Group Avg. Damage: " + data.data.groups[i].avgDamage + "<br>" +
                "Group Avg. Range: " + data.data.groups[i].avgRange + "<br>" +
                '</div><br>')
            }

            RenderForce(msg.responseJSON);
        }
    });
}

function RenderForce(data)
{
    for (var i = 0; i < data.generatedForce.length; i++)
    {
        $("#group" + data.generatedForce[i].group).append('<div style="float:left;padding:2px;border:1px solid black;margin:3px;width:200px;height:80px;background-color: ' + intToRGB(hashCode(data.generatedForce[i].name + "color")) + '">' + 
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