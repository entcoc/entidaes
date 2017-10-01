<?php 
function descendientes($arbol, $st, $id, $object=false, $firstLevel=false)
{
	if($st->execute(array($id))){
		while ($row = $st->fetch(PDO::FETCH_ASSOC))
		{
			$insert=$row;
			if($object)
				$insert=(object)$row;
			array_push($arbol,$insert);
			if(!$firstLevel)
				$arbol=descendientes($arbol,$st,$row['id_ent']);
		}
		return $arbol;
	}
	return $arbol;
}
function ascendientes($arbol, $st, $id, $object=false, $firstLevel=false){
	if($st->execute(array($id))){
		if($row = $st->fetch(PDO::FETCH_ASSOC))
		{
			$insert=$row;
			if($object)
				$insert=(object)$row;
			array_unshift($arbol,$insert);
			if(!$firstLevel){
				$arbol=ascendientes($arbol,$st,$row['par_ent'],$object,$firstLevel);
			}
		}
		return $arbol;
	}
	return $arbol;
}
function contiene($array,$id){
	for ($i=0; $i < count($array) ; $i++) { 
		if($array[$i]==$id)
			return true;
	}
	return false;
}
?>