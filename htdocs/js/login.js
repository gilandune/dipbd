$(document).ready(function(){
    
//    console.log($.browser);
    
    $.reject({  
        imagePath: '/images/jreject/',
        reject: {  
            // MSIE Flags (Global, 5-8) 
            //msie: true, 
            msie5: true, msie6: true, msie7: true, msie8: true, 
            // Firefox Flags (Global, 1-3) 
            firefox1: true, firefox2: true, firefox3: true, firefox4: true, firefox5: true,
            // Konqueror Flags (Global, 1-3) 
//            konqueror, konqueror1, konqueror2, konqueror3, 
            // Chrome Flags (Global, 1-4) 
            chrome1: true, chrome2: true, chrome3: true, chrome4: true, chrome5: true, chrome6: true, chrome7: true,
            // Safari Flags (Global, 1-4) 
            //safari: true, 
            safari2: true, safari3: true, safari4: true, 
            // Opera Flags (Global, 7-10) 
            //opera: true, 
            opera7: true, opera8: true, opera9: true, opera10: true, 
            unknown: true // Everything else  
        },
        header: 'Explorador no soportado', // Header Text  
        paragraph1: 'El explorador con el que está ingresando al sistema podría no estar soportado o estar desactualizado', // Paragraph 1  
        paragraph2: 'Por favor actualice su explorador o instale alguna version recomendada a continuación',
        closeMessage: 'Al cerrar esta ventana, usted reconoce que su experiencia en este sitio web podría no ser la óptima', // Message below close window link  
        closeLink: 'Cerrar esta ventana'
    });
    
    $('#waform_login').validate({
        rules: {
            'username': 'required',
            'pswd': 'required'
        },
        messages: {
            'username': 'Requerido',
            'pswd': 'Requerido'
        },
        debug: true,
        submitHandler: function(form) {
            var url = '/';

            $.post('response/response_data.sys.php', $(form).serialize(),
            function(data) {
                switch(data.result) {
                    case 'ok':
                        $(location).attr('href',url)
                        break;
                    case 'error':
                        clear_form_elements('#waform_login');
                        $.pnotify({
                            history: false,
                            title: 'Error al iniciar sesión',
                            text: data.error_msg,
                            type: 'error',
                            styling: 'jqueryui',
                            delay: 2000
                        });
                        
                        console.log(data.debug_info)
                        
                        break;
                    default:
                        
                        console.log(data);
                        break;
                }
            },'json');
        }
    });

});


