<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<title>EDMC - EDMC est un Detecteur de Multi Compte</title>
	<link rel="icon" type="image/png" href="http://img143.imageshack.us/img143/9420/bullebla.png" />
	<style type="text/css">
		body {background-color: #000000;color: #BBBBBB;font-family: monospace;white-space: pre;}
	</style>
</head>
<body>
<?php

if ($_GET['is_multi'] != "")
{
	$pseudos = explode('-', $_GET['is_multi']);
	$verbose = isset($_GET['v']);
	$precision = isset($_GET['p']);
	$info = isset($_GET['i']);
	is_multi($pseudos[0],$pseudos[1],$verbose,$precision,$info);
}

function	version()
{
	echo "+-----------+<br />";
	echo "| EDMC V0.2 |<br />";
	echo "+-----------+<br />";
	system("printf %s Caracteres : ; cat EDMC.php | wc -c");
	system("printf %s Mots : ; cat EDMC.php | wc -w");
	system("printf %s Lignes : ; cat EDMC.php | wc -l");
}

function	init()
{
	$a_keys = array('ip', 'ipdc', 'passwd', 'pseudo', 'login', 'loginpseudo', 'mail', 'no_mp', 'gift', 'bank');
	$a_ret =  array_fill_keys($a_keys, 0);
	return ($a_ret);
}

function	is_multi($s_p1, $s_p2, $b_verbose = false, $i_precision = 30, $b_info = false)
{
	$a_coef = array('ip' => 100,
			'ipdc' => 100,
			'same_passwd' => 240,
			'near_passwd' => 90,
			'pseudo' => 70,
			'login' => 70,
			'loginpseudo' => 70,
			'mail' => 10,
			'no_mp' => 30,
			'gift' => 20,
			'gift_no_mp' => 40,
			'bank' => 20,
			'bank_no_mp' => 40,
			'max' => 0);
	$a_coef['max'] = array_sum($a_coef);
	$i_is_multi = 0;
	$i = 0;
	$j = 0;

	if ($s_p1 === $s_p2)
		return (false);
	if ($b_info == true)
	{
		version();
		return (false);
	}

	$bdd = mysql_connect("localhost", "dc_test", "test");
	mysql_select_db("dc_test", $bdd);
	$a_analyse = init();
	$sql_list = mysql_query("SELECT * FROM principal_tbl WHERE pseudo LIKE '$s_p1' OR pseudo LIKE '$s_p2'") OR die(mysql_error());
	if (mysql_num_rows($sql_list) != 2)
		return (false);

	while ($a_tmp = mysql_fetch_array($sql_list))
	{
		if ((strcasecmp($s_p1, $a_tmp['pseudo']) == 0))
			$a_p1 = $a_tmp;
		if ((strcasecmp($s_p2, $a_tmp['pseudo']) == 0))
			$a_p2 = $a_tmp;
	}

	if ($a_p1['ip'] === $a_p2['ip'] && $a_p1['ip'] != "1.1.1.1")
		$a_analyse['ip'] = 1;
	if ($a_p1['ipdc'] === $a_p2['ipdc'])
		$a_analyse['ipdc'] = 1;
	if (similar_text($a_p1['password'], $a_p2['password'], &$f_percent) > 0 && $f_percent >= 70)
		$a_analyse['passwd'] = 1;
	if (stristr($a_p1['pseudo'], $a_p2['pseudo']) != false || stristr($a_p2['pseudo'], $a_p1['pseudo']) != false)
		$a_analyse['pseudo'] = 1;
	if (stristr($a_p1['login'], $a_p2['login']) != false || stristr($a_p2['login'], $a_p1['login']) != false)
		$a_analyse['login'] = 1;
	if (stristr($a_p1['pseudo'], $a_p2['login']) != false || stristr($a_p2['pseudo'], $a_p1['login']) != false)
		$a_analyse['loginpseudo'] = 1;
	$s_cut_mail1 = strrev(stristr(strrev($a_p1['adresse']), '@'));
	$s_cut_mail2 = strrev(stristr(strrev($a_p2['adresse']), '@'));
	if (stristr($s_cut_mail1, $s_cut_mail2) != false || stristr($s_cut_mail2, $s_cut_mail1) != false)
		$a_analyse['mail'] = 1;
	$sql_mp_list = mysql_query("SELECT * FROM messages_tbl WHERE (auteur = '$s_p1' AND cible = '$s_p2')
				 OR (auteur = '$s_p2' AND cible = '$s_p1')") OR die(mysql_error());
	$nb_mp = mysql_num_rows($sql_list) - 1;
	if ($nb_mp > 0)
		$a_analyse['no_mp'] = 1;
	$sql_list = mysql_query("SELECT * FROM transferts_tbl WHERE operation LIKE 'Don %' AND ((donneur = '$s_p1' AND receveur = '$s_p2')
			   	 OR (donneur = '$s_p2' AND receveur = '$s_p1'))") OR die(mysql_error());
	if ($nb_echanges = mysql_num_rows($sql_list))
		$a_analyse['gift'] = 1;
	$sql_list = mysql_query("SELECT * FROM comptes_acces_tbl WHERE pseudo LIKE '$s_p1' OR pseudo LIKE '$s_p2' ") OR die(mysql_error());
	while ($tmp = mysql_fetch_array($sql_list))
	{
		if (strcasecmp($tmp['pseudo'], $s_p1) == 0)
			$a_c1[$i++] = $tmp['compte'];
		else if (strcasecmp($tmp['pseudo'], $s_p2) == 0)
			$a_c2[$j++] = $tmp['compte'];
	}
	if (!empty($a_c1) && !empty($a_c2))
	{
		$a_res = array_intersect(array_unique($a_c1), array_unique($a_c2));
		if ($a_res)
			$a_analyse['bank'] = 1;
	}

	//------------------------------------------------------------------------------------------

	if ($a_analyse['ip'] == 1)
	{
		$i_is_multi += $a_coef['ip'];
		if ($b_verbose == true)
			echo "- Meme IP (".$a_p1['ip'].").<br />";
		elseif ($a_p1['ip'] === "1.1.1.1")
        		echo "- IP autorise.<br />";
	}
	if ($a_analyse['ipdc'] == 1)
	{
		$i_is_multi += $a_coef['ipdc'];
		if ($b_verbose == true)
			echo "- Meme IPDC. <br />";
	}
	if ($a_analyse['passwd'] == 1)
	{
		$i_is_multi += ($percent == 100 ? $a_coef['same_passwd'] : $a_coef['near_passwd']);
	        if ($b_verbose == true)
			printf("- Meme Mot de Passe, ou presque (%s - %s)<br />", $a_p1['password'], $a_p2['password']);
	}
	if ($a_analyse['pseudo'] == 1)
	{
		$i_is_multi += $a_coef['pseudo'];
		if ($b_verbose == true)
			echo "- Pseudo qui se ressemble.<br />";
	}
	if ($a_analyse['login'] == 1)
	{
		$i_is_multi += $a_coef['login'];
		if ($b_verbose == true)
			printf("- Login qui se ressemble. (%s - %s)<br />", $a_p1['login'], $a_p2['login']);
	}
	if ($a_analyse['loginpseudo'] == 1)
	{
		$i_is_multi += $a_coef['loginpseudo'];
		if ($b_verbose == true)
			printf("- Pseudo/Login qui se ressemble. (%s/%s - %s/%s)<br />", $a_p1['pseudo'], $a_p1['login'], $a_p2['pseudo'], $a_p2['login']);
	}
	if ($a_analyse['mail'] == 1)
	{
		$i_is_multi += $a_coef['mail'];
		if ($b_verbose == true)
			printf("- Mail qui se ressemble (%s - %s).<br />", $a_p1['adresse'], $a_p2['adresse']);
	}
	if ($a_analyse['no_mp'] == 1)
	{
		echo "- Se sont deja envoye des mp (".$nb_mp.").<br />";
		while ($tmp = mysql_fetch_array($sql_mp_list))
			printf("     [%s]<br />", $tmp['message']);
	}
	else
	{
		$i_is_multi += $a_coef['no_mp'];
		if ($b_verbose == true)
			echo "- Ne se sont jamais envoye de mp.<br />";
        }
	if ($a_analyse['gift'] == 1)
	{
		$i_is_multi += ($a_analyse['no_mp'] == 1 ? $a_coef['gift_no_mp'] : $a_coef['gift']);
		if ($b_verbose == true)
			echo "- Echange d'objet ou d'argent (".$nb_echanges.").<br />";
	}
	if ($a_analyse['bank'] == 1)
	{
			$i_is_multi += ($b_mp == false ? $a_coef['bank_no_mp'] : $a_coef['bank']);
			if ($b_verbose == true)
				printf("- %s compte%scommun%s: %s<br />", count($a_res), count($a_res) > 1 ? 's ' : ' ', count($a_res) > 1 ? 's ' : ' ', implode(", ", $a_res));
	}


	if (($i_is_multi / $a_coef['max'] * 100) > $i_precision || $b_verbose == true)
		printf("<br /><strong>%s</strong> et <strong>%s</strong> (%d%%)<br />", $s_p1, $s_p2, $i_is_multi / $a_coef['max'] * 100);
}
	?>
</body>
</html>
