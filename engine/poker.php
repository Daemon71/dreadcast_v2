<?php
function resultat_poker ($carte1,$carte2,$carte3,$carte4,$carte5)
{
//on initialise le résultat à la plus petite combinaison possible (ie : rien)
$resultat=9;
	
//on fait un tableau double pour enregistrer la couleur et le type des cartes dans des variables différentes
	$jeu['force']=array(substr($carte1,2,4),substr($carte2,2,4),substr($carte3,2,4),substr($carte4,2,4),substr($carte5,2,4));
	$jeu['couleur']=array(substr($carte1,0,1),substr($carte2,0,1),substr($carte3,0,1),substr($carte4,0,1),substr($carte5,0,1));
	
//tri des cartes dans l'ordre de force
	for ($i=0;$i<4;$i++)
		{
			for ($j=0;$j<4;$j++)
				{
					if ($jeu['force'][$j]<$jeu['force'][$j+1])
						{
							$temp=array($jeu['force'][$j],$jeu['couleur'][$j]);
							$jeu['force'][$j]=$jeu['force'][$j+1];
							$jeu['couleur'][$j]=$jeu['couleur'][$j+1];
							$jeu['force'][$j+1]=$temp[0];
							$jeu['couleur'][$j+1]=$temp[1];
						}
				}
		}
		
		for ($i=0;$i<5;$i++)
			{
				echo $jeu['couleur'][$i].' '.$jeu['force'][$i].'<br/>';
			}		


	
//on démarre maintenant les tests des combinaisons



//gestion des couleurs, quintes flush et quintes flush royale

	if ($jeu['couleur'][0]==$jeu['couleur'][1] && $jeu['couleur'][1]==$jeu['couleur'][2] && $jeu['couleur'][2]==$jeu['couleur'][3] && $jeu['couleur'][3]==$jeu['couleur'][4])
	{
		if ($jeu['force'][4]=='1')
			{
				if ($jeu['force'][0]-$jeu['force'][1]==1 && $jeu['force'][1]-$jeu['force'][2]==1 && $jeu['force'][2]-$jeu['force'][3]==1)
					{
						if ($jeu['force'][0]=='13')
							{$resultat=1;}
						elseif ($jeu['force'][3]=='2')
							{$resultat=2;}
						else
							{$resultat=5;}
					}
				else
					{$resultat=5;}
			}
		elseif ($jeu['force'][0]-$jeu['force'][1]==1 && $jeu['force'][1]-$jeu['force'][2]==1 && $jeu['force'][2]-$jeu['force'][3]==1 && $jeu['force'][3]-$jeu['force'][4]==1)
			{
				$resultat=2;
			}
		else
			{$resultat=5;}
	}



//gestion des carrés

	if ($jeu['force'][0]==$jeu['force'][1] && $jeu['force'][1]==$jeu['force'][2] && $jeu['force'][2]==$jeu['force'][3])
		{
			if ($resultat>3)
				{$resultat=3;}
		}
	if ($jeu['force'][3]==$jeu['force'][4] && $jeu['force'][1]==$jeu['force'][2] && $jeu['force'][2]==$jeu['force'][3])
		{
			if ($resultat>3)
				{$resultat=3;}
		}



//gestion des suites

	if ($jeu['force'][4]=='1')
		{
			if ($jeu['force'][0]-$jeu['force'][1]==1 && $jeu['force'][1]-$jeu['force'][2]==1 && $jeu['force'][2]-$jeu['force'][3]==1)
				{
					if ($jeu['force'][0]=='13')
						{
							if ($resultat>6)
								{$resultat=6;}
						}
					elseif ($jeu['force'][3]=='2')
						{
							if ($resultat>6)
								{$resultat=6;}
						}
				}
		}
	elseif ($jeu['force'][0]-$jeu['force'][1]==1 && $jeu['force'][1]-$jeu['force'][2]==1 && $jeu['force'][2]-$jeu['force'][3]==1 && $jeu['force'][3]-$jeu['force'][4]==1)
		{
			if ($resultat>6)
				{$resultat=6;}
		}



//gestion des fulls et brelan

	if ($jeu['force'][0]==$jeu['force'][1] && $jeu['force'][1]==$jeu['force'][2])
		{
			if ($jeu['force'][3]==$jeu['force'][4])
				{
					if ($resultat>4)
						{$resultat=4;}
				}
			else
				{
					if ($resultat>7)
						{$resultat=7;}
				}
		}
	elseif ($jeu['force'][2]==$jeu['force'][3] && $jeu['force'][3]==$jeu['force'][4])
		{
			if ($jeu['force'][0]==$jeu['force'][1])
				{
					if ($resultat>4)
						{$resultat=4;}
				}
			else
				{
					if ($resultat>7)
						{$resultat=7;}
				}
		}



//gestion des doubles paires

	if ($jeu['force'][0]==$jeu['force'][1])
		{
			if ($jeu['force'][2]==$jeu['force'][3])
				{
					if ($resultat>8)
						{$resultat=8;}
				}
			elseif ($jeu['force'][3]==$jeu['force'][4])
				{
					if ($resultat>8)
						{$resultat=8;}
				}
		}
	elseif ($jeu['force'][1]==$jeu['force'][2])
		{
			if ($jeu['force'][3]==$jeu['force'][4])
				{
					if ($resultat>8)
						{$resultat=8;}
				}
		}



//gestion du resultat
switch ($resultat)
	{
		case 1 :
			{
				$combinaison = "quinte flush royale";
				return $combinaison ;
				break;
			}
		case 2 :
			{
				$combinaison = "quinte flush";
				return $combinaison ;
				break;
			}
		case 3 :
			{
				$combinaison = "carre";
				return $combinaison ;
				break;
			}
		case 4 :
			{
				$combinaison = "full";
				return $combinaison ;
				break;
			}
		case 5 :
			{
				$combinaison = "couleur";
				return $combinaison ;
				break;
			}
		case 6 :
			{
				$combinaison = "suite";
				return $combinaison ;
				break;
			}
		case 7 :
			{
				$combinaison = "brelan";
				return $combinaison ;
				break;
			}
		case 8 :
			{
				$combinaison = "double paire";
				return $combinaison ;
				break;
			}
		case 9 :
			{
				$combinaison = "rien";
				return $combinaison ;
				break;
			}
		default :
			{
				$combinaison = "rien";
				return $combinaison ;
				break;
			}
	}

}









//simple script pour le test ^^ 


$carte1='1_13';
$carte2='2_10';
$carte3='1_9';
$carte4='1_10';
$carte5='4_10';

echo '<br/><br/>'.resultat_poker($carte1,$carte2,$carte3,$carte4,$carte5);
?>
