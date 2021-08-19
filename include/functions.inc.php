<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 																	 *
 * Nom de la page :	function.inc.php								 *
 * Date création :	01/02/2017										 *
 * Date Modification : 31/07/2021									 *
 * Créateur : Guillaume Crégut										 *
 * Version :	1.0B												 *
 * Objet et notes :													 *
 * Regroupe les fonctions utilisées									 *
 *																	 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
require_once('sql.inc.php');
require_once('db.inc.php');
function WarningHandler($errno,$errstr)
{
	throw new Exception('Erreur de zip');
}
 function convertDate($OldDate,$typeD)
{
	switch($typeD)
	{
		case 0 : $format = '#^[0-3][0-9]/[0-1][0-9]/[0-9]{4}$#';
			if (preg_match( $format , $OldDate )) //on vérifie que la date à bien un format de type jj/mm/aaaa
			{
				$TabDate=explode('/',$OldDate);      //On convertit PHP->Mysql
				$DateBase=$TabDate[2].'-'.$TabDate[1].'-'.$TabDate[0];
			}
			else
			{
				$DateBase= date("Y-m-d");//date de ce jour;
			}
			break;
		case 1 : $TabDate=explode('-',$OldDate);      //On convertit Mysql->PHP
			$DateBase=$TabDate[2].'/'.$TabDate[1].'/'.$TabDate[0];
			break;
		default : $DateBase=$OldDate;
	}
	return $DateBase;
}

function CreateMinPng($image,$Dest)
{
	$Img=imagecreatefrompng($image);
	$Img=imagescale($Img,320);
	imagepng($Img,$Dest);
}
function CreateMinJpg($image,$Dest)
{
	//Création de l'image source
	$Img=imagecreatefromjpeg($image);
	$Img=imagescale($Img,320);
	imagejpeg($Img,$Dest);
}
function CreateMinGif($image,$Dest)
{
	$Img=imagecreatefromgif($image);
	$Img=imagescale($Img,320);
	imagegif($Img,$Dest);
}
function deflate($fichier,$Chemin)
{
	/*
	pour chaque fichier du zip, en fonction de l'extension, on sauvegarde l'image sur le répertoire
	*/
	//Ouverture du fichier zip
	$Monzip=new ZipArchive;
	set_error_handler("WarningHandler",E_WARNING);
	try
	{
		$Monzip->open($fichier);
		if($Monzip)
		{
			try
			{
				$Monzip->extractTo($Chemin);
                $Monzip->close();
			}
			catch(Exception $e)
			{
				//On a pas put gérer le zip
				return false;
			}
		}
		else
		{
			//Un souci avec l'ouverture du zip
			return false;
		}
	}
	catch(Exception $e)
	{
		//On a pas pu ouvrir le zip
		echo "<p>Erreur 3</p>";
	}
	restore_error_handler();
	return true;
}
function saveFile($fichier,$chemin)
{
	$Retour=false;
	if(move_uploaded_file($fichier,$chemin))
		$Retour=true;
	return $Retour;
}
//Procédures pour l'archivage des fichiers
function purge_photos($Repertoire)
{
	global $Rep_Photo_Valide;
	global $CheminMiniatures;
	$retour=true;
//Suppression répertoire valide et de ce qu'il contient
	$Base_Valide=substr($Rep_Photo_Valide,1);
	$Dir_Valide=$Repertoire.$Base_Valide;
	if(is_dir($Dir_Valide))
	{
		if ($handle = opendir($Dir_Valide))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != "." && $entry != "..")
				{
					$FileName=$Dir_Valide.$entry;
					unlink($FileName);
				}
			}
			closedir($handle);
		}
		//Suppression repertoire
		rmdir($Dir_Valide);
	}
