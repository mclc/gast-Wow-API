<?php include('WowAPI.php'); ?>
<html>
    <head>
        <title>WowAPI examples</title>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
        <style>
            table{border-collapse: collapse;}
            td{border: 1px solid black; padding: 3px;}
        </style>
    </head>
    <body>
        <p>
            See PHP source code for more informations and try to print_r the result to see what data is available
        </p>
        
        <h1>Character</h1>
        <?php $character = WowAPI::character('Gasba', 'eu', 'Medivh', 'all'); ?>
        <table>
            <tr>
                <td>Name</td>
                <td><?php echo $character->name; ?></td>
            </tr>
            <tr>
                <td>Level</td>
                <td><?php echo $character->level; ?></td>
            </tr>
            <tr>
                <td>Guild</td>
                <td><?php echo $character->guild->name; ?></td>
            </tr>
            <tr>
                <td>Achievments points</td>
                <td><?php echo $character->achievementPoints; ?></td>
            </tr>
        </table>
        
        <h1>Guild</h1>
        <?php $guild = WowAPI::guild('La Waagh Retrouvée', 'eu', 'Medivh', 'all'); ?>
        <table>
            <tr>
                <td>Name</td>
                <td><?php echo $guild->name; ?></td>
            </tr>
            <tr>
                <td>Level</td>
                <td><?php echo $guild->level; ?></td>
            </tr>
            <tr>
                <td>Members</td>
                <td><?php echo count($guild->members); ?></td>
            </tr>
        </table>
        
        <h1>Realm</h1>
        <?php $realms  = WowAPI::realm('eu', array('Medivh', 'Archimonde')); ?>
        <table>
            <?php foreach($realms->realms as $r) : ?>
            <tr>
                <td>Name</td>
                <td><?php echo $r->name; ?></td>
            </tr>
            <tr>
                <td>Informations</td>
                <td>
                    <table>
                        <tr>
                            <td>Type</td>
                            <td><?php echo $r->type; ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?php if($r->status==1) echo 'Alive'; else echo 'Not alive' ?></td>
                        </tr>
                        <tr>
                            <td>Population</td>
                            <td><?php echo $r->population; ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
    </body>
</html>