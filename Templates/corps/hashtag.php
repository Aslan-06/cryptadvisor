
<?php 
require_once "./connexion.php";

$array = array();
$responseCode = 500; /* si la requête est bien en Ajax et la méthode en GET ... */

    if((strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') && ($_SERVER['REQUEST_METHOD'] == 'GET')){
        /* on récupère le terme et on le duplique en terme en transformant les espaces en tirets et tirets en espaces (au cas ou) */
        $q = str_replace("''","'",urldecode($_REQUEST['name']));
        $q = strtolower(str_replace("'","''",$q));
        $qTiret = str_replace(' ','-',$q);
        $qSpace = str_replace('-',' ',$q);
        $array = array();

        /* creation de la requête SQL */
        $query=self::$bdd->prepare('SELECT ht.nom FROM Hashtag as ht WHERE (ht.nom LIKE '%'.$q.'%' OR ht.nom LIKE '%'.$qTiret.'%' OR ht.nom LIKE '%'.$qSpace.'%') ORDER BY ht.nom ASC');
        $query->setFetchMode(PDO::FETCH_OBJ);

        /* remplissage du tableau avec les termes récupéré en requete (ou non) */
        while($q = $query->fetch()){
        $nom = utf8_encode($q->name);
        $array[] = array( 'id' => $q->id, 'label' => $nom.' ('.$idCours.')', 'value' => $nom.' ('.$idCours.')', );
        }

        $query->closeCursor();
        //die(print_r($array));
        $responseCode = 200; 
    }

    /* génération réponse JSON */
    http_response_code($responseCode);
    header('Content-Type: application/json');
    echo json_encode($array); 
?>