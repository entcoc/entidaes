//Variable auxiliar para concatenar las entidades
var data=[];
//Inserción del primer dato
data.push({id:"0",name:"Estado Colombiano",collapse:false,parent:null,tDoc:"-1"});
//Layout del árbol
var tree;
//Nodos del árbol
var root;
//Ancho y alto del svg a dibujar
var w=0,h=0;
//Duration de las animaciones
var duration = 1000;