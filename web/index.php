<?php


    //Démarrage de la session de l'internaute
    session_start();

    //IMPORTS
    
    require_once '../src/lib/vendor/autoload.php';
    require_once '../src/config/parametres.php';
    require_once '../src/app/connexion.php';
    require_once '../src/config/routing.php';
    require_once '../src/controleur/_controleurs.php';
    require_once '../src/modele/_classes.php';
    $loader = new Twig_Loader_Filesystem('../src/vue/');
    $twig = new Twig_Environment($loader, array());
    $twig->addGlobal('session', $_SESSION);
    $twig->addGlobal('get', $_GET);
    //$db = connect($config); 
    // Exécution de la fonction souhaitée
    //$contenu($twig,$db);
    //Connexion à la BDD
    $db = connect($config);
    
    $contenu = getPage($db);
    $contenu($twig,$db);

?>
