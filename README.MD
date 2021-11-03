
# Tasker

Tasker es un proyecto que consiste en la implementación de una tienda virtual donde en lugar de ofrecer productos se ofrecen servicios.

La documentacion será creada para especificar en profundidad las diferentes partes de la aplicación des de un punto de vista academico, proporcionando documentación para cada una de las funcionalidades implementadas. 


# Estructura de ficheros

La estructura de ficheros implementa varias funcionalidades inspiradas por el framework de Laravel. Algunas de las funcionalidades son un router propio, una estructura de vistas basada en plantillas, el uso de variables de entorno para asignar los datos sensibles (contraseñas bases de datos, usuarios, etc...) y  la implementación de un pequeño ORM propio.

La estructura se basa principalmente en el patron MVC, y esta pensado para facilitar la escalabilidad de la plataforma. 


# Diseño del software

El diseño interno de la plataforma es el punto más fuerte del proyecto. Esta pensando para ser escalable, mantanible y facilmente editable. Esto se consigue con la implementación de distintos patrones de diseño para crear una jerarquía de clases estable y armonica.



## El patrón Strategy y las diferentes implementaciones para cada tipo de base de datos.

Uno de los problemas que me encontre en el desarrollo del proyecto fue como añadir funcionalidad para cada tipo de base de datos. Las operaciones son efectuadas a traves de un ORM y definir los diferentes algoritmos para cada tipo de base de datos (SQL y NoSql) sin una estructura previa me parecio una mala idea, porque podria acabar en código repetido, mal organizado y poco escalable. 

Por eso opté por implementar el patron de diseño Strategy, y pasarle a la instancia de este diferentes classes con el mismo tipo pero implementaciones diferentes dependiendo del tipo de base de datos que se necesite. La base de datos se define en el fichero .env y el cliente solamente necesita instanciar la clase que se ocupa de manejar los datos. 


## Instanciando la base de datos usando un Singleton
Este punto podría llegar a considerarse overkill debido a que no es un problema realmente que necesite esta implementación, pero me parecio buena idea para ahorrar recursos en tiempo de ejecución. La clase encargada de la conexion a la base de datos debe instanciarse individualmente en cada fichero, por lo que me parecio un gasto de recursos innecesario. Decidí que para solventar esto podría implementar un Singleton que se ocupase de que la instancia fuese única y se compartiese por todo el programa. Esto evita que se creen objetos de más y mejora un poco el tiempo de carga de la web (es un valor relativamente pequeño pero aún así decidí implementarlo).


# Diseño de la web

Todo la parte visual del proyecto ha sido diseñada y maquetada por mi, no he usado ninguna libreria de terceros para los estilos (Bootstrap, Tailwind, etc..) y para organizar mejor los estilos he utilizado Sass, separando mejor el código y también haciendolo más leíble.

Las imagenes estaticas están optimizadas al máximo para evitar tiempos de carga excesivos o problemas con las composite layers del navegador.


