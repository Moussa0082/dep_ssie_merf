<?php 

$ACCESS="N";

if($_POST)
{ extract($_POST);
	$ind = array("'", "<", ">", " ", "--", "/", '"', "%", "$", "*");
	if((isset($Login) AND !empty($Login)) AND (isset($Pass) AND !empty($Pass)))
	{
		$Login=str_replace($ind, "", $Login);
		$Pass=str_replace($ind, "", $Pass);
		foreach (FC_Rechercher_Code("SELECT * FROM personnel WHERE (id_personnel='".$Login."' AND pass='".md5(htmlentities($Pass))."')") as $row1111)
			{$ACCESS="O";}
	}
}

 ?>