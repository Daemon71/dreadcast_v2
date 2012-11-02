<?php
$taille=6;
$nbiter=50;
?>

<html>
<head>
<script type="text/javascript" src="javascript/jQuery.js"></script>
<script type="text/javascript">
	function actionne(etat)
		{
		document.getElementById('etatact').value = etat;
		}
	
	function changeCouleur(elt)
		{
		couleur = document.getElementById('couleuract').value;
		if(recup('etatact')=="ON")
			{
			couleur2 = document.getElementById(elt).style.backgroundColor;
			$("#"+elt).css("background-color", couleur);
			document.getElementById(document.getElementById('couleuract').value).value++;
			if(document.getElementById(elt).style.backgroundColor!="#AAA")
				{
				if(!test_couleur(couleur2)) document.getElementById(couleur2).value--;
				}
			}
		}
		
	function changeCouleurActuelle(couleur)
		{
		document.getElementById('couleuract').value = couleur;
		}
		
	function reset(elt)
		{
		couleur="#AAA";
		document.getElementById(document.getElementById(elt).style.backgroundColor).value--;
		$("#"+elt).css("background-color", couleur);
		}
		
	function recup(id)
		{
		return document.getElementById(id).value;
		}
	
	function test_couleur(couleur)
		{
		return couleur == document.getElementById('couleuract').value;
		}
		
	function resultat1()
		{
		retour = "";
		for(i=0;i<25;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					retour += "UPDATE dreadmap2_tbl SET idrue="+trad_couleur(couleur)+" WHERE x="+i+" AND y="+j+"\r";
					}
				}
			}
		document.getElementById('genere').value = retour;
		}
		
	function resultat2()
		{
		retour = "";
		for(i=25;i<50;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					retour += "UPDATE dreadmap2_tbl SET idrue="+trad_couleur(couleur)+" WHERE x="+i+" AND y="+j+"\r";
					}
				}
			}
		document.getElementById('genere').value = retour;
		}
	
	function resultat3()
		{
		retour = "";
		for(i=50;i<75;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					retour += "UPDATE dreadmap2_tbl SET idrue="+trad_couleur(couleur)+" WHERE x="+i+" AND y="+j+"\r";
					}
				}
			}
		document.getElementById('genere').value = retour;
		}
		
	function resultat4()
		{
		retour = "";
		for(i=75;i<100;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					retour += "UPDATE dreadmap2_tbl SET idrue="+trad_couleur(couleur)+" WHERE x="+i+" AND y="+j+"\r";
					}
				}
			}
		document.getElementById('genere').value = retour;
		}
		
	function resultat5()
		{
		retour = "";
		for(i=125;i<125;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					retour += "UPDATE dreadmap2_tbl SET idrue="+trad_couleur(couleur)+" WHERE x="+i+" AND y="+j+"\r";
					}
				}
			}
		document.getElementById('genere').value = retour;
		}
		
	function trad_couleur(couleur)
		{
		if(couleur=="black") return 0;
		if(couleur=="white") return 1;
		if(couleur=="red") return 2;
		if(couleur=="orange") return 3;
		if(couleur=="blue") return 4;
		if(couleur=="green") return 5;
		if(couleur=="yellow") return 6;
		if(couleur=="violet") return 7;
		if(couleur=="brown") return 8;
		if(couleur=="pink") return 9;
		}
		
	function uniformise()
		{
		for(i=0;i<125;i++)
			{
			for(j=0;j<125;j++)
				{
				couleur = document.getElementById(i+'-'+j).style.backgroundColor;
				if(couleur!="rgb(170, 170, 170)")
					{
					document.getElementById(i+'-'+j).style.backgroundColor = "black";
					}
				}
			}
		}
	
	function calcul(couleur)
		{
		retour=0;
		for(i=0;i<<?php print($nbiter); ?>;i++)
			{
			for(j=0;j<<?php print($nbiter); ?>;j++)
				{
				if(document.getElementById(i+'-'+j).style.backgroundColor == couleur)
					{
					retour++;
					}
				}
			}
		document.getElementById(couleur).value = retour;
		}
	
</script>
</head>
<body style="background-color:#191919;color:#AAA;" onkeydown="javascript:actionne('ON');" onkeyup="javascript:actionne('OFF');">

<div style="position:relative;left:50%;top:0;width:900px;margin-left:-450px;">

