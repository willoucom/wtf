<?php
// Gestion de l'utilisateur

class Traduction {

    function Traduction(){
        // Variables
        $module = '';
        $langue = 1;
        $dbh = '';
    }

    // constructeur
    function __construct($dbh) {
        // print "In constructor\n";        
        $this->dbh = $dbh;
        $this->langue = 1;
    }

    function charger($textes2,$module){
        $dbh = $this->dbh;
        $textes = array();
        if(array_key_exists('langue', $_SESSION)){
            $this->langue = $_SESSION['langue'];            
        }
        
        $langue = $this->langue;
        // on charge en premier la langue par défaut de l'application (le français)
        $query = "select * from traduction where langue = '1' and modul = '".$module."'";
        // imp($query);
        try {
            $res = $dbh->query($query);                
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>" . $query;
            die();
        }
         
        while($row = $res->fetch(PDO::FETCH_ASSOC)){
            $textes[$row['tag']] = $row['contenu'];
        }
        $res->closeCursor();
        unset($res);

        if($langue != 1){                
            // lister toutes les traductions du module
            $query = "select * from traduction where langue = '".$langue."' and modul = '".$module."'";
            // imp($query);
            try {
                $res = $dbh->query($query);                
            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>" . $query;
                die();
            }
            try {               
                while($row = $res->fetch(PDO::FETCH_ASSOC)){
                    $textes[$row['tag']] = $row['contenu'];
                }
            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>" . $query;
                die();
            }

            // $this->trad = $trad;
            $res->closeCursor();
        }
        // on set la locale en fonction
        $query = "select locale,date_format from langue where idlangue = " . $langue;
        try {
            $res = $dbh->query($query);
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $locale = $row['locale'];
            $textes['date_format'] = $row['date_format'];
            setlocale(LC_ALL, $locale);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>" . $query;
            die();
        }


        $textes = array_merge($textes2, $textes);
        return $textes;
    }

	// Liste des langues
	function getLanguesList($messages) {
		$dbh = $this->dbh;
		$id_utilisateur = $dbh->quote($messages['id_utilisateur']);
		$query = "select idlangue, libelle,locale from langue ;";
		try {
			$res = $dbh->query($query);
			$langues = array();
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
				$langues[$row['idlangue']] = $row['libelle'];
			}
			$messages['contenu']['liste_langues'] = $langues;
		} catch (PDOException $e) {
			print "Erreur !: " . $e->getMessage() . "<br/>";
			die();
		}
		return $messages;
	}

}
?>