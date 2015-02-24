function cargarseccion(seccion){
new Ajax.Request('seccion.php', {
method: 'post',
parameters: { seccion: seccion},
onCreate: function(){ $('footer').innerHTML = 'Espera, por favor...'; },
onSuccess: function(transport){ $('footer').innerHTML = transport.responseText; }
});
}