<form style="position:relative;top:40px;">
<table>
	<tr>
	<td>Couleur : </td><td><input style="width:40px;background:white;border:1px solid black;" type="text" id="couleuract" value="black" /></td>
	</tr>
	<tr>
	<td>Etat : </td><td><input style="width:40px;background:white;border:1px solid black;" type="text" id="etatact" value="OFF" /></td>
	</tr>
	<tr><td>&nbsp;</td></tr>
	<tr>
	<td>Noir </td><td><input onmouseover="javascript:changeCouleurActuelle('black');" style="width:40px;background:white;border:1px solid black;" type="text" id="black" value="0" /> <div style="position:absolute;right:100px;background-color:black;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('black');"></div> <a style="color:#AAA;" href="javascript:calcul('black');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Blanc </td><td><input onmouseover="javascript:changeCouleurActuelle('white');" style="width:40px;background:white;border:1px solid black;" type="text" id="white" value="0" /> <div style="position:absolute;right:100px;background-color:white;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('white');"></div> <a style="color:#AAA;" href="javascript:calcul('white');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Rouge </td><td><input onmouseover="javascript:changeCouleurActuelle('red');" style="width:40px;background:white;border:1px solid black;" type="text" id="red" value="0" /> <div style="position:absolute;right:100px;background-color:red;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('red');"></div> <a style="color:#AAA;" href="javascript:calcul('red');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Orange </td><td><input onmouseover="javascript:changeCouleurActuelle('orange');" style="width:40px;background:white;border:1px solid black;" type="text" id="orange" value="0" /> <div style="position:absolute;right:100px;background-color:orange;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('orange');"></div> <a style="color:#AAA;" href="javascript:calcul('orange');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Bleu </td><td><input onmouseover="javascript:changeCouleurActuelle('blue');" style="width:40px;background:white;border:1px solid black;" type="text" id="blue" value="0" /> <div style="position:absolute;right:100px;background-color:blue;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('blue');"></div> <a style="color:#AAA;" href="javascript:calcul('blue');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Vert </td><td><input onmouseover="javascript:changeCouleurActuelle('green');" style="width:40px;background:white;border:1px solid black;" type="text" id="green" value="0" /> <div style="position:absolute;right:100px;background-color:green;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('green');"></div> <a style="color:#AAA;" href="javascript:calcul('green');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Jaune </td><td><input onmouseover="javascript:changeCouleurActuelle('yellow');" style="width:40px;background:white;border:1px solid black;" type="text" id="yellow" value="0" /> <div style="position:absolute;right:100px;background-color:yellow;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('yellow');"></div> <a style="color:#AAA;" href="javascript:calcul('yellow');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Violet </td><td><input onmouseover="javascript:changeCouleurActuelle('violet');" style="width:40px;background:white;border:1px solid black;" type="text" id="violet" value="0" /> <div style="position:absolute;right:100px;background-color:violet;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('violet');"></div> <a style="color:#AAA;" href="javascript:calcul('violet');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Marron </td><td><input onmouseover="javascript:changeCouleurActuelle('brown');" style="width:40px;background:white;border:1px solid black;" type="text" id="brown" value="0" /> <div style="position:absolute;right:100px;background-color:brown;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('brown');"></div> <a style="color:#AAA;" href="javascript:calcul('brown');">Recalculer</a></td>
	</tr>
	<tr>
	<td>Rose </td><td><input onmouseover="javascript:changeCouleurActuelle('pink');" style="width:40px;background:white;border:1px solid black;" type="text" id="pink" value="0" /> <div style="position:absolute;right:100px;background-color:pink;width:20px;height:20px;" onmouseover="javascript:changeCouleurActuelle('pink');"></div> <a style="color:#AAA;" href="javascript:calcul('pink');">Recalculer</a></td>
	</tr>
</table>
</form>

<!--<form style="position:absolute;left:800px;top:50px;">
	<textarea id="genere"style="width:450px;height:200px;"></textarea><br />
	<a href="javascript:resultat1();">Génère le résultat1</a><br />
	<a href="javascript:resultat2();">Génère le résultat2</a><br />
	<a href="javascript:resultat3();">Génère le résultat3</a><br />
	<a href="javascript:resultat4();">Génère le résultat4</a><br />
	<a href="javascript:resultat5();">Génère le résultat5</a><br /><br />
	<a href="javascript:uniformise();">Uniformise</a>
</form>-->

<div style="position:absolute;top:10px;left:200px;">
<?php


$color="white";
for($i=0;$i<$nbiter;$i++)
	{
	for($j=0;$j<$nbiter;$j++)
		{
		print('<div id="'.$i.'-'.$j.'" onclick="javascript:reset(\''.$i.'-'.$j.'\');" onmouseover="javascript:changeCouleur(\''.$i.'-'.$j.'\');" style="background-color:#AAA;display:block;position:absolute;top:'.($taille*$i+$i).'px;left:'.($taille*$j+$j).'px;width:'.$taille.'px;height:'.$taille.'px;border:1px solid #AAA;"></div>
');
		}
	}

?>
</div>

</div>

</body>
</html>
