<?php
require_once '../api/Fonctions.php';
require_once '../theme_components/theme_style.php';
$ii=0;
$ui=0;

foreach (FC_Rechercher_Code('SELECT * FROM t_classeur ORDER BY Code_Classeur DESC') as $row3)
{if($ii%3==0){echo '<div class="row projects">';}
$ui++;
echo ' <div class="col-lg-4">
                <div class="hpanel " style="border-top: 2px solid '.$Panel_Item_Style.'">
                    <div class="panel-body">
                       ';
                        if(strstr($row3['Date_Insertion'], date('Y-m-d'))){echo '<span class="label '.$Label_Style.' pull-right">NEW</span>';}
                        echo '<div class="row" style="text-align: left">
                            <div class="col-sm-10">
                                <h4><a href="classeur_details.php?c='.base64_encode($row3['Code_Classeur']).'">'.$row3['Libelle_Classeur'].'</a></h4>

                                <p>'.$row3['Note_Classeur'].'</p>

                                <div class="row">
                                    
                                    <div class="col-sm-10">
                                        <div class="project-label"><small>Nombre de feuilles : </small>';
foreach (FC_Rechercher_Code('SELECT COUNT(*) AS NB FROM t_feuille WHERE Code_Classeur='.$row3['Code_Classeur']) as $row4)
{echo $row4['NB'];}
                                        echo '</div>
                                        
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="project-label"><div class="" style="background-color:'.$row3['Couleur_Classeur'].'; border-radius: 50%; width: 20px; height: 20px"></div></div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-sm-2 project-info">
                                <div class="project-action m-t-md">
                                    <div class="btn-group" style="font-size: 18px">
                                        <button class="btn btn-xs btn-default" onclick="Modifier_Classeur(\''.$row3['Code_Classeur'].'\')" id="" title="Modifier"><span class="nav-label glyphicon glyphicon-pencil text-info" ></span></button>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer"><a href="classeur_details.php?c='.base64_encode($row3['Code_Classeur']).'" >Ouvrir le classeur</a></div>
                </div>
            </div>';

if($ui%3==0){echo ' </div>'; $ui=0;}
 $ii++;
}
?>