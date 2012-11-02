<body style="background:#dcdbf0;">
<?php 

$db = mysql_connect('localhost', 'CENSURE', 'CENSURE') or die("Erreur de connexion au serveur des messages.");
mysql_select_db('CENSURE',$db) or die("Erreur de connexion a la base de donnees des messages.");

$sql = 'SELECT x,y,idrue FROM carte_tbl' ;
$req = mysql_query($sql);
$res = mysql_num_rows($req);
print('<div style="position:relative;left:2px;top:2px;">');
print('<div style="z-index:50;position:absolute;">');

for($i=0;$i<$res;$i++)
	{
	$col = (mysql_result($req,$i,idrue) == 0)?'white':"#333";/*(mysql_result($req,$i,idrue) == 1)?'red':(
			(mysql_result($req,$i,idrue) == 2)?'blue':(
			(mysql_result($req,$i,idrue) == 3)?'green':(
			(mysql_result($req,$i,idrue) == 4)?'white':(
			(mysql_result($req,$i,idrue) == 5)?'black':(
			(mysql_result($req,$i,idrue) == 6)?'yellow':(
			(mysql_result($req,$i,idrue) == 7)?'violet':(
			(mysql_result($req,$i,idrue) == 8)?'brown':(
			(mysql_result($req,$i,idrue) == 9)?'orange':'pink'
			))))))));*/
	print('<div style="position:absolute;left:'.(2*(mysql_result($req,$i,y)-1)).'px;top:'.(2*(mysql_result($req,$i,x)-1)).'px;background:'.$col.';width:2px;height:2px;"></div>');
	}
print('</div>');/*
print('<div style="left:0;top:0;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:100px;top:0;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:200px;top:0;z-index:2;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:300px;top:0;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:400px;top:0;z-index:3;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:500px;top:0;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');

print('<div style="left:0;top:100px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:100px;top:100px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:200px;top:100px;z-index:2;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:300px;top:100px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:400px;top:100px;z-index:3;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:500px;top:100px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');

print('<div style="left:0;top:200px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:100px;top:200px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:200px;top:200px;z-index:2;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:300px;top:200px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:400px;top:200px;z-index:3;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:500px;top:200px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');

print('<div style="left:0;top:300px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:100px;top:300px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:200px;top:300px;z-index:2;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:300px;top:300px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:400px;top:300px;z-index:3;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:500px;top:300px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');

print('<div style="left:0;top:400px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:100px;top:400px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:200px;top:400px;z-index:2;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:300px;top:400px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:400px;top:400px;z-index:3;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:500px;top:400px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');

print('<div style="left:0;top:500px;z-index:1;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:100px;top:500px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:200px;top:500px;z-index:2;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:300px;top:500px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');
print('<div style="left:400px;top:500px;z-index:3;position:absolute;width:100px;height:100px;background:#a2a2a2;"></div>');
print('<div style="left:500px;top:500px;z-index:1;position:absolute;width:100px;height:100px;background:#909090;"></div>');*/

print('</div>');
mysql_close($db);

?>
</body>
