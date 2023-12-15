<?php
require_once 'api/db.php';
$query_theme = $db->query("SELECT * FROM t_style GROUP BY Style_Index");
$row_theme = $query_theme ->fetchAll();
$totalRows_theme = $query_theme->rowCount();
if($totalRows_theme>0)
{ ?>
<table style="margin: 30px 0 0 30px;">
    <tbody>
<?php $i=$j=0; foreach($row_theme as $row_theme){ echo $i==0?"<tr>":""; ?>
        <td>
            <a href="#" onclick="Changer_Theme(<?php echo $row_theme["Style_Index"]; ?>)" class="theme_radio_btn" style="<?php echo $row_theme["Style_Par_Defaut"]==1?'background-color:yellow;':''; ?>">
                <div style="width: 50%; height: 20px;" class="btn btn<?php echo substr($row_theme["Valeur_Style"],5); ?> "></div>
                <i class="text-info"></i>
                <h5>Thème <?php echo $row_theme["Style_Index"]; ?></h5>
            </a>
        </td>
<?php $i++; $j++; if($j>=$totalRows_theme) $j=0; echo $i%3==0?"</tr><tr>":""; } echo "</tr>"; ?>
    </tbody>
</table>
<?php } ?>