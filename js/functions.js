/**
 * Determina si existe un objeto en un arreglo.
 * @param  {obj} obj Objeto a buscar.
 * @return {bool}     Retorna verdadero si se encontro.
 */
Array.prototype.contains = function(obj) {
    var i = this.length;
    while (i--) {
      var comp=this[i]; 
      if (comp.id == obj.id) {
          return true;
      }
    }
    return false;
}

/**
 * Inicializa variables trayendo los datos de la base de datos.
 */
function init(){
	entidades();
  ramas();
  armarArbol();
}
/**
 * Trae todas las ramas de las entidades públicas y las agrega en la variable data.
 */
function ramas(){
	for (var i = rama.length - 1; i >= 0; i--) {
		if(rama[i].id_ram>0)
		  addData({id:"r"+rama[i].id_ram,name:rama[i].nom_ram,collapse:false,parent:0,tDoc:"-1"});
	}
}

/**
 * Trae todas las entidades públicas y las agrega en la variable data.
 */
function entidades(){
	for (var i = entidad.length - 1; i >= 0; i--) {
    if(parseInt(entidad[i].par_ent))
      addData({id:"e"+entidad[i].id_ent,name:entidad[i].nom_ent,collapse:false,parent:"e"+entidad[i].par_ent,tDoc:entidad[i].tdoc_ent});
    else
      addData({id:"e"+entidad[i].id_ent,name:entidad[i].nom_ent,collapse:false,parent:"r"+entidad[i].ram_ent,tDoc:entidad[i].tdoc_ent});
  }
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
        scale=d3.event.transform.k;
        g.attr("transform", d3.event.transform);
    }

    //Se establecen los limites del zoom entre 0.1 mínimo y 3 máximo
    var zoomListener = d3.zoom().scaleExtent([0.1, 3]).on("zoom", zoomanddrag);
    window.milist=zoomListener;
    //Se crea el svg para empezar a dibujar, además se le agrega el listener del evento de zoom y drag
    d3.select('svg').remove();
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
    	var transform=d3.zoomTransform(g.node());
        var x = -n.y0;
        var y = -n.x0;
        x = x*scale +w/2;
        y = y*scale +h/2;
        transform=transform.translate(x,y);
        d3.select('g').transition()
          .duration(duration)
          .attr('transform',transform)
          .on('end',function(){
            svg.call(zoomListener.transform,transform);
          });
    }

    /**
     * Contrae todos los descendientes de una entidad.
     * @param  {Node} d La entidad seleccionada
     */
    function collapse(d) {
        if (d && d.children) {
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
        if (d && d.children) {
            /*d._children = d.children;
            d.children = null;*/
            collapse(d);
        } else if (d && d._children) {
            d.children = d._children;
            d._children = null;
        }
        return d;
    }

    /**
     * Expande o contrae la entidad a la cual se le haga click
     * @param  {Node} d La entidad a la cual se hizo click.
     */
    function click(d) {
        if (d3.event.defaultPrevented) return; // click suppressed
        d = toggleChildren(d);
        pintarNodo(d);
        //centrarEntidad(d);
    }
    /**
     * Detecta si el mouse está sobre una entidad
     * @param  {Node} d La entidad actual.
     */
    function circHover(d) {
        var circle=$(this).siblings().css({fill:"red"});
    }
    /**
     * Detecta si el mouse está por fuera de una entidad
     * @param  {Node} d La entidad actual.
     */
    function circOut(d) {
        var circle=$(this).siblings().css({fill:""});
    }
    function clickEnText(d){
      if(d.data.tDoc!="-1"){
        location.href="entidad.php?ent="+d.id.slice(1);
      }
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
        var newHeight = d3.max(levelWidth) * 75; // 75 pixels por linea  
        tree = tree.size([newHeight,w]);
	    //Llamado de visit
	    visit(root, function(d) {
        var name=$('<p>').html(d.data.name).text();
        if(name.length>40)
          name=name.substr(0,40)+"...";
	      maxLabelLength = Math.max(name.length, maxLabelLength);

	    }, function(d) {
	        return d.children && d.children.length > 0 ? d.children : null;
	    });

	    var newRoot=tree(root),
	    entidades=newRoot.descendants();

	    entidades.forEach(function(d){
	    	/*OJO posible d.x*/d.y=(d.depth*(maxLabelLength*15));
	    });


      var transit=d3.transition()
        .duration(duration);

      //Actualizar lista de entidades a mostrar
      var entidad=g.selectAll("g.entidad")
        .data(entidades,function(d){
          return d.id;
        });

      //Agragar entidades expandidads
      var expandidas=entidad.enter().append("g")
        .attr("class",function(d){
          return "entidad prof"+d.depth;
        })
        .attr("transform",function(d){
          return "translate("+nodo.y0+","+nodo.x0+")";
        });

      expandidas.append("text")
        .on("click",clickEnText)
        .attr("x",function(d){
          return d.children || d._children ? -15 : 15;
        })
        .style("text-anchor",function(d){
          return d.children || d._children ? "end" : "start";
        })
        .text(function(d){
          var name=$('<p>').html(d.data.name).text();
          if(name.length>40)
            name=name.substr(0,40)+"...";
          return name;
        })
        .style("fill-opacity",0)
        .transition(transit)
        .style("fill-opacity",1);

      var circle=expandidas.append("g")
        .on("click",click);
      circle.append("circle")
        .attr("r",0)
        .transition(transit)
        .attr("r",15);
      circle.append("text")
        .text(function(d){
          var t="";
          if(d._children)
            t="+";
          if(d.children)
            t="-";
          return t;
        })
        .on("mouseover",circHover)
        .on("mouseout",circOut)
        .attr("text-anchor","middle")
        .attr("font-size",20)
        .attr("dy",6)
        .attr("fill","white")
        .style("fill-opacity",0)
        .transition(transit)
        .style("fill-opacity",1);

      expandidas.transition(transit)
        .attr("transform",function(d){
          return "translate("+d.y+","+d.x+")";
        });

      var contraidas=entidad.exit().transition(transit)
        .attr("transform",function(d){
          return "translate("+nodo.y+","+nodo.x+")";
        })
        .remove();

      contraidas.select("text")
        .style("fill-opacity",0);

      contraidas.select("g text")
        .style("fill-opacity",0);

      contraidas.select("g circle")
        .attr("r",0);

      //Actualizar posición entidades
      entidad.transition(transit)
        .attr("transform",function(d){
          return "translate("+d.y+","+d.x+")";
        })
        .selectAll(".entidad g text")
        .text(function(d){
          var t="";
          if(d._children)
            t="+";
          if(d.children)
            t="-";
          return t;
        });

      var links=newRoot.links();
      
      //Actualizar lista de rutas entre entidades
      var link=g.selectAll("path.ruta")
        .data(links,function(d){
          return d.target.id;
        });

      //Links de entidades expandidads
      var lExpand=link.enter().insert("path","g")
        .attr("class","ruta")
        .attr("d",d3.linkHorizontal()
                  .x(function(d){
                    return nodo.y0
                  })
                  .y(function(d){
                    return nodo.x0
                  }));

      lExpand.transition(transit)
        .attr("d",diagonal);

      //Links de entidades contraidas
      var lContra=link.exit()
        .transition(transit)
        .attr("d",d3.linkHorizontal()
                  .x(function(d){
                    return nodo.y
                  })
                  .y(function(d){
                    return nodo.x
                  }))
        .remove();

      //Actualizar links de entidades no expandidas ni contraidas
      link.transition(transit)
        .attr("d",diagonal);

      entidades.forEach(function(d) {
          d.x0 = d.x;
          d.y0 = d.y;
      });

      centrarEntidad(nodo);
    }
    var des=root.descendants();
    collapse(des[1]);
    collapse(des[2]);
    collapse(des[3]);
    collapse(des[4]);
    collapse(des[5]);
    collapse(des[6]);
    collapse(des[7]);
    collapse(des[8]);
    collapse(des[9]);
    var t=d3.zoomIdentity;
    t.k=scale;
    svg.call(zoomListener.transform,t);
    pintarNodo(root);
}
window.carga=0;
$(document).ready(function(){
	$(document).on("cargaCompleta",armarArbol);
	$(document).on("carga",function(){
		window.carga=window.carga+1;
		if(window.carga==window.limite){
			$(document).trigger('cargaCompleta');
		}
	});
  $("#sel_ram").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro ram">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_car").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro car">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_ord").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro ord">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_niv").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro niv">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_subord").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro subord">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_dep").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro dep">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
  $("#sel_mun").selectmenu({width:200,change:function(event,ui){
    $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ui.item.value+'" class="filtro mun">'+ui.item.label+'</div>')
  }}).selectmenu("menuWidget").addClass("overflow");
});
$(window).resize(function(){
  w=+$('#treeShow').width();
  h=+$('#treeShow').height();
  $('#treeShow svg').eq(0).width(w)
              .height(h);
  tree.size([h,w]);
  armarArbol();
});
init();
/**
 * Establece los filtros para ser subidos al servidor y de esta manera filtrar las entidades correspondientes.
 */
