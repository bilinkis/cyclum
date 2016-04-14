<div class="row">
    <div class="col s12 m9 l10">
        <div id="api-intro" class="section scrollspy">
            <h5>Introducción</h5>
            <p>Cyclum provee una API para desarrolladores para poder hacer estadísticas de los cambios que se le hacen a su producto, y luego poder decidir si estos fueron beneficiosos o no.</p>
            <p>Además, damos la opción de sacar estadísticas del producto sin cambios a lo largo del tiempo, para que se pueda generar una historia de cómo cada cambio fue variando el comportamiento de los usuarios.</p>
        </div>
        
        <div class="divider"></div>
        
        <div id="api-instructions" class="section scrollspy">
            <h5>Instrucciones de uso</h5>
            <p>Para poder implementar la API dentro de su proyecto, debe seguir una serie de pasos.</p>
            <ul class="collapsible popout" data-collapsible="accordion">
                <li>
                    <div class="collapsible-header"><i class="material-icons">settings</i>Configuración de servidor</div>
                    <div class="collapsible-body">
                        <p>Cuando se hace un cambio, y se quiere validar si fue positivo, se puede implementar A/B testing, que consiste en mostrarle a un porcentaje de los usuarios una versión del producto con el cambio, y al resto una versión sin el cambio.</p>
                        <p>Nuestra API ofrece herramientas para conocer el comportamiento de cada grupo de usuarios, pero por el momento no brindamos herramientas para dividir, desde el servidor, a los usuarios para que se le muestre una versión distinta a cada grupo.</p>
                        <p>Por esto recomendamos la implementación de un sistema en el cual se guarde una cookie en el cliente, en la que se sepa a qué grupo pertenece (se le asigna de forma aleatoria).</p>
                        <p>Por último, dependiendo a qué grupo pertenece, se le debe mostrar una versión distina del producto, e incluir a nuestra API de forma diferente, para que podamos saber a qué grupo pertenece (más adelante se explica en detalle)</p>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">get_app</i>Instalación de la API</div>
                    <div class="collapsible-body">
                        <p>Cyclum necesita jQuery para poder funcionar, por lo tanto, se necesita descargar o incluir jQuery en su proyecto para que la API funcione</p>
                        <p><a href="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js" download="jquery.min.js" class="waves-effect waves-light btn"><i class="material-icons left">get_app</i>Descargar jQuery</a></p>
                        <div class='pre-margin-left'><pre><code class='language-markup'>&lt;script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'&gt;&lt;/script&gt;</code></pre></div>
                        <p>El siguiente paso es descargar el código JavaScript para monitorear a los clientes.</p>
                        <p><a href="js/api.js" download="api.js" class="waves-effect waves-light btn"><i class="material-icons left">get_app</i>Descargar API</a></p>
                        <p>Una vez descargada la API, se debe incluir en cada página en la que se quiere registrar el comportamiento de los clientes.</p>
                        <div class='pre-margin-left'>
                            <pre><code class='language-markup'>&lt;script type='text/javascript' src='js/cyclum.js'&gt;&lt;/script&gt;&#13;&lt;script type='text/javascript'&gt;&#13;&#09;cyclum.ini('id_grupo_clientes', 'estado_default_usuarios');&#13;&lt;/script&gt;</code></pre>
                        </div>
                        <p>Para que podamos saber a qué grupo corresponde el usuario de su producto, se debe reemplazar en la función <i>cyclum.ini</i> el parámetro <i>'id_grupo_clientes'</i> por el id del grupo que corresponda. En Uso de la API se explica para qué sirve <i>'estado_default_usuarios'</i>.</p>
                    </div>
                </li>
                <li>
                    <div class="collapsible-header"><i class="material-icons">trending_up</i>Uso de la API</div>
                    <div class="collapsible-body">
                        <p>Por ser la primera versión de la API, permitimos registrar 3 cosas:</p>
                        <p class='no-padding'>&#8226; Estado de los usuarios</p>
                        <p class='no-padding'>&#8226; Variables</p>
                        <p class='no-padding'>&#8226; Tiempo en cada página</p>
                        
                        <br>
                        
                        <p>Una de las maneras para usar la API es agregandole a los objectos HTML distintas propiedades</p>
                        
                        <blockquote class="collapsible-margin-left">
                            <b>data-cyclum:</b> Se le indica a la API si lo que se va a cambiar es una variable (hizo una pregunta, subio una foto, etcétera) ó si lo que se va a cambiar es un estado (registrado, logeado, etcétera).
                            <br>
                            <b>data-cyclum-value:</b> Se indica el valor de lo que se va cambiar (noticia, pregunta, registrado, logeado), esto depende de el valor que haya en <i>'data-cyclum'</i> ya que no se va a cambiar una variable 'registrado'.
                            <br>
                            <b>data-cyclum-amount:</b> Se indica cuanto se va a sumar o restar el valor de una variable, en caso de querer restar, ingresar -1, -2, etcétera.
                        </blockquote>
                        
                        <p>Otra de las maneras para usar la API es llamando a funciones de JavaScript en donde se indica si se cambia el valor de una variable o un estado</p>
                    
                        <div class='pre-margin-left'>
                            <pre><code class='language-markup'>&lt;script type='text/javascript'&gt;&#13;&#09;cyclum.variable.modify('preguntas', 1);&#13;&#09;cyclum.state.modify('login');&#13;&lt;/script&gt;</code></pre>
                        </div>
                        
                        <blockquote class="collapsible-margin-left">
                            <b>cyclum.variable.modify (nombre, cantidad):</b> Se le indica a la API el nombre de la variable que se va a modificar y la cantidad (1, -1, 2, etcétera).
                            <br>
                            <b>cyclum.state.modify (nombre):</b> Se le indica a la API qué estado se va a modificar pasando como parámetro el nombre.
                        </blockquote>
                        
                        <p>A continuación se van a mostrar distintos ejemplos acerca de como usar la API.</p>
                        
                        <div class="divider"></div>
                        
                        <p>Estado de los usuarios:</p>
                        <blockquote class="collapsible-margin-left">
                            Supongamos que queremos saber el porcentaje de los usuarios que entraron a la página pero no se registraron, y el porcentaje de los que sí se registraron; para esto se puede usar la funcionalidad de Estado de Usuarios.
                            
                            <br>
                            
                            Primero, tenemos que fijar el estado por defecto de los usuarios (en este caso, todo usuario que entra por primera vez queremos que aparezca como "No Registrado"). Para esto, cuando inicializamos la API le pasamos como parámetro el estado por defecto.
                            
                            <pre><code class='language-markup'>&lt;script type='text/javascript'&gt;&#13;&#09;cyclum.ini('id_grupo', 'no-registrado');&#13;&lt;/script&gt;</code></pre>
                            
                            Esto hace que cada vez que entre un usuario por primera vez, se guarde como su estado 'no-registrado'.
                            Ahora, queremos que cuando el usuario aprieta el botón de registrar, se actualize el estado por 'registrado'.
                            
                            <br>
                            
                            <pre><code class='language-markup'>&lt;button data-cyclum='state' data-cyclum-value='registrado'&gt;&lt;/button&gt;</pre></code>
                            
                            Para esto tenemos que decirle a la API que lo que estamos haciendo es cambiar el estado, por lo tanto en el valor de <i>'data-cyclum'</i> ingresamos 'state' y luego en <i>'data-cyclum-value'</i> por ejemplo, ingresamos 'registrado'. Nosotros nos encargamos del resto.
                            
                            <br>
                            <br>
                            
                            La otra manera para hacerlo es con JavaScript
                            <pre><code class='language-markup'>&lt;script type='text/javascript'&gt;&#13;&#09;$('#MiBoton').click(function(){&#13;&#09;&#09;cyclum.state.modify('estado');&#13;&#09;&#09;...&#13;&#09;});&#13;&lt;/script&gt;</code></pre>
                            
                           Cuando el botón se clickea cambia el estado de un usuario.
                        </blockquote>
                        
                        <div class="divider"></div>
                        
                        <p>Variables</p>
                        <blockquote class="collapsible-margin-left">
                            Supongamos que, en un sitio de noticias, queremos saber cuántas notas vio cada usuario en promedio. Lo que tenemos que hacer es 
                            crear en el botón o el link que lleva a la nota, 3 atributos distintos como en el siguiente ejemplo:
                            
                            <pre><code class='language-markup'>&lt;a data-cyclum='variable' data-cyclum-value='vio-noticia' data-cyclum-amount='1'&gt;&lt;/a&gt;</pre></code>
                            
                            Como la cantidad de visitas a notas es una variable numérica, en <i>'data-cyclum'</i> ponemos 'variable'. En <i>'data-cyclum-value'</i> ponemos el nombre de la variable, en este caso 'vio-noticia'. Por último, en <i>'data-cyclum-amount'</i> ponemos la variación que le queremos aplicar a la variable. En este caso, va a ser 1.
                            
                            <br>
                            <br>
                            
                            La otra manera para hacerlo es con JavaScript
                            <pre><code class='language-markup'>&lt;script type='text/javascript'&gt;&#13;&#09;$('#MiBoton').click(function(){&#13;&#09;&#09;cyclum.variable.modify('vio-noticia', 1);&#13;&#09;&#09;...&#13;&#09;});&#13;&lt;/script&gt;</code></pre>
                            
                           Cuando el botón se clickea cambia el valor de la variable ingresada.
                        </blockquote>
                        
                        <div class="divider"></div>
                        
                        <p>Tiempo en cada página</p>
                        <blockquote class="collapsible-margin-left">
                            Una vez que inicializamos la API con la función <i>'cyclum.ini'</i>, ya estamos monitoreando el tiempo que pasan los usuarios en cada página, sin previa configuración. 
                        </blockquote>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="col hide-on-small-only m3 l2">
        <div id='right-navbar'>
            <ul class="section table-of-contents">
                <li><a href="#api-intro">Introducción</a></li>
                <li><a href="#api-instructions">Instrucciones de uso</a></li>
            </ul>
        </div>
    </div>
</div>
