function cargarseccion(seccion){
new Ajax.Request('seccion.php', {
method: 'post',
parameters: { seccion: seccion},
onCreate: function(){ $('copyright').innerHTML = 'Espera, por favor...'; },
onSuccess: function(transport){ $('copyright').innerHTML = transport.responseText; }
});
}