function cargarfiltros(){
  $('[name=coin]').val(+$('#coin').prop("checked"));
  $('.ram').each(function(i,item){
    var ram=$('[name=ram]');
    var val=ram.val();
    if(i) ram.val(val+","+$(item).data("id"));
    else ram.val($(item).data('id'));
  });
  $('.car').each(function(i,item){
    var car=$('[name=car]');
    var val=car.val();
    if(i) car.val(val+","+$(item).data("id"));
    else car.val($(item).data('id'));
  });
  $('.ord').each(function(i,item){
    var ord=$('[name=ord]');
    var val=ord.val();
    if(i) ord.val(val+","+$(item).data("id"));
    else ord.val($(item).data('id'));
  });
  $('.niv').each(function(i,item){
    var niv=$('[name=niv]');
    var val=niv.val();
    if(i) niv.val(val+","+$(item).data("id"));
    else niv.val($(item).data('id'));
  });
  $('.subord').each(function(i,item){
    var subord=$('[name=subord]');
    var val=subord.val();
    if(i) subord.val(val+","+$(item).data("id"));
    else subord.val($(item).data('id'));
  });
  $('.dep').each(function(i,item){
    var dep=$('[name=dep]');
    var val=dep.val();
    if(i) dep.val(val+","+$(item).data("id"));
    else dep.val($(item).data('id'));
  });
  $('.mun').each(function(i,item){
    var mun=$('[name=mun]');
    var val=mun.val();
    if(i) mun.val(val+","+$(item).data("id"));
    else mun.val($(item).data('id'));
  });
}
/**
 * Elimina un filtro, esta función es activada al hacer click en uno de los filtros seleccionados por el usuario.
 *
 * @param      {object}  e       El objeto html a ser eliminado.
 */
function eliminar(e){
  $(e).animate({opacity:0},500,'swing',function(){
    $(e).remove();
  });
}
/**
 * Esta función ayuda a conservar los filtros seleccionados en busquedas anteriores.
 *
 * @param      {string}  ids     Los ids correspondientes de los filtroas en la base de datos.
 * @param      {string}  eclass  El tipo de filtro ej: rama, caracter, departamento, etc.
 */
function addFiltro(ids,eclass){
  if(ids[0]!=""){
    for (var i = ids.length - 1; i >= 0; i--) {
      $('.filtros').append('<div onclick="eliminar(event.target)" data-id="'+ids[i]+'" class="filtro '+eclass+'">'+$("#sel_"+eclass+" option[value="+ids[i]+"]").text()+'</div>');
    }
  }
}