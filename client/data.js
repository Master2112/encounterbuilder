var data = 
{
    options:
    {
        swarmMode: false, 
        groups: 3, //amount of subgroups in the generated force. Grouped for similarity.
        attackPowerUpperRangePercentage: 1.5, //Higher means harder for party
        attackPowerLowerRangePercentage: 0.3 //Lower means easier for party
    },
    party: 
    [
        {
            name: "Bob",
            hp: 180,
            equipment: 
            [
                {
                    name: "longsword",
                    range: 5,
                    damage: 20
                },
                {
                    name: "shortsword",
                    range: 5,
                    damage: 12
                }
            ]
        },
        {
            name: "Sjon",
            hp: 80,
            equipment: 
            [
                {
                    name: "Longbow",
                    range: 150,
                    damage: 10
                },
                {
                    name: "dagger",
                    range: 5,
                    damage: 8
                }
            ]
        },
        {
            name: "Hans",
            hp: 130,
            equipment: 
            [
                {
                    name: "Greatsword",
                    range: 5,
                    damage: 30
                }
            ]
        },
        {
            name: "Blubber",
            hp: 70,
            equipment: 
            [
                {
                    name: "Mage Staff",
                    range: 120,
                    damage: 15
                },
                {
                    name: "Shortsword",
                    range: 5,
                    damage: 10
                }
            ]
        }
    ],

    availableUnits: 
    [
        {
            name: "Goblin",
            hp: 20,
            equipment: 
            [
                {
                    name: "shortsword",
                    range: 5,
                    damage: 6
                },
                {
                    name: "Shortbow",
                    range: 60,
                    damage: 6
                }
            ]
        },
        {
            name: "Kobold",
            hp: 15,
            equipment: 
            [
                {
                    name: "shortsword",
                    range: 5,
                    damage: 6
                }
            ]
        },
        {
            name: "Rat",
            hp: 4,
            equipment: 
            [
                {
                    name: "Bite",
                    range: 1,
                    damage: 1
                }
            ]
        },
        {
            name: "Wolf",
            hp: 10,
            equipment: 
            [
                {
                    name: "teeth",
                    range: 3,
                    damage: 3
                }
            ]
        },
        {
            name: "Bear",
            hp: 30,
            equipment: 
            [
                {
                    name: "teeth",
                    range: 5,
                    damage: 10
                }
            ]
        },
        {
            name: "Hobgoblin",
            hp: 30,
            equipment: 
            [
                {
                    name: "club",
                    range: 5,
                    damage: 15
                },
                {
                    name: "Shortbow",
                    range: 60,
                    damage: 6
                }
            ]
        },
        {
            name: "Wizard",
            hp: 40,
            equipment: 
            [
                {
                    name: "Staff",
                    range: 150,
                    damage: 20
                }
            ]
        },
        {
            name: "Assassin",
            hp: 20,
            equipment: 
            [
                {
                    name: "Poison Dagger",
                    range: 4,
                    damage: 27
                }
            ]
        },
        {
            name: "Giant spider",
            hp: 25,
            equipment: 
            [
                {
                    name: "Bite",
                    range: 7,
                    damage: 15
                }
            ]
        },
        {
            name: "Veteran",
            hp: 30,
            equipment: 
            [
                {
                    name: "Longsword",
                    range: 5,
                    damage: 17
                }
            ]
        },
        {
            name: "Spearman",
            hp: 20,
            equipment: 
            [
                {
                    name: "Spear",
                    range: 60,
                    damage: 17
                }
            ]
        },
        {
            name: "Skeleton",
            hp: 10,
            equipment: 
            [
                {
                    name: "Shortsword",
                    range: 5,
                    damage: 10
                }
            ]
        },
        {
            name: "Zombie",
            hp: 30,
            equipment: 
            [
                {
                    name: "Bite",
                    range: 3,
                    damage: 17
                }
            ]
        },
        {
            name: "Archer",
            hp: 30,
            equipment: 
            [
                {
                    name: "Longbow",
                    range: 120,
                    damage: 17
                }
            ]
        },
        {
            name: "Footsoldier",
            hp: 40,
            equipment: 
            [
                {
                    name: "Longsword",
                    range: 5,
                    damage: 17
                }
            ]
        },
        {
            name: "Knight",
            hp: 50,
            equipment: 
            [
                {
                    name: "Longsword",
                    range: 5,
                    damage: 17
                }
            ]
        },
        {
            name: "Elite Knight",
            hp: 100,
            equipment: 
            [
                {
                    name: "Greatsword",
                    range: 10,
                    damage: 17
                }
            ]
        },
        {
            name: "Wyvern",
            hp: 150,
            equipment: 
            [
                {
                    name: "Poison Tail",
                    range: 10,
                    damage: 30
                },
                {
                    name: "Claw",
                    range: 5,
                    damage: 15
                }
            ]
        },
        {
            name: "Dragon",
            hp: 200,
            equipment: 
            [
                {
                    name: "fire breath",
                    range: 150,
                    damage: 50
                }
            ]
        }
    ]
}