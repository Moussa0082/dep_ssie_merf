<?php

    $path = (isset($path))?$path:'./';

    include_once $path.'system/configuration.php';

    $config = new Config;



    $config->host = "localhost";

    $config->user = "root";
    // $config->user = "c0ssi4627";

    $config->password = '';
    // $config->password = 'B$Ji*mWyKfln_';

    $config->db_name = "c0ssi4627";

    $config->db_prefix = "";

    try

    {

        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        $pdo_options[PDO::ATTR_PERSISTENT] = true;

        $db = new PDO('mysql:host='.$config->host.';dbname='.$config->db_name.'', $config->user, $config->password, $pdo_options);

        $db->exec('SET CHARACTER SET utf8');



        $hostname_connect_transfert = $hostname_pdar_connexion = $config->host;

        $database_connect_transfert = $database_pdar_connexion = $config->db_name;

        $database_connect_prefix = $database_pdar_prefix = $config->db_prefix;

        $username_connect_transfert = $username_pdar_connexion = $config->user;

        $password_connect_transfert = $password_pdar_connexion = $config->password;

        $connect_transfert = $pdar_connexion = $db;

        //mysql_pconnect($hostname_connect_transfert, $username_connect_transfert, $password_connect_transfert) or trigger_error(mysql_error(),E_USER_ERROR);



        include_once $path.$config->sys_folder."/essentiel.php";

    }

    catch (Exception $e)

    {

        die('Erreur : ' . $e->getMessage());

        $db->CloseCursor();

    }

?>

