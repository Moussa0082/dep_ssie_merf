
<div class="row">
    <?php 
    $ind=0;
    $ind2=0;
    foreach (FC_Rechercher_Code("SELECT * FROM t_rapport_indicateur WHERE Affichage LIKE 'Tous'") as $row44) 
    {$ind++;
        echo '<div class="col-md-4 col-lg-4">
                <div class="hpanel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-tools">
                            <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                            <a class="closebox"><i class="fa fa-times"></i></a>
                        </div>';

    foreach (FC_Rechercher_Code('SELECT `code_ref_ind` AS code, `intitule_ref_ind` AS intitule FROM referentiel_indicateur WHERE (code_ref_ind=\''.$row44["Indicateur"].'\')') as $row45)
{echo '<span class="text-primary">'.$row45['intitule'].'</span>';}  
 echo '</div><div class="panel-body">';

 if(!empty($row44["Group_By"]))
 {$Total=0;

   $ind2=0;
   echo '<table cellpadding="1" cellspacing="1" class="table contenu">';
   $Res2=FC_Rechercher_Code('SELECT * FROM '.$row44["Nom_View"]);
   if($Res2!=null){    foreach ($Res2 as $row48)
    {$ind2++;
    if(($ind2%2)==0){echo "<tr>";}
     else{echo '<tr style=" background-color:beige;">';}
      echo "<td>".$row48[0]."</td>"; echo "<td>".number_format($row48[1],0, '',' ')."</td>"; echo "</tr>";
    $Total+=$row48[1];
    //echo '<strong>'.number_format($row48[1],0, '',' ').'</strong>';
   }}
    echo '<tr style=" background-color:#F1F3F6; font-size:18px">'; echo "<td>Total</td>"; echo "<td>".number_format($Total,0, '',' ')."</td>"; echo "</tr>";
   echo '</table>';
        
 }
 else
 {    $Res3=FC_Rechercher_Code('SELECT * FROM '.$row44["Nom_View"]); 
    if($Res3!=null){foreach ($Res3 as $row46)
        {echo '<strong>'.number_format($row46[0],0, '',' ').'</strong>';}}
    

 }


 echo '</div></div></div>';

    }
     ?>
            
                    
                        
                    


</div>



