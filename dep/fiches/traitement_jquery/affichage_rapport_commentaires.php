<?php 
if(isset($_GET['Code_Rapport']) AND !empty($_GET['Code_Rapport']))	
{extract($_GET);
	require_once '../api/Fonctions.php';
	?>
<center>
<div class="content">

<div class="row social-board">
    <?php 
foreach (FC_Rechercher_Code('SELECT * , CONCAT(DAY(t_rapport_article.Date_Insertion), \'.\', MONTH(t_rapport_article.Date_Insertion), \'.\' , YEAR(t_rapport_article.Date_Insertion)) AS Date_Publication FROM t_rapport_article INNER JOIN t_rapport_indicateur ON (t_rapport_indicateur.Code_Rapport=t_rapport_article.Code_Rapport) WHERE (t_rapport_indicateur.Affichage=\'Tous\' AND t_rapport_article.Code_Article='.$Code_Rapport.') ORDER BY t_rapport_article.Date_Insertion DESC') as $row9)
    {
        echo '    <div class="col-lg-12">
        <div class="hpanel hblue">
            <div class="panel-body">
                <div class="media social-profile clearfix">
                    <a class="pull-left">';
foreach (FC_Rechercher_Code('SELECT * FROM t_users INNER JOIN t_programmes ON(t_users.programme_active=t_programmes.id_programme) WHERE login=\''.$row9['Login'].'\'') as $row10)
    {$dir = '../images/avatar/'; if(file_exists($dir."profil_".$row10["id_user"].'.jpg')){ ?>
                        <img src="<?php echo "./images/avatar/profil_".$row10["id_user"].'.jpg'; ?>" alt="photo de profil">
<?php }                       
else {echo '<img src="./images/avatar/user.png" alt="photo de profil">'; }
 }                        
echo '</a><div class="media-body"><h5>';
foreach (FC_Rechercher_Code('SELECT * FROM t_users INNER JOIN t_programmes ON(t_users.programme_active=t_programmes.id_programme) WHERE login=\''.$row9['Login'].'\'') as $row10)
    {echo ucfirst($row10['prenom']).' '.strtoupper($row10['nom']).' : '.$row10['sigle_programme'];}
                echo '</h5>

                        <small class="text-muted">'.$row9['Date_Publication'].'</small>
                            <br>
                        <span class="nav-label glyphicon glyphicon-eye-open text-default" style="padding:2px"><small class="text-muted" style="margin-left:2px">'.number_format($row9['Vue'],0, '',' ').'</small></span>
        
                

                    </div>
                </div>
                <p><img src="./images/'.$row9['Photo'].'" width="90%" height="90%" alt="..."></p>
                <div class="social-content m-t-md">
                    <h4>'.htmlspecialchars_decode($row9['Titre_Article']).'</h4>'.htmlspecialchars_decode($row9['Description_Article']).'
                </div>
            </div>
            <div class="panel-footer">';
$compte=0;
$Res4 = FC_Rechercher_Code('SELECT *, CONCAT(DAY(Date_Insertion), \'.\', MONTH(Date_Insertion), \'.\' , YEAR(Date_Insertion), \' \', TIME(Date_Insertion)) AS Date_Publication FROM t_rapport_commentaire WHERE t_rapport_commentaire.Code_Article='.$row9['Code_Article'].' ORDER BY Date_Insertion DESC');
if($Res4!=null AND $Res4->rowCount()>=1){
    echo '<div><details><summary style="cursor:pointer; text-decoration:underline">Voir les commentaires ('.$Res4->rowCount().')</summary>';
foreach ($Res4 as $row11)
              { $compte++;
                echo ' <div class="social-talk">
                    <div class="media social-profile clearfix">
                       <div class="media-body">
                            <span class="font-bold">Commentaire '.$compte.'</span> : 
                            <small class="text-muted">'.$row11['Date_Publication'].'</small>

                            <button class="btn btn-xs btn-default" id="" onclick="Supprimer_Commentaire(\''.$row11['Code_Commentaire'].'\')" style="float:right" title="Rapport"><span class="nav-label glyphicon glyphicon-trash text-danger" ></span></button>
          
                            <div class="social-content">
                                ';

                                if($row11['Photo']==""){echo htmlspecialchars_decode($row11['Commentaire']);}
                            else {echo '<div class="col-lg-12"><img src="./images/'.$row11['Photo'].'" style="width:90%!important; height:90%"  alt="..."></div>';}

                                echo '
                            
                            </div>
                        </div>
                    </div>
                </div>';}
                echo "</details></div>";

            }

                echo '
            </div>
        </div>
    </div>';
    }

     ?>

   
</div>

</div>

</center>

<?php } ?>
 
