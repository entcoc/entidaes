(function (window, document) {

})(window, document);
function encode_utf8(s) {
	return unescape(encodeURIComponent(s));
}
function decode_utf8(s) {
	return decodeURIComponent(escape(s));
}
function simple(table,id,suf){
	var q='select * from '+table+' where ?';
	$.get("php/query.php",{q:q,v:'1'},function(d){
		d=JSON.parse(d);
		d.sort(function(a,b){
			return (a['nom_'+suf]<=b['nom_'+suf])?-1:1;
		});
		var sel=$('#'+id);
		for(var i=0;i<d.length;i++){
			if(d[i]['id_'+suf]>0)
				sel.append('<option value='+d[i]['id_'+suf]+'>'+d[i]['nom_'+suf]+'</option>');
			else
				sel.prepend('<option value='+d[i]['id_'+suf]+' selected>'+d[i]['nom_'+suf]+'</option>');

		}
	});
}
function municipio(){
	var q='select * from municipio where ?';
	$.get("php/query.php",{q:q,v:'1'},function(d){
		d=JSON.parse(d);
		d.sort(function(a,b){
			return (a.nom_mun>=b.nom_mun);
		});
		var sel=$('#mun_ent');
		for(var i=0;i<d.length;i++){
			if(d[i].id_mun>0)
				sel.append('<option style="display:none;" data-dep='+d[i].dep_mun+' value='+d[i].id_mun+'>'+d[i].nom_mun+'</option>');
			else
				sel.prepend('<option style="display:none;" data-dep='+d[i].dep_mun+' value='+d[i].id_mun+' selected>'+d[i].nom_mun+'</option>');

		}
	});
}