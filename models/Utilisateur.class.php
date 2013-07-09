<?php
// Gestion de l'utilisateur

class Utilisateur {

    public $id_utilisateur = '';
    public $is_admin = 0;
    public $langue = '';
    public $email = '';
    
    private $token = '';        
    private $traduction = array();
    private $tradobj = '';
    private $dbh = '';

    function Utilisateur(){
        // Variables
    }

    // constructeur
    function __construct($dbh,$traduction) {
        // print "In constructor\n";
        $this->dbh = $dbh;
        // je charge les trads pour le module 
        $texte = array();
        $this->tradobj = $traduction;
        $this->traduction = $this->tradobj->charger($texte,'Utilisateur');
        // unset($traduction);
    }


    // Fonctions

    // gere la connexion
    function verifie_connexion($messages) {
        $dbh = $this->dbh;
        // imp($messages,'messages');

        // je regarde dans la session
        if(array_key_exists('connecte', $_SESSION) && $_SESSION['connecte']){
            $messages['utilisateur']['id_utilisateur'] = $_SESSION['idutilisateur'];            
            // on va dire que je suis connecté
            $messages['notification']['message'] = $this->traduction['connecte'] ; // message à affiche en cas d’erreur/réussite
            $messages['notification']['niveau'] = '0' ; // niveau d’alerte (la couleur de l’alerte)
            $messages['notification']['redirect'] = '' ; // page suivante (seulement en html)
            // $messages['notification']['redirect_time'] = 3 ; // temps du redirection (seulement en html)
            $messages = $this->charge_profil($messages,$_SESSION['idutilisateur']);
        }elseif(array_key_exists('token', $messages['request'])){
            // identifie par le token et pas par la session
            $token = $messages['request']['token'];
            $messages = $this->charge_profil_token($messages,$token);
        }else{
            $messages = $this->connexion($messages);
        }
        return $messages;
    }

    // connecte
    function connexion($messages){
        $dbh = $this->dbh;
        // imp($dbh);

        if(array_key_exists('utilisateur', $messages['request']) && array_key_exists('motdepasse', $messages['request'])){
            $utilisateur = $dbh->quote($messages['request']['utilisateur']);
            $motdepasse = $dbh->quote($messages['request']['motdepasse']);

            $query = 'select idutilisateur from utilisateur where username = '.$utilisateur.' and password = '.$motdepasse.';';
            // imp($query);
            try {
                $res = $dbh->query($query);                
                $row = $res->fetch(PDO::FETCH_ASSOC);
                $res->closeCursor();
                $messages = $this->charge_profil($messages,$row['idutilisateur']);

            } catch (PDOException $e) {
                print "Erreur !: " . $e->getMessage() . "<br/>";
                die();
            }
        }

        // imp($row,'row');

        if(isset($row) && is_array($row) && array_key_exists('idutilisateur', $row) && $row['idutilisateur']){
            // connecté
            $_SESSION['connecte'] = true;
            $_SESSION['idutilisateur'] = $row['idutilisateur'];
            $messages['idutilisateur'] = $row['idutilisateur'];
            // $_SESSION['langue'] = $row['langue'];
            $this->traduction = $this->tradobj->charger($this->traduction,'Utilisateur');

            $messages['notification']['message'] = $this->traduction['connecte'] ; // message à affiche en cas d’erreur/réussite
            $messages['notification']['niveau'] = '1' ; // niveau d’alerte (la couleur de l’alerte)
            $messages['notification']['redirect'] = 'index.php' ; // page suivante (seulement en html)
            $messages['notification']['redirect_time'] = 3 ; // temps du redirection (seulement en html)

            $this->token = $this->cree_Token($row['idutilisateur']);
            $messages['contenu']['token'] = $this->token;
        }else{
            // raté
            $_SESSION['connecte'] = false;
            $_SESSION['idutilisateur'] = 0;
            $messages['notification']['message'] = $this->traduction['nonconnecte'] ; // message à affiche en cas d’erreur/réussite
            $messages['notification']['niveau'] = '3' ; // niveau d’alerte (la couleur de l’alerte)
            $messages['notification']['redirect'] = 'login.php' ; // page suivante (seulement en html)
            $messages['notification']['redirect_time'] = 1 ; // temps du redirection (seulement en html)
            // if($)           
            if(!headers_sent() && basename($_SERVER['SCRIPT_NAME']) != 'login.php'){
                // imp(basename($_SERVER['SCRIPT_NAME'])) ;
                header('Location: login.php');                
            }
            
        }
        return $messages;
    }

    // recupere la langue de l'utilisateur
    function deconnexion($messages){
        $_SESSION['connecte'] = false;
        $_SESSION['idutilisateur'] = 0;
        $messages['notification']['message'] = $this->traduction['nonconnecte'] ; // message à affiche en cas d’erreur/réussite
        $messages['notification']['niveau'] = '3' ; // niveau d’alerte (la couleur de l’alerte)
        $messages['notification']['redirect'] = 'login.php' ; // page suivante (seulement en html)
        $messages['notification']['redirect_time'] = 3 ; // temps du redirection (seulement en html)
        return $messages;
    }

    // recupere le profil via la session
    function charge_profil($messages,$utilisateur){
        $dbh = $this->dbh;
        $utilisateur = $dbh->quote($utilisateur);
        $query = 'select * from utilisateur where idutilisateur = '.$utilisateur.';';
        try {
            $res = $dbh->query($query);                
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $this->id_utilisateur = $row['idutilisateur'];
            $this->langue = $row['langue'];
            $this->token = $row['token'];
            $this->is_admin = $row['is_admin'];
            
            $res->closeCursor();
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        return $messages;
    }

    // recupere le profil via le token
    function charge_profil_token($messages,$token){
        
        $dbh = $this->dbh;
        $token = $dbh->quote($token);
        $query = "select idutilisateur from utilisateur where token = ".$token.";";        
        try {
            $res = $dbh->query($query);                
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $this->id_utilisateur = $row['idutilisateur'];            
            $res->closeCursor();
            $messages = $this->charge_profil($messages,$utilisateur);

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
        return $messages;
    }


    // cree un token d'identification valable quelques minutes
    function cree_Token($utilisateur){
        $dbh = $this->dbh;
        $utilisateur = $dbh->quote($utilisateur);
        $token = "PW4_".sha1(uniqid(mt_rand(), true));
        $token_quote = $dbh->quote($token);
        $query = "update utilisateur set token = ".$token_quote." where idutilisateur = ". $utilisateur;
        try {
            $res = $dbh->query($query);
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }

        return $token;

    }

    // donne l'id user
    function getIdUtilisateur(){
        return $this->id_utilisateur;
    }

    // donne la societe
    function getIdSociete(){
        return $this->id_societe;
    }

    // si l'utilisateur est admin de powow/marque blanche
    function is_admin(){
        return $this->is_admin;
    }

    // recupere les droits d'acces
    function liste_droits(){
        /* TODO */
    }

    // verifie si l'utilisateur a droit à la ressource
    function droit_ressource($nom_ressource){
        /* TODO */
    }
}
?>