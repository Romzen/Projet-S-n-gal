<?php
    function connect($config)
    {
        try
        {
                $db = NEW PDO('mysql:host='.$config['serveur'].'; dbname='.$config['bd'], $config['login'], $config['mdp']);
        }
        catch (EXCEPTION $e)
        {
                echo 'ECHEC '.$e->getMessage();
                $db = NULL;	
        }
        return $db;
    }
?>
