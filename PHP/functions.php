<?php 
function descendientes($arbol, $st, $id)
{
	if($st->execute(array($id))){
		while ($row = $st->fetch(PDO::FETCH_ASSOC))
		{
			array_push($arbol,$row);
			$arbol=descendientes($arbol,$st,$row['id_ent']);
		}
		return $arbol;
	}
	return $arbol;
}
function ascendientes($arbol, $st, $id){
	if($st->execute(array($id))){
		if($row = $st->fetch(PDO::FETCH_ASSOC))
		{
			array_push($arbol,$row);
			$arbol=ascendientes($arbol,$st,$row['par_ent']);
		}
		return $arbol;
	}
	return $arbol;
}
?>