<?php
    $host = "localhost";
    $user = "c0ssi4627";
    $password = 'B$Ji*mWyKfln_';
    $db_name = "c0_dep";
    try
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $db = new PDO('mysql:host='.$host.';dbname='.$db_name.'', $user, $password, $pdo_options);
        $db->exec('SET CHARACTER SET utf8');
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
        $db->CloseCursor();
    }
    function Charger_db()
    {
        $db_name='c0_dep';
        $host='localhost';
        $user='c0ssi4627';
        $password='B$Ji*mWyKfln_';
        try{$Connexion=new PDO('mysql:host='.$host.';dbname='.$db_name,$user,$password,array(PDO::ATTR_PERSISTENT=>true));$Connexion->exec('SET CHARACTER SET utf8');return $Connexion;}
        catch (Exception $e)
        {return null;}
    }
?>
