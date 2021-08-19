<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Classe de connexion à la base de données et aux interactions SQL  *
 * Création : ?														 *
 * Modification : 30/12/2020 										 *
 * Version : 2.1													 *
 * copyright : Editiel98											 *
 * Suivi dev :														 *
 * 30/12/2020 : 	- Modification constructeur						 *
 * 					- Deprecated et changement fetch_row()			 *
 *					- Rationnalisation des fonctions				 *
 *					- Depreciation de certaines fonctions inutiles	 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
/*Liste des fonctions :
function __construct($Serveur,$userDB,$UserName,$UserPass, $persistency = false)
function sql_close()
function sql_query($query = "")
function sql_fetch_all($query)
function sql_Last_Erreur()
function get_Last_Exception()
function ExecProc($TabVal, $Requete)
function sql_fetchrow()
function sql_fetch_all_prepared($TabVal,$query)
*/
class NotFoundException extends Exception {}
class connect_base
{
	var $connect_id;  //Objet PDO
	var $query_result;  
	var $sth; //Objet PDOStatement
	var $row=array();
	var $rowset=array();
	var $num_queries=0;
	var $LastException;
	//constructor
	//Old PHP4 : function connect_base($Serveur,$userDB,$UserName,$UserPass, $persistency = true)
	//PHP 7  constructor
/* Constructeur de classe
Prototype :
Role : créé une connexion à la base de données de type MySQL en UTF-8
Entrées :
	$Serveur : nom du serveur de base de données
	$userDB : nom de la base de données
	$UserName :nom d'utilisateur de la base de données
	$UserPass : Mot de passe d'accès la la BDD
	$persistency : optionnel, permet une connexion persistante, par défaut à false
Sorties
	Connexion à la base de données en cas de réussite, null si echec, avec une levée d'exception
exemple : $db=new connect_base('localhost', 'mabase', 'utilisateur', 'motdepasse');
*/
	function __construct($Serveur,$userDB,$UserName,$UserPass, $persistency = false)
	{
		$this->persistency=$persistency;
		$this->user=$UserName;
		$this->base=$userDB; 
		$this->password=$UserPass;
		$this->host=$Serveur;
/* Explications sur les paramètres utilisés :
Pour l'objet PDO lors de la création de la connexion avec la BDD	
	ATTR_PERSISTENT : Accelère le fonctionnement en gardant la connexion ouverte pour l'ensemble de la session de travail (dépasse le temps du script)
	MYSQL_ATTR_FOUND_ROWS : Retourne le nombre d'enregistrements trouvés, pas le nombre d'enregistrements changés.
	
Pour l'objet PDO propre (en dehors de la configuration de  la base de données)	
	ATTR_EMULATE_PREPARES : c'est MySQL qui traite la préparation de la requete
	PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION : N'affiche que les erreurs d'eceptions et pas les warnings.
	
*/		
		if($this->persistency)
		{
			try
			{
				//On passe au PDO le type de serveur et son nom, nom BDD, username, pwd, et un tableau de configuration.
				$this->connect_id =new PDO('mysql:host='.$this->host.';dbname='.$this->base.';charset=UTF8',$this->user,$this->password,array( PDO::ATTR_PERSISTENT => true,PDO::MYSQL_ATTR_FOUND_ROWS=>TRUE));
				//On configure l'objet PDO local
				$this->connect_id->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->connect_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e)
			{
				$this->LastException=$e;
				$this->connect_id = null;
				throw new NotFoundException();
			}
		}
		else
		{
			try
			{
			$this->connect_id =new PDO('mysql:host='.$this->host.';dbname='.$this->base.';charset=UTF8',$this->user,$this->password,array(PDO::MYSQL_ATTR_FOUND_ROWS=>TRUE,PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
			//On configure l'objet PDO local
			$this->connect_id->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
			$this->connect_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			}
			catch(PDOException $e)
			{
				$this->LastException=$e;
				$this->connect_id = null;
				throw new NotFoundException('Connexion impossible');
			}
		}
		if ($this->connect_id)
		{
			return $this->connect_id;
			
		}
		else
		{
			return null;
		}
	}  //fin constructeur
	
//**************************************************************	
	//Destucteur
	function sql_close()
	{
		if($this->connect_id)
		{
			$this->connect_id=null;
		}
	}
	//Fin destructeur
	
//***************************************************
/* Fonction sql_query
prototype : 
Role : execute une requete SQL simple (non préparée ou formatée)
Entrées : requete formatée au langage SQL 
Sorties : Nombre de ligne retournée, en cas de select, le nombres de lignes modifiées en cas de UPDATE/INSERT/DELETE
Utilisation :
$Requete = "SELECT/INSERT/DELETE..............";
exemple
$query=$db->sql_query($Requete);
*/
	function sql_query($query = "")
	{
		if(!(is_null($this->connect_id)))
		{
		// Remove any pre-existing queries 
			unset($this->query_result);
			if($query != "")
			{
				//On prépare la requete
				$this->sth=$this->connect_id->prepare($query);
				//On l'éxécute. Utilité de query_result ? Retour true ou false
				$this->query_result =$this->sth->execute();
			}
			//Renvoi le nombre de lignes contenues
			return $this->sth->rowCount();
		}

	}

//**********************************************************	
	function sql_Last_Erreur()
	{
		$a=$this->sth->errorInfo();
		return $a;
	}
//********************************************************	
	function get_Last_Exception()
	{
		return $this->LastException;
	}
	
//**********************************************************
/*  ExecProc
Prototype 
Fonction exécutant une requete SQL paramétrée
Entrée :
	$TabVal : tableau de valeur à binder à la requete SQL
	$Requete : Requete SQL paramétrée.
Sorties 
	Retourne le nombre d'enregistrement affectés par la requete
Exemple
	$Requete="SELECT * FROM matable WHERE id=:nom";
	$tabval=array(':nom'=>1);
	$Resultat=$db->ExecProc($tabval,$Requete);
	
*/	
	 //Ajout V2
	function ExecProc($TabVal, $Requete)
	{
		//Si on a bien une connexion à la BDD
		if(!(is_null($this->connect_id)))
		{
			unset($this->query_result);
			//Si on a une requete SQL
			if($Requete!='')
			{
				try
				{
					//On exécute comme avant.
					$this->sth=$this->connect_id->prepare($Requete);
					$this->query_result =$this->sth->execute($TabVal);
					return $this->sth->rowCount();
				}
				catch(PDOException $e)
				{
					/*echo "<p> Erreur : ";
					$a=$this->sth->errorInfo();
					print_r($a);
					echo "</p>";*/
					$this->LastException=$e;
					return -1;
				}
			}
			else
			{
				return -1;
			}
		}
	}
	//****************************************************************************
/* sql_fetch_all
prototype : 
Récupère l'ensemble du dataset d'une requete simple de type select
Entrées :
	$query : requete SQL
Sortie :
	Tableau contenant le dataset retourné
exemple :
$tab_res=$db->sql_fetch_all($Requete_SQL);
*/
	function sql_fetch_all($query)
	{
		if(!(is_null($this->connect_id)))
		{
			$this->sth=$this->connect_id->prepare($query);
			$this->query_result =$this->sth->execute();
			$this->Tab_All=$this->sth->fetchAll();
			return $this->Tab_All;
		}
	}

//Version 2.1
/* sql_fetchrow()
Prototype
Role : Récupère un enregistrement du dataset
Entrées : 
	Rien
Sortie :
	L'enregistrement suivant du dataset
Exemple :
$Requete ="SELECT....";
$Query=db->sql_query($Requete);
$LigneDonnee=db->sql_fetchrow();
*/
	function sql_fetchrow()
	{
		if(!(is_null($this->connect_id)))
		{	
				return $this->sth->fetch(PDO::FETCH_ASSOC);
		}
		else
		{
			return false;
		}
	}
 
//*************************************************************

/*  sql_fetch_all_prepared
Prototype 
Fonction exécutant une requete SQL paramétrée de type SELECT et renvoi le dataset
Entrée :
	$TabVal : tableau de valeur à binder à la requete SQL
	$Requete : Requete SQL paramétrée.
Sorties 
	Retourne le dataset
Exemple
	$Requete="SELECT * FROM matable WHERE id=:nom";
	$tabval=array(':nom'=>1);
	$Resultat=$db->ExecProc($tabval,$Requete);
	
*/	
	function sql_fetch_all_prepared($TabVal,$query)
	{
		if(!(is_null($this->connect_id)))
		{
			$this->sth=$this->connect_id->prepare($query);
			$this->query_result =$this->sth->execute($TabVal);
			$this->Tab_All=$this->sth->fetchAll();
			return $this->Tab_All;
		}
	}
	/*Les fonctions si dessous sont deprecated. Concervées pour le moment à titre de comptaibilité. 
	Ne pas utiliser ces fonctions
	*/
	/*Deprecated */
		//On récupère les infos de la base via une requête préparée
	function sql_fetch_prepared($TabVal,$Requete)
	{
		try
		{
			if(!(is_null($this->connect_id)))
			{
				$this->sth=$this->connect_id->prepare($Requete);
				$this->query_result =$this->sth->execute($TabVal);
			}
			else
				return -1;
		}
		catch(PDOException $e)
		{
			$this->LastException=$e;
			return -1;
			//return($e);
		}
	}
	
	function sql_Insert_With_ID($TabVal,$query)
	{
		/*Nécessite une requete de type insert, update ou delete suivant
			INSERT INTO table (champs, champs, ...) VALUES(:a,:b,...)
			de meme pour delete ou update
			ne fonctionne pas avec une procédure stockée
		*/
		/*Voir pour pousser les recherche avec une Proc stock */
		if(!(is_null($this->connect_id)))
		{
		// Remove any pre-existing queries 
			unset($this->query_result);
			if($query != "")
			{
				try
				{
					$this->sth=$this->connect_id->prepare($query);
					foreach($TabVal as $key=>$value)
					{
						$this->sth->bindParam($key,$value);
					}	
					if($this->sth->execute())
					{
						//OK
						return $this->connect_id->lastInsertId();
					}
					else
					{
						return false;
					}
				}
				catch(PDOException $e)
				{
					return -1;
				}
			}
			return false;
		}

	}
	function CloseCursor()
	{
		$this->sth->CloseCursor();
	}
//Old versions
 //Version <2.1 Deprecated
	/*function sql_fetchrow($query_id = 0)
	{
		if(!(is_null($this->connect_id)))
		{
			if(!$query_id)
			{
				$query_id = $this->query_result;
			}
			if($query_id)
			{
			  //$this->row[$query_id] =mysql_fetch_assoc($query_id);
				$this->row[$query_id]=$this->sth->fetch(PDO::FETCH_ASSOC);
				return $this->row[$query_id];
			}
			else
			{
				return false;
			}
		}
	}*/
		
}


?>