//Suppression de mini et de ce qu'il conient
	$Dir_Mini=$Repertoire.$CheminMiniatures;
	if(is_dir($Dir_Mini))
	{
		if ($handle = opendir($Dir_Mini))
		{
			while (false !== ($entry = readdir($handle)))
			{
				if ($entry != "." && $entry != "..")
				{
					$FileName=$Dir_Mini.$entry;
					unlink($FileName);
				}
			}
			closedir($handle);
			
		}
		//Suppression repertoire
		rmdir($Dir_Mini);
	}
	//suppression du contenu du repertoire
	if(is_dir($Repertoire))
	{
		if ($handle = opendir($Repertoire)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$FileName=$Repertoire.$entry;
					unlink($FileName);
				}
			}
			closedir($handle);
		}
		//Suppression repertoire
		$retour=rmdir($Repertoire);
	}
	return $retour;
}
function purge_fichier($Repertoire,$Fichier)
{
	$retour=true; //Si ce n'est pas un répertoire, on renvoie que la fonction à quand même bien fonctionner
	//Suppression du fichier
	if(is_dir($Repertoire))
	{
		if(file_exists($Repertoire.$Fichier))
		{
			$Filename=$Repertoire.$Fichier;
			$retour=unlink($Filename);
		}
	}
	return $retour;
}
function purge_echange($repertoire)
{
	//suppression des fichers du repertoire
	$retour=true; // si pas répertoire, alors fonction peut quand même retourner true
	if(is_dir($repertoire))
	{
		if ($handle = opendir($repertoire)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$FileName=$repertoire.$entry;
					unlink($FileName);
				}
			}
			closedir($handle);
			//Suppression repertoire
			$retour=rmdir($repertoire);
			
		}
	}
	return $retour;
}
function purge_article($idArticle,$backup=false,$chemin='')
{
	$Resultat=1;
	global $DataBaseServeur;
	global $DataBaseName;
	global $DataBaseUser;
	global $DataBasePass;
	global $CheminArticle;
	global $CheminBaseFichier;
	global $CheminMEP;
    global $CheminPhotos;
    global $CheminEspace;
	global $RepTemp;
	//Requetes SQL
	global $SQL_Purge_Article;
	global $SQLS_Supp_Espace;
	global $SQL_Supp_Espace_User;
	global $SQL_Supp_MEP;
	global $SQL_Supp_Photo;
	global $SQL_Supp_Message_Espace;
    global $SQL_Supp_Message_Redaction;
	global $SQL_Set_Article_Archive;
	$Conn=new connect_base($DataBaseServeur,$DataBaseName,$DataBaseUser,$DataBasePass);
	//Etat_Article_Archive;
	//Récupération des infos dans la base de donnéees
	$TabVar=array(':id_article'=>$idArticle);
	$SQLS=$SQL_Purge_Article;
	$row=$Conn->sql_fetch_all_prepared($TabVar,$SQLS);
	//Chemin s'il existe, pour la MEP
	if($row[0]['fichier_mep']!='')
	{
		$Chemin_Purge_MEP=$CheminBaseFichier.$CheminMEP;
		if($backup)
		{
			//On copie le fichier dans le répertoire $chemin
			$NewFile=$chemin.$row[0]['fichier_mep'];
			$OldFile=$Chemin_Purge_MEP.$row[0]['fichier_mep'];
			if(!copy($OldFile,$NewFile))
			{
				$Resultat=-11;
			}
		}
		if(purge_fichier($Chemin_Purge_MEP,$row[0]['fichier_mep']))
		{
			//Suppression de l'entrée MEP dans la base de données
			$SQLS=$SQL_Supp_MEP;
			$Retour=$Conn->ExecProc($TabVar,$SQLS);
			if($Retour!=1)
			{
				$Resultat=-1;
			}
			//Suppression des messages liés
			$SQLS=$SQL_Supp_Message_Redaction;
			$Retour=$Conn->ExecProc($TabVar,$SQLS);
			if($Retour<0)
			{
				$Resultat=-2;
			}
		}
		else
		{
			$Resultat=-3;
		}
		
	}
	//Chemin s'il existe, pour  les photos
	if($row[0]['chemin_photo']!='')
	{
		$Chemin_Purge_Photo=$CheminBaseFichier.$CheminPhotos.$row[0]['chemin_photo'].'/';
		if(purge_photos($Chemin_Purge_Photo))
		{
			//Suppression de photos dans la base de données
			$SQLS=$SQL_Supp_Photo;
			$Retour=$Conn->ExecProc($TabVar,$SQLS);
			if($Retour<0)
			{
				$Resultat=-4;
			}
		}
		else
		{
			$Resultat=-5;
		}
		
	}
	//Chemin, s'il existe, pour le fichier
	if($row[0]['fichiers']!='')
	{
		$Chemin_Purge_Article=$CheminBaseFichier.$CheminArticle;
		if(!purge_fichier($Chemin_Purge_Article,$row[0]['fichiers']))
		{
			$Resultat=-6;
		}
	}
	//Chemin, s'il existe, des échanges
	if($row[0]['id_espace']!='')
	{
		$Chemin_Purge_Espace=$CheminBaseFichier.$CheminEspace.$row[0]['id_espace'].'/';
		if(purge_echange($Chemin_Purge_Espace))
		{
			//Suppression dans la base des espaces
			$SQLS=$SQL_Supp_Espace_User;
			$idEspace=$row[0]['id_espace'];
			$TabEspace=array(':id_espace'=>$idEspace);
			$Retour=$Conn->ExecProc($TabEspace,$SQLS);
			if($Retour>=0)
			{
				//Suppression des echanges de messages
				$SQLS=$SQL_Supp_Message_Espace;
				$Retour=$Conn->ExecProc($TabEspace,$SQLS);
				if($Retour>=0)
				{
					$SQLS=$SQLS_Supp_Espace;
					$Retour=$Conn->ExecProc($TabEspace,$SQLS);
					if($Retour<0)
					{
						$Resultat=-7;
					}
				}
				else
				{
					$Resultat=-8;
				}

			}
			else
			{
				$Resultat=-9;
			}
		}
		else
		{
			$Resultat=-10;
		}
	}
	if($Resultat>0)
	{
		$SQLS=$SQL_Set_Article_Archive;
		$TabId=array(':id'=>$idArticle);
		$Resultat=$Conn->ExecProc($TabId,$SQLS);

	}
	return $Resultat;
	
}
?>