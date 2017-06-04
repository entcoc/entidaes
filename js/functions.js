var rama=[];
var caracter=[];
var nivel=["No asignado","Centralizado","Descentralizado","No aplica"];
var orden=["No asignado","Nacional","Territorial","No aplica"];
var suborden=["No asignado","Departamental","Distrital","Municipal","Organización Territorial","No aplica"];
var data=[];
data.push({id:"0",name:"Estado Colombiano",parent:null});
var entidad=[];
var departamento=[];
var municipio=[];
init();
Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}
function init(){
	ramas();
	//entidades();
}
function ramas(){
	$.get("PHP/rama.php",function(d){
		rama=rama.concat(d);
		for (var i = d.length - 1; i >= 0; i--) {
			if(d[i].id_ram>0)
			addData({id:""+d[i].id_ram,name:d[i].nom_ram,parent:0});
		}
		console.log(rama);
		console.log(data);
	});
}
function entidades(){
	$.get("PHP/entidad.php",function(d){
		entidades=entidades.concat(d);
		for (var i = d.length - 1; i >= 0; i--) {
			addData({id:(d[i].ram_ent+"_"+d[i].ord_ent),name:orden[d[i].ord_ent],parent:d[i].ram_ent});
			addData({id:(d[i].ram_ent+"_"+d[i].ord_ent+"_"+d[i].subord_ent),name:suborden[d[i].subord_ent],parent:(d[i].ram_ent+"_"+d[i].ord_ent)});
			addData({id:(d[i].ram_ent+"_"+d[i].ord_ent+"_"+d[i].subord_ent+"_"+d[i].niv_ent),name:nivel[d[i].niv_ent],parent:(d[i].ram_ent+"_"+d[i].ord_ent+"_"+d[i].subord_ent)});
			addData({id:(d[i].ram_ent+"_"+d[i].ord_ent+"_"+d[i].subord_ent+"_"+d[i].niv_ent+"_"+d[i].id_ent),name:d[i].nom_ent,parent:(d[i].ram_ent+"_"+d[i].ord_ent+"_"+d[i].subord_ent+"_"+d[i].niv_ent)});
		}
	});
}
function addData(obj){
	if(!data.contains(obj)){
		data.push(obj);
	}
}