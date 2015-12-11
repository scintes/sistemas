
function cargar_pie_de_pagina(id_div,url){
    //debugger;
    //$("#"+id_div).html("esto es un ejemplo");
    $("#"+id_div).load(url,
                        {}, 
                        function(response, status, xhr) {
                            if (status == "error") {
                                var msg = "Error!, algo ha sucedido: ";
                                $("#"+id_div).html(msg + xhr.status + " " + xhr.statusText);
                            }				  
                        }
                      );    
}


// Titulo: Titulo de la ventana
// Url: pagina con parametro 
// alto, ancho: alto y ancho de la ventana.
function abrir_ventana(titulo,url,alto,ancho) {
	var pos_x = (screen.height - alto) / 2;
	var pos_y = (screen.width - ancho) / 2;
        return window.open(url, titulo, "toolbar = 0, status = 0, menubar = 0, resizable = 0, titlebar = 0, hotkeys = 0, height = "+alto+", width = "+ancho+", top = "+pos_y+", left ="+pos_x);
};

// Cierra las ventanas 
function cerrar_ventana(puntero){ // cerrar_ventana
//    if (!puntero){
        puntero.close();
//    }else{
//        window.opener.puntero.close();
    //}
};	
//------------------------------------------------------------------------------
// objeto:Sintaxis igual al jquery ejemplo ob1="#e_fecha_desde"
// Vincula dos "text" con sus respectivos calendarios en javascript 
//------------------------------------------------------------------------------
function asociar_rango_calendario(ob1,ob2,formato){
    if(!formato){
        formato="dd/mm/yy";
    }     
    $(function() {
        $( ob1 ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 2,   
        showButtonPanel: true,
        dateFormat: formato,
        onClose: function( selectedDate ) {
            $( ob2 ).datepicker( "option", "minDate", selectedDate );        
        }      
    });
    $( ob1 ).datepicker();
    
    $( ob2 ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 3,
        showButtonPanel: true,
          dateFormat: formato,
        onClose: function( selectedDate ) {
            $( ob1 ).datepicker( "option", "maxDate", selectedDate );
        }
    });
    $( ob2 ).datepicker();

  });
    
}
//------------------------------------------------------------------------------
// referencia al calendario desde una fecha en adelante
//------------------------------------------------------------------------------
function asociar_calendario_con_fecha_desde(ob1,formato){
    if(!formato){
        formato="dd/mm/yy";
    }    
    $(function() {
        $( ob1 ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1, 
            showButtonPanel: true,
            dateFormat: formato,
            onClose: function( selectedDate ) {
                $( ob1 ).datepicker( "option", "minDate", selectedDate );        
            }      
    });
    
    $( ob1 ).datepicker();
    
  });
    
}
//------------------------------------------------------------------------------
// referencia al calendario hasta una fecha en adelante
//------------------------------------------------------------------------------
function asociar_calendario_con_fecha_desde(ob1,formato){
    if(!formato){
        formato="dd/mm/yy";
    }    
    $(function() {
    $( ob1 ).datepicker({
      defaultDate: "+1w",
      changeMonth: true,
      changeYear: true,
      numberOfMonths: 1, 
      showButtonPanel: true,
        dateFormat: formato, 
      onClose: function( selectedDate ) {
        $( ob1 ).datepicker( "option", "maxDate", selectedDate );        
      }      
    });
    $( ob1 ).datepicker();
    
  });
    
}

//------------------------------------------------------------------------------
// Asociamos el calendario js a un componente
// Entrada: Objeta a asociar, y el formato(por defecto es dd/mm/yy)
//------------------------------------------------------------------------------
function asociar_calendario(ob1,formato){
    if(!formato){
        formato="dd/mm/yy";
    }
    $(function() {
        
        $( ob1 ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            numberOfMonths: 1, 
            showButtonPanel: true,  
            dateFormat: formato            
        });
        
        $( ob1 ).datepicker();
    
  });
    
}

//------------------------------------------------------------------------------       
/* CONFIGURATION
    Here are the keys that you can use if you pass an object into sweetAlert:
    Argument	Default value	Description
    title	null (required)	The title of the modal. It can either be added to the object under the key "title" or passed as the 
                                first parameter of the function.
    text	null	A description for the modal. It can either be added to the object under the key "text" or passed as the 
                        second parameter of the function.
    type	null	The type of the modal. SweetAlert comes with 4 built-in types which will show a corresponding icon animation: 
                        "warning", "error", "success" and "info". 
                        It can either be put in the array under the key "type" or passed as the third parameter of the function.
    allowOutsideClick	false	If set to true, the user can dismiss the modal by clicking outside it.
    showCancelButton	false	If set to true, a "Cancel"-button will be shown, which the user can click on to dismiss the modal.
    confirmButtonText	"OK"	Use this to change the text on the "Confirm"-button. If showCancelButton is set as true, the confirm 
                                button will automatically show "Confirm" instead of "OK".
    confirmButtonColor	"#AEDEF4"	Use this to change the background color of the "Confirm"-button (must be a HEX value).
    cancelButtonText	"Cancel"	Use this to change the text on the "Cancel"-button.
    closeOnConfirm	true	Set to false if you want the modal to stay open even if the user presses the "Confirm"-button. This 
                                is especially useful if the function attached to the "Confirm"-button is another SweetAlert.
    imageUrl	null	Add a customized icon for the modal. Should contain a string with the path to the image.
    imageSize	"80x80"	If imageUrl is set, you can specify imageSize to describes how big you want the icon to be in px. 
                        Pass in a string with two values separated by an "x". The first value is the width, the second is the height.
    timer	null	Auto close timer of the modal. Set in ms (milliseconds). */
    
// Muestra solo el mensaje
        function aviso(titulo){
            swal(titulo);            
        }
// muestra el mensaje con un titulo         
        function mensaje(titulo, mensaje){
            swal(titulo, mensaje);            
        }
// muestra el mensaje con titulo por un tiempo dado en segundos.        
        function mensaje_con_tiempo(titulo, mensaje, tiempo){
            tiempo = tiempo * 1000;
            swal({
                 title: titulo,   
                 text: mensaje,   
                 timer: tiempo 
             });            
        }
// Muestra el mensaje con titulo con una imagen determinada
        function mansaje_con_imagen(titulo, mensaje, imagen){
            swal({   
                  title: titulo,   
                  text: mensaje,   
                  imageUrl: imagen 
              });            
        }
// Muestra el mensaje de ERROR y espera ha que hagas clicken aceptar        
        function mensaje_error(titulo, mensaje){
            swal({   title: titulo,   text: mensaje,   type: "error",   confirmButtonText: "Aceptar" });
        }
        
// Muestra el mensaje de advertencia con un titulo
        function mensaje_advertencia(titulo, mensaje){
            swal({   title: titulo,   text: mensaje,   type: "error",   confirmButtonText: "Aceptar" });
        }

        function mensaje_con_descision_parcial(titulo, mensaje){
            swal({   
                    title: titulo,   
                    text: mensaje,   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Si",   
                    closeOnConfirm: false 
                 }, 
                function(){   
                        swal("Deleted!", "Your imaginary file has been deleted.", "success"); 
                });            
        }
        
        function mensaje_con_descision_completa(titulo, mensaje){
            swal({   
                    title: titulo,   
                    text: mensaje,   
                    type: "warning",   
                    showCancelButton: true,   
                    confirmButtonColor: "#DD6B55",   
                    confirmButtonText: "Yes, delete it!",   
                    cancelButtonText: "No, cancel plx!",   
                    closeOnConfirm: false,   
                    closeOnCancel: false 
                }, 
                function(isConfirm){   
                    if (isConfirm) {     
                        swal("Deleted!", "Your imaginary file has been deleted.", "success");   
                    } else {     
                        swal("Cancelled", "Your imaginary file is safe :)", "error");   
                    } 
                }
            );
        }

//------------------------------------------------------------------------------
// Usados para cambiar el color de la fila en las tablas
//------------------------------------------------------------------------------
var back_color = ''; // guarda el color que tiene la celda antes de alterarla.
// funcion que altera el color de la celda.
function cambiar_color_over(celda){ 
   back_color = celda.style.backgroundColor; 
   celda.style.backgroundColor = '#ffa556'; 
} 
// vuelve a poner la celda en el color anterior.
function cambiar_color_out(celda){ 
   celda.style.backgroundColor= back_color;
} 
//------------------------------------------------------------------------------

//******************************************************************************
// Dialogo por Sergio Cintes
//      Parametros de Entrada: 
//          Titulo: Nombre referencial del cuadro de dialogo.
//          div: ID del objeto DIV que se tomara como cuadro de dialogo.
//          ancho: indica el valor del ancho del dialogo.
//          alto: Indica  el valor de la altura del cuadro de dialogo.
//          url: pagina a mostrar en el dialogo.
//          parametros: Lista de parametros a pasar por post a la url en formato json
//          faceptar: Nombre de la funcion a ejecutar al precionar el boton aceptar.
//          fverif_aceptar: Nombre de la funcion que verifica las condiciones que se 
//                      deben cumplir para ejecutar la funcion faceptar.
//          fcancelar: Nombre de la funcion a ejecutar al precionar el boton cancelar. 
//          fverif_cancelar: Nombre de la funcion que verifica las condiciones que se 
//                      deben cumplir para ejecutar la funcion fcancelar.
//          mensaje_aceptar: Pregunta por verdadero o falso antes de evaluar 
//                      la funcion de verificacion en el boton aceptar.
//          mensaje_cancelar: Pregunta por verdadero o falso antes de evaluar 
//                      la funcion de veriicacion en el boton cancelar.           
//     Salida: 
//          Ejecutando boton ACEPTAR:
//              Verif V F     Funcion  V F
//                 -  - -           -  - - ---> -1(Sale)
//              R=f() x -           -  - - --->  R(Sale)
//              R=f() - x           -  - - ---> -2 
//                 -  - -        W=g() x - ---> W(sale)           
//                 -  - -        W=g() - x ---> -3           
//              R=f() x -        W=g() x - ---> W(sale)           
//              R=f() x -        W=g() - x ---> -4           
//              R=f() - x        W=g() x - ---> -2           
//              R=f() - x        W=g() - x ---> -2           
//          Ejecutando boton CANCELAR:
//              Verif V F     Funcion  V F
//                 -  - -           -  - - ---> -1(Sale)
//              R=f() x -           -  - - --->  R(Sale)
//              R=f() - x           -  - - ---> -2 
//                 -  - -        W=g() x - ---> W(sale)           
//                 -  - -        W=g() - x ---> -3           
//              R=f() x -        W=g() x - ---> W(sale)           
//              R=f() x -        W=g() - x ---> -4           
//              R=f() - x        W=g() x - ---> -2           
//              R=f() - x        W=g() - x ---> -2           

//------------------------------------------------------------------------------ 
function dialogo(titulo, div, ancho, alto, url, parametros,faceptar,fverif_aceptar,fcancelar,fverif_cancelar,mensaje_aceptar,mensaje_cancelar){
    $("#"+div).dialog({ // <!--  ------> muestra la ventana  -->
        widthmin:ancho, width: ancho, widthmax:ancho,                                                
        heightmin: alto,height: alto, heightmax: alto,                        
        show: "scale", // <!-- -----------> animación de la ventana al aparecer -->
        hide: "scale", // <!-- -----------> animación al cerrar la ventana -->
        resizable: "false", // <!-- ------> fija o redimensionable si ponemos este valor a "true" -->
        position: "center", // <!--  ------> posicion de la ventana en la pantalla (left, top, right...) -->
        modal: "true", // <!-- ------------> si esta en true bloquea el contenido de la web mientras la ventana esta activa (muy elegante) -->
        title: titulo,
        position:   { 
            my: "center", 
            at: "center", 
            of: window 
                    },                            
        buttons: {
            //----------------------------------
            // ------ BOTON de ACEPTACION -----
            //----------------------------------
            "Aceptar": function() {    
                var res_pregunta_aceptar = false;
                var respuesta_pregunta = false;
                
                if (!mensaje_aceptar){
                    respuesta_pregunta = true;                    
                }else{
                    if(confirm(mensaje_aceptar)){
                        respuesta_pregunta = true;
                    }                    
                }
                if (respuesta_pregunta){
                    if(!fverif_aceptar){ // Si se ingreso la funcion a evaluar antes de faceptar
                           if(!faceptar){// determinamos si hay funcion a ejecutar                             
                               $( "#"+div ).dialog( "close" );
                               return -1                                                         
                           }else{
                               // Evaluamos la funcion ACEPTAR
                               var aux1 = eval(faceptar);
                               if(aux1){
                                    return aux1;
                                    $( "#"+div ).dialog( "close" );     
                               }else{
                                    return -3                               
                               }
                           }
                    }else{
                        var aux = eval(fverif_aceptar); // evaluamos la funcion de verificacion
                        if(!aux){// si no paso las condiciones
                            return -2;
                            //$( "#"+div ).dialog( "close" );
                        }else{   // Si paso la condicion
                            if(!faceptar){// determinamos si hay funcion a ejecutar                             
                                return aux;                              
                                $( "#"+div ).dialog( "close" );                                 
                           }else{
                               var aux1 = eval(faceptar);
                               if(aux1){
                                   return aux1;
                                   $( "#"+div ).dialog( "close" );                                    
                               }else{
                                   return -4;                               
                               }
                           }
                        }                    
                    } 
                }
            },
            //----------------------------------
            // ------ BOTON de CANCELAR -----
            //----------------------------------                                
            "Cancelar": function() { 
                var respuesta_pregunta = false;
                if (!mensaje_cancelar){
                    respuesta_pregunta = true;                    
                }else{
                    if(confirm(mensaje_cancelar)){
                        respuesta_pregunta = true;
                    }                    
                }
                if (respuesta_pregunta){
                    if(!fverif_cancelar){ // Si se ingreso la funcion a evaluar antes de fcancelar
                           if(!fcancelar){// determinamos si hay funcion a ejecutar                             
                               $( "#"+div ).dialog( "close" );
                               return -1                                                         
                           }else{
                               // Evaluamos la funcion ACEPTAR
                               var aux1 = eval(faceptar);
                               if(aux1){
                                    $( "#"+div ).dialog( "close" ); 
                                    return aux1;                               
                               }else{
                                    return -3                               
                               }
                           }
                    }else{
                        var aux = eval(fverif_cancelar); // evaluamos la funcion de verificacion
                        if(!aux){// si no paso las condiciones
                            return -2;
                            //$( "#"+div ).dialog( "close" );
                        }else{   // Si paso la condicion
                            if(!fcancelar){// determinamos si hay funcion a ejecutar                             
                                $( "#"+div ).dialog( "close" ); 
                                return aux                              
                           }else{
                               var aux1 = eval(faceptar);
                               if(aux1){
                                   $( "#"+div ).dialog( "close" ); 
                                   return aux1;
                               }else{
                                   return -4                               
                               }
                           }
                        }                    
                    }   
                }
            }
        }
    });
    if(url!=""){
        $("#"+div).load(url,parametros, 
            function(response, status, xhr) {                            
                switch (status) {
                    case 'error':{ 
                        var msg = "Error!, algo ha sucedido: ";
                        $("#"+div).html(msg + xhr.status + " " + xhr.statusText);
                        break;}
                    case 'success': {break;}
                    case 'notmodified':{break;}
                    case 'timeout':    {break;}
                    case 'parsererror':{break;}
                }
            }
        );                                        
    }

}