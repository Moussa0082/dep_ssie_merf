<?php
if(!isset($_SESSION)) session_start();
if(!isset($config)) include_once 'api/configuration.php';
if(!isset($config)) $config = new Config;
require_once 'api/Fonctions.php';
require_once 'theme_components/theme_style.php';

function check_menu_showing($nfile,$MENU,$page_principal)
{
    $j = 0; foreach($MENU as $a => $b) { //if(is_array($b)) $tmp = array_values($b);
        if((is_array($page_principal) && !in_array($a,$page_principal) && !empty($b)) || !is_array($page_principal)) $j++;
    }
    return $j==0?false:true;
}
function check_menuT_showing($nfile,$MENU)
{
    $j = 0; foreach($MENU as $a => $b) { if(is_array($b)){ foreach($b as $a => $b){ if(strcmp($nfile,$a)==0) $j++; } }else{ if(strcmp($nfile,$a)==0) $j++; }
    }
    return $j==0?false:true;
}
function checkIfTimedOut()
{
    $config = new Config;
    $current = time();// take the current time
    $diff = $current - $_SESSION['loggedAt'];
    if($diff > $config->maxtime)
    {
    return true;
    }
    else
    {
    return false;
    $_SESSION['loggedAt']= time();// update last accessed time
    }
}
//Auto logout function
//Fin

/*if(isset($_SESSION["id"]))
{
    //Initialisation des droits s'accès
    require_once $path.'api/db.php';
    $query_auth = $db->query("SELECT * FROM t_users_access WHERE id_user=".$_SESSION["id"]);
    $row_auth = $query_auth ->fetch();
    $totalRows_auth = $query_auth->rowCount();
    if($totalRows_auth>0)
    {
        $_SESSION["page_edit"] = $row_auth["page_edit"];
        $_SESSION["page_verif"] = $row_auth["page_verif"];
        $_SESSION["page_valid"] = $row_auth["page_valid"];
        $_SESSION["page_interd"] = $row_auth["page_interd"];
    }
    else
    {
        $_SESSION["page_edit"] = "";
        $_SESSION["page_verif"] = "";
        $_SESSION["page_valid"] = "";
        $_SESSION["page_interd"] = "";
    }
    //Authorisation
    $page = explode('|',$_SESSION["page_interd"]); $page_principal = $page;
    if(in_array($nfile,$page))
    {
        if(!headers_sent())
        {
            header(sprintf("Location: %s", "./unauthorize.php?page=".$nfile));
            exit;
        }
        else
        {
            ?>

            <?php
        }
    }
}*/
?>
<!-- Header -->




        
                <?php include_once 'theme_components/online_users.php'; ?>
                
<!-- Right sidebar -->

<script>
$().ready(function() {
$('#filter_projet').bind('keyup', function() {filtre_ul_li('liste_projet_filter',$(this).val());});
$('#filter_projet1').bind('keyup', function() {filtre_ul_li('liste_projet_filter1',$(this).val());});
});
</script>