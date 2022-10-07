
<center><img src="/public/img/geekshubs.png" style="width:900px;"/></center>

<pre>
<center><img src="/public/img/ubuntu-logo.jpg" style="width:120px;"/>  <img src="/public/img/Laravel.jpg" style="width:140px;"/>  <img src="/public/img/mysql-workbench.png" style="width:110px;"/>        <img src="/public/img/mysql-server.jpg" style="width:90px;"/>      <img src="/public/img/postman.jpg" style="width:115px;"/>
<img src="/public/img/thunder-client.png" style="width:120px;"/>   <img src="/public/img/heroku.png" style="width:130px;"/>   <img src="/public/img/PHP.png" style="width:120px;"/>     <img src="/public/img/jsonwebtoken.png" style="width:120px;"/>      <img src="/public/img/composer.png" style="width:92px;"/>
</center>
</pre>

<br>

> Desde GeeksHubsAcademy se solicita la siguiente aplicación :
# <center>Laravel Backend E-commerce</center>

<br>

##### Este documento consta de dos secciones:
<br>

> * Requisitos
> * Instrucciones de uso y demostración de proyecto.

<br><br>

> #### Primera sección:

> ##### Los requisitos funcionales de la aplicación son los siguientes:
<pre>
> ● 
</pre>

---

> #### Segunda sección:

> ##### Información acerca del proyecto e instrucciones de uso.

<br>

> Para cumplir con los requisitos solicitados, se han creado las siguientes tablas y sus relaciones:

<center><img src="/public/img/tablas-devos-ecommerce-laravel-backend.png" style="width:800px;"/></center>

> El proyecto se ha realizado en una rama adicional de trabajo, haciendo un merge cuando se ha comprobado una o varias funcionalidades.

> Se ha utilizado JWT para la autenticación de los usuarios.

> Existen distintos tipos de usuarios, siendo uno de ellos del tipo súper admin, que tiene acceso a ;
* Conceder privilegios de admin o super admin a otros usuarios.
* Quitar privilegios de admin o super admin a otros usuarios.
* Eliminar usuarios.
* Eliminar xxxx.
* Eliminar xxxx.

> Los usuarios sin privilegios de administrador podrán tener acceso a todo aquello relacionado con su cuenta de usuario.

> Para acceder a los endpoints, cualquier usuario puede hacerlo mediante distintas aplicaciones.
> En la aplicación se ha incluido un archivo JSON con los datos exportado de los endpoints para importarlos en Postman o Thunder client.

<pre>
Ubicación del archivo para importar en Postman o Visual Studio Code con Thunder Client.
<img src="/public/img/Postman-Thunder-json.png" style="width:200px;"/> 

Vista de Thunder Client en Visual Studio Code.
<img src="/public/img/Thunder-client.png" style="width:200px;"/> 

Vista de los distintos grupos creados para Thunder client.
<img src="/public/img/Postman-user.png" style="width:155px;"/> 

</pre>

>* Al importar el archivo, se tendrá acceso a todos los endpoints y se podrá hacer uso como titular de la aplicación. - SE RUEGA PRECAUCIÓN -
>* Las variables de entorno se encuentran en ".env.example".

>* Si se desea ejecutar en local, se deberán modificar los datos de conexión del archivo anterior y tener acceso a la base de datos en local.

>* La aplicación se ha construido de manera que permite tener un registro (al que un usuario no puede tener acceso ) de cada una de sus funciones ;

<img src="/public/img/Log-example.png" style="width:358px;"/>

>* Además, cada usuario recibirá información en caso de error o exito acerca de la función que haya ejecutado.

>* La aplicación cuenta con distintos seeds para ser probada sin tener que rellenar datos desde Workbench. (RoleSeeder por ejemplo.)

<br>
 
 > Para poder hacer uso de la aplicación es necesario clonarla o descargar el archivo zip.

 > Tras clonar o usar el .zip, es necesario ejecutar los siguientes comandos desde el terminal:
 >* composer install
 >* php artisan db:migrate
 >* php artisan db:seed

 > Con esos comandos y configurando el archivo .env para trabajar en local, se podrá hacer uso de la aplicación haciendo las solicitudes en "http://localhost:8000/api/{ENDPOINT}"
 

 ---

#### En adelante se muestran capturas de los distintos endpoints de la aplicación que se pueden agrandar al hacer click sobre su imagen, además de una breve descripción.
<br>

* #### USERS

<br><br>


>* <b>Register User;</b>
<img src="/public/img/Register-User.png" style="width:358px;"/>

> Se deberán introduciŕ los datos que aparecen en la captura mediante json.

<br>
<br>


>* <b>User login;</b>
<img src="/public/img/User-Login.png" style="width:358px;"/>

> Se deberán introduciŕ los datos que aparecen en la captura mediante json.

<br><br>



>* <b>User Profile;</b>
<img src="/public/img/User-Profile.png" style="width:358px;"/>

> Se deberá introduciŕ autenticación mediante token.

<br><br>



>* <b>User edit own profile;</b>
<img src="/public/img/Register-User.png" style="width:358px;"/>

> Se deberán introduciŕ los datos que aparecen en la captura mediante json además de introduciŕ autenticación mediante token.

<br><br>



>* <b>Add super admin to user;</b>
<img src="/public/img/Add-sadmin-by-id.png" style="width:358px;"/>

> El usuario ha de ser super admin e introducir autenticación mediante token y la id del usuario al que quiera dar privilegios en la url.

<br><br>



>* <b>Remove super admin from user;</b>
<img src="/public/img/Add-sadmin-by-id.png" style="width:358px;"/>

> El usuario ha de ser super admin e introducir autenticación mediante token y la id del usuario al que quiera quietar privilegios en la url.

<br><br>



>* <b>Logout user;</b>
<img src="/public/img/Add-sadmin-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token.

<br><br>



>* <b>Get all users by role;</b>
<img src="/public/img/Get-users-by-role-sadmin.png" style="width:358px;"/>

> El usuario ha de ser super admin e introducir autenticación mediante token.

<br><br>



>* <b>Delete user by id;</b>
<img src="/public/img/Add-sadmin-by-id.png" style="width:358px;"/>

> El usuario ha de ser super admin e introducir autenticación mediante token y la id del usuario al que quiera eliminar en la url.

<br><br>



---

* #### GAMES

<br><br>



>* <b>Create game;</b>
<img src="/public/img/Create-game.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y el título del juego.

<br><br>



>* <b>Get all games;</b>
<img src="/public/img/Get-all-games.png" style="width:358px;"/>

> No es necesario estar registrado para ver los juegos disponibles.

<br><br>



>* <b>Get game by id;</b>
<img src="/public/img/Get-game-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y la id del juego en la url.

<br><br>



>* <b>Get game by title;</b>
<img src="/public/img/Get-game-by-title.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y el título del juego mediante json.

<br><br>



>* <b>Get own games by user id;</b>
<img src="/public/img/Get-own-games-by-user-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y la id del usuario en la url.

<br><br>



>* <b>Update game title by id;</b>
<img src="/public/img/Update-game-title-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, la id del juego en la url y el nuevo título mediante json.

<br><br>



>* <b>Delete game by id;</b>
<img src="/public/img/Get-own-games-by-user-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y la id del juego en la url.

---

* #### CHANNELS

<br><br>



>* <b>Create new channel;</b>
<img src="/public/img/Create-new-channel.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, name y game id mediante json.

<br><br>



>* <b>Get all channels;</b>
<img src="/public/img/Create-new-channel.png" style="width:358px;"/>

> No es necesario registro para acceder a los distintos canales.

<br><br>



>* <b>Get channel by id</b>
<img src="/public/img/Get-channel-by-id.png" style="width:358px;"/>

> No es necesario registro para acceder a los distintos canales.

<br><br>



>* <b>Get channel by name;</b>
<img src="/public/img/Get-channel-by-name.png" style="width:358px;"/>

> No es necesario registro para acceder a los distintos canales.

<br><br>



>* <b>Join channel by id;</b>
<img src="/public/img/Join-channel-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, y canal en url.

<br><br>



>* <b>Exit channel by id;</b>
<img src="/public/img/Exit-channel-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, y canal en url.

<br><br>



>* <b>Delete channel by id (super admin);</b>
<img src="/public/img/Delete-channel-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, y canal en url.

<br><br>


---

#### MESSAGES

<br><br>



>* <b>Create message;</b>
<img src="/public/img/Create-message.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, channel id y mensaje.

<br><br>



>* <b>Get own messages;</b>
<img src="/public/img/Get-own-messages.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token.

<br><br>



>* <b>Get messages by message id;</b>
<img src="/public/img/Get-messages-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, y el id en url.

<br><br>

>* <b>Get messages by channel id;</b>
<img src="/public/img/get-messages-by-channel-id.png" style="width:358px;"/>

> No es necesaria autenticación mediante token, y se debe poner el id del canal en la url.

<br><br>


>* <b>Update message by id;</b>
<img src="/public/img/Update-message-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token, channel id, mensaje y el id en url.

<br><br>


>* <b>Delete message by id (super admin);</b>
<img src="/public/img/Update-message-by-id.png" style="width:358px;"/>

> El usuario ha de introducir autenticación mediante token y el id en url.

---

>* Aquí concluye la aplicación y sus posibilidades de momento, pues iré corrigiendo pequeños errores de código o mejoras del mismo para ir ampliándola.

>* <b>Por último, indicar que todo feedback es bienvenido !!</b>