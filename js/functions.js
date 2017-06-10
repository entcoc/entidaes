//Listado de ramas del poder público
var rama=[];
//Listado de caracter de las entidades
var caracter=[];
//Listado de niveles de las entidades
var nivel=["No asignado","Centralizado","Descentralizado","No aplica"];
//Listado de los ordenes de las entidades
var orden=["No asignado","Nacional","Territorial","No aplica"];
//Listado de los subordenes de las entidades
var suborden=["No asignado","Departamental","Distrital","Municipal","Organización Territorial","No aplica"];
//Variable auxiliar para concatenar las entidades
var data=[];
//Inserción del primer dato
data.push({id:"0",name:"Estado Colombiano",collapse:false,parent:null});
//Listado completo de entidades Públicas
var entidad=[];
//Listado de departamentos de Colombia
var departamento=[];
//Listado de municipios de Colombia
var municipio=[];
//Layout del árbol
var tree;
//Nodos del árbol
var root;
//Ancho y alto del svg a dibujar
var w=0,h=0;

/**
 * Determina si existe un objeto en un arreglo.
 * @param  {obj} obj Objeto a buscar.
 * @return {bool}     Retorna verdadero si se encontro.
 */
Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
        if (this[i] === obj) {
            return true;
        }
    }
    return false;
}

/**
 * Inicializa variables trayendo los datos de la base de datos.
 */
function init(){
	ramas();
	entidades();
}

/**
 * Trae todas las ramas de las entidades públicas y las agrega en la variable data.
 */
function ramas(){
	$.get("Administrador entidades/php/query.php",{q:"select * from rama where ?",v:1},function(d){
		d=JSON.parse(d);
		rama=rama.concat(d);
		for (var i = d.length - 1; i >= 0; i--) {
			if(d[i].id_ram>0)
			addData({id:"r"+d[i].id_ram,name:d[i].nom_ram,collapse:false,parent:0});
		}
	});
}

/**
 * Trae todas las entidades públicas y las agrega en la variable data.
 */
function entidades(){
	$.get("Administrador entidades/php/query.php",{q:"select count(id_ent) as total from entidad where ?",v:1},function(d){
		d=JSON.parse(d);
		d=parseInt(d[0].total);
		var cuan=5;
		window.limite=Math.ceil(d/cuan);
		for(var i=0;i<d;i+=cuan){
			entConLimite(i,cuan);
		}
	});
}
/**
 * Trae las entidades por paquetes.
 * @param  {int} offset Desde que entidad se debeconsultar.
 * @param  {int} cuan   Cuantas entidades se deben consultar.
 */
function entConLimite(offset,cuan){
	var q='select * from entidad where ? limit '+offset+","+cuan;
	$.get("Administrador entidades/php/query.php",{q:q,v:'1'},function(d){
		d=JSON.parse(d);
		d.sort(function(a,b){
			return (a.nom_ent.toLowerCase()<=b.nom_ent.toLowerCase())?-1:1;
		});
		entidad=entidad.concat(d);
		for (var i = d.length - 1; i >= 0; i--) {
			if(parseInt(d[i].par_ent))
				addData({id:"e"+d[i].id_ent,name:d[i].nom_ent,collapse:false,parent:"e"+d[i].par_ent});
			else
				addData({id:"e"+d[i].id_ent,name:d[i].nom_ent,collapse:false,parent:"r"+d[i].ram_ent});
		}
		$(document).trigger("carga");
	});
}

/**
 * Añade entidades a la variable data.
 * @param {obj} obj Entidad a insertar. Formato {id:$id,name:$nombre,parent:$parentID}.
 */
function addData(obj){
	if(!data.contains(obj)){
		data.push(obj);
	}
}

/**
 * Muestra en pantalla el árbol resultante.
 */
function armarArbol(){
	var scale=0.73;
	//Se crea la estructura de datos árbol.
	root=d3.stratify()
				.id(function(d){return d.id;})
				.parentId(function(d){return d.parent;})
				(data)
				.sort(function(a, b) {
			            return b.data.name.toLowerCase() < a.data.name.toLowerCase() ? 1 : -1;
			        });
	w=+$('#treeShow').width();
	h=+$('#treeShow').height($(document).height()-100).height();
	root.x0=h/2;
	root.y0=0;
	//Se crea el layout del arbol.
	tree=d3.tree().size([h,w]);
	
	//Funcion que detecta el scroll del mouse y el movimiento del svg, para que este sea movido y acercado o alejado según sea el caso.
	function zoomanddrag() {
        g.attr("transform", d3.event.transform);
    }

    //Se establecen los limites del zoom entre 0.1 mínimo y 3 máximo
    var zoomListener = d3.zoom().scaleExtent([0.1, 3]).on("zoom", zoomanddrag);
    window.milist=zoomListener;
    //Se crea el svg para empezar a dibujar, además se le agrega el listener del evento de zoom y drag
	var svg=d3.select('#treeShow')
				.append('svg')
				.attr('width',w)
				.attr('height',h)
				.call(zoomListener);
	//Se crea el grúpo principal de vectores para dibujar.
	var g=svg.append('g');
	
    /**
     * Función que centra la vista del svg en un nodo.
     * @param  {Node} source El nodo a centrar.
     */
    function centrarEntidad(n) {
    	var transform=d3.zoomTransform(svg);
    	var scale=transform.k;
        var x = -n.x0-500;
        var y = -n.y0;
        x = x * scale+w/2;
        y = y * scale+h/2;
        transform=transform.translate(x,y);
        svg.call(zoomListener.transform,transform);
    }

    /**
     * Contrae todos los descendientes de una entidad.
     * @param  {Node} d La entidad seleccionada
     */
    function collapse(d) {
        if (d.children) {
            d._children = d.children;
            d._children.forEach(collapse);
            d.children = null;
        }
    }

    /**
     * Expande todas las entidades descendientes.
     * @param  {Node} d La entidad a expandir
     */
    function expand(d) {
        if (d._children) {
            d.children = d._children;
            d.children.forEach(expand);
            d._children = null;
        }
    }

    /**
     * Expande o contrae la entidad seleccionada.
     * @param  {Node} d La entidad a aplicar la expanción o contracción.
     * @return {Node}   El nodo resultante.
     */
    function toggleChildren(d) {
        if (d.children) {
            d._children = d.children;
            d.children = null;
        } else if (d._children) {
            d.children = d._children;
            d._children = null;
        }
        return d;
    }

    /**
     * Expande o contrae la entidad a la cual se le haga click
     * @param  {Node} d La entidad a la cual dele hizo click.
     */
    function click(d) {
        if (d3.event.defaultPrevented) return; // click suppressed
        d = toggleChildren(d);
        pintarNodo(d);
        centrarEntidad(d);
    }

    function pintarNodo(nodo){
    	var maxLabelLength=0;
    	/**
	     * Visita todos los nodos.
	     * @param  {Node} parent     El padre de uun nodo
	     * @param  {function} visitFn    Función para reproducir al comienzo
	     * @param  {function} childrenFn Función para obtener hijos de un nodo
	     */
	    function visit(parent, visitFn, childrenFn) {
	        if (!parent) return;

	        visitFn(parent);

	        var children = childrenFn(parent);
	        if (children) {
	            var count = children.length;
	            for (var i = 0; i < count; i++) {
	                visit(children[i], visitFn, childrenFn);
	            }
	        }
	    }
	    var levelWidth = [1];
	    /**
	     * Determina el número máximo de hijos que se puede llegar a tener.
	     * @param  {int} level El número del nivel
	     * @param  {Node} n     El nodo a verificar
	     */
        var childCount = function(level, n) {

            if (n.children && n.children.length > 0) {
                if (levelWidth.length <= level + 1) levelWidth.push(0);

                levelWidth[level + 1] += n.children.length;
                n.children.forEach(function(d) {
                    childCount(level + 1, d);
                });
            }
        };

        /**
         * funcion que retorna un curva cubica
         */
        var diagonal = d3.linkHorizontal()
          .x(function(d) { return d.y; })
          .y(function(d) { return d.x; });

        childCount(0, root);
        var newHeight = d3.max(levelWidth) * 25; // 25 pixels por linea  
        tree = tree.size([newHeight,w]);
	    //Llamado de visit
	    visit(root, function(d) {
	        maxLabelLength = Math.max(d.data.name.length, maxLabelLength);

	    }, function(d) {
	        return d.children && d.children.length > 0 ? d.children : null;
	    });

	    var newRoot=tree(root),
	    entidades=newRoot.descendants(),
	    links=newRoot.links();

	    entidades.forEach(function(d){
	    	/*OJO posible d.x*/d.y=(d.depth*(maxLabelLength * 10));
	    });
	    
		var entidad = g.selectAll("g.entidad")
    				.data(entidades,function(d){
    					return d.id;
    				});
    	var entidadEnter=entidad.enter().append("g")
      				.attr("class", function(d) { return "entidad prof" +d.depth; })
      				.attr("transform", function(d) { return "translate(" + nodo.y0 + "," + nodo.x0 + ")"; })
      				.on('click',click);

  		entidadEnter.append("circle")
      		   .attr("r", 0);

  		entidadEnter.append("text")
      		   .attr("dy", ".35em")
      		   .attr("x", function(d) { return d.children || d._children ? -8 : 8; })
      		   .style("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      		   .text(function(d) { return $('<p>').html(d.data.name).text(); })
      		   .style("fill-opacity", 0);

      	entidad.select('text')
      		   .attr("x", function(d) { return d.children || d._children ? -8 : 8; })
      		   .style("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      		   .text(function(d) { return $('<p>').html(d.data.name).text(); });

      	entidad.select('circle')
      		   .attr('r',8);

      	var entidadActualizar=entidad.transition()
      				.duration(500)
      				.attr('transform',function(d){
      					return "translate("+d.y+","+d.x+")";
      				});
      	entidadActualizar.select("text")
      					 .style('fill-opacity',1);

      	var entidadExit=entidad.exit().transition()
      					.duration(500)
      					.attr("transform",function(d){
      						return "translate("+nodo.y+","+nodo.x+")";
      					})
      					.remove();

      	entidadExit.select('circle')
      			   .attr("r",0);

      	entidadExit.select("text")
      					 .style('fill-opacity',0); 

    	var ruta = g.selectAll("path.ruta")
    				.data(links,function(d){
    					return d.target.id;
    				});

    	ruta.enter().insert("path","g")
    				.attr('class',"ruta")
    				.attr("d", d3.linkHorizontal()
          			.x(function(d) { return nodo.x0; })
          			.y(function(d) { return nodo.y0; }));

        ruta.transition()
        	.duration(500)
        	.attr("d",diagonal);

        ruta.exit().transition()
        	.duration(500)
        	.attr("d", d3.linkHorizontal()
          	.x(function(d) { return nodo.x; })
          	.y(function(d) { return nodo.y; }))
          	.remove();
      	entidades.forEach(function(d) {
            d.x0 = d.x;
            d.y0 = d.y;
        });
    }
    var des=root.descendants();
    /*toggleChildren(des[1]);
    collapse(des[2]);
    toggleChildren(des[3]);
    toggleChildren(des[4]);
    toggleChildren(des[5]);
    toggleChildren(des[6]);
    toggleChildren(des[7]);
    toggleChildren(des[8]);
    toggleChildren(des[9]);*/
    var t=d3.zoomIdentity;
    t.k=scale;
    svg.call(zoomListener.transform,t);
    pintarNodo(root);
    centrarEntidad(root);
    function actualizarEntidad(source){
    	var maxLabelLength=0;
    	/**
	     * Visita todos los nodos.
	     * @param  {Node} parent     El padre de uun nodo
	     * @param  {function} visitFn    Función para reproducir al comienzo
	     * @param  {function} childrenFn Función para obtener hijos de un nodo
	     */
	    function visit(parent, visitFn, childrenFn) {
	        if (!parent) return;

	        visitFn(parent);

	        var children = childrenFn(parent);
	        if (children) {
	            var count = children.length;
	            for (var i = 0; i < count; i++) {
	                visit(children[i], visitFn, childrenFn);
	            }
	        }
	    }
	    var levelWidth = [1];
	    /**
	     * Determina el número máximo de hijos que se puede llegar a tener.
	     * @param  {int} level El número del nivel
	     * @param  {Node} n     El nodo a verificar
	     */
        var childCount = function(level, n) {

            if (n.children && n.children.length > 0) {
                if (levelWidth.length <= level + 1) levelWidth.push(0);

                levelWidth[level + 1] += n.children.length;
                n.children.forEach(function(d) {
                    childCount(level + 1, d);
                });
            }
        };

        /**
         * funcion que retorna un curva cubica
         */
        var diagonal = d3.linkHorizontal()
          .x(function(d) { return d.y; })
          .y(function(d) { return d.x; });

        childCount(0, root);
        var newHeight = d3.max(levelWidth) * 25; // 25 pixels por linea  
        tree = tree.size([w, newHeight]);
	    //Llamado de visit
	    visit(root, function(d) {
	        maxLabelLength = Math.max(d.data.name.length, maxLabelLength);

	    }, function(d) {
	        return d.children && d.children.length > 0 ? d.children : null;
	    });
    	var duration = d3.event && d3.event.altKey ? 5000 : 500;
    	//Computar el nuevo layout
    	tree(root);
    	var nodes=root.descendants();
    	nodes.forEach(function(d) {
            d.y = (d.depth * (maxLabelLength * 10)); //maxLabelLength * 10px
        });
        //Seleccionar los nodos actuales
        var node=svg.selectAll("g.entidad");
        var nodeEnter=node.enter().append('svg:g')
        .attr('class',function(d){return 'entidad prof'+d.depth})
		      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
		      .on("click", click);
		nodeEnter.append("svg:circle")
		      .attr("r", 1e-6);//radio 0
		nodeEnter.append("svg:text")
	      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
	      .attr("dy", ".35em")
	      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
	      .text(function(d) { return d.name; })
	      .style("fill-opacity", 1e-6);
	      	/**
	      	 * Parar y continuar aquí
	      	 */

		  //Reposicionar los nodos antiguos
		  var nodeUpdate = node.transition()
		      .duration(duration)
		      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

		  nodeUpdate.select("circle")
		      .attr("r", 4.5)
		      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

		  nodeUpdate.select("text")
		      .style("fill-opacity", 1);

		  // Borrar los nodos hijos que han sido contraidos
		  var nodeExit = node.exit().transition()
		      .duration(duration)
		      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
		      .remove();

		  nodeExit.select("circle")
		      .attr("r", 1e-6);

		  nodeExit.select("text")
		      .style("fill-opacity", 1e-6);

		  // Actualizar rutas
		  var link = svg.selectAll("path.ruta")
		      .data(nodes.links(), function(d) { return d.target.id; });

		  // Nuevos Links
		  link.enter().insert("svg:path", "g")
		      .attr("class", "ruta")
		      .attr("d", function(d) {
		        var o = {x: source.x0, y: source.y0};
		        return diagonal({source: o, target: o});
		      })
		    .transition()
		      .duration(duration)
		      .attr("d", diagonal);

		  // Transition links to their new position.
		  link.transition()
		      .duration(duration)
		      .attr("d", diagonal);

		  // Transition exiting nodes to the parent's new position.
		  link.exit().transition()
		      .duration(duration)
		      .attr("d", function(d) {
		        var o = {x: source.x, y: source.y};
		        return diagonal({source: o, target: o});
		      })
		      .remove();

		  // Stash the old positions for transition.
		  nodes.forEach(function(d) {
		    d.x0 = d.x;
		    d.y0 = d.y;
		  });
    }
}
init();
window.carga=0;
$(document).ready(function(){
	$(document).on("cargaCompleta",armarArbol);
	$(document).on("carga",function(){
		window.carga=window.carga+1;
		if(window.carga==window.limite){
			$(document).trigger('cargaCompleta');
		}
	});
});
$(window).resize(function(){
	w=+$('#treeShow').width();
	h=+$('#treeShow').height();
	$('#treeShow svg').eq(0).width(w)
							.height(h);
	tree.size([h,w]);
	armarArbol();
});