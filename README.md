
<center><img src="/public/img/geekshubs.png" style="width:900px;"/></center>

<pre>
<center><img src="/public/img/ubuntu-logo.jpg" style="width:120px;"/>  <img src="/public/img/Laravel.jpg" style="width:140px;"/>  <img src="/public/img/mysql-workbench.png" style="width:110px;"/>        <img src="/public/img/mysql-server.jpg" style="width:90px;"/>      <img src="/public/img/postman.jpg" style="width:115px;"/>
<img src="/public/img/thunder-client.png" style="width:120px;"/>   <img src="/public/img/heroku.png" style="width:130px;"/>   <img src="/public/img/PHP.png" style="width:120px;"/>     <img src="/public/img/jsonwebtoken.png" style="width:120px;"/>      <img src="/public/img/composer.png" style="width:92px;"/>
</center>
</pre>

<br>


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
> ● Registro de usuarios.
> ● Login de usuarios + token + middleware.
> ● Middleware para roles de usuarios.
> ● CRUD de los diferentes modelos.
> ● Al menos una relación Many to Many y otra One to Many.
> ● Seeders para distintas tablas.
</pre>


---

> #### Segunda sección:

> ##### Información acerca del proyecto e instrucciones de uso.

<br>

> Para cumplir con los requisitos solicitados, se han creado las siguientes tablas y sus relaciones:

<center><img src="/public/img/Diagram.png" style="width:800px;"/></center>


> Para mantener un orden y una secuencia correcta, se ha usado Trello

<center><img src="/public/img/trello.png" style="width:400px;"/></center>

<br>

---

> El proyecto se ha realizado en una rama adicional de trabajo, haciendo un merge cuando se ha comprobado una o varias funcionalidades.

> Se ha utilizado JWT para la autenticación de los usuarios.

> Existen distintos tipos de usuarios, siendo uno de ellos del tipo súper admin, que tiene acceso a ;
* Conceder privilegios de admin o super admin a otros usuarios.
* Quitar privilegios de admin o super admin a otros usuarios.
* Recuperar todos los usuarios.
* Eliminar usuarios.
* Recuperar todas las ventas.
* Crear productos.
* Editar productos.
* Eliminar productos.
* Recuperar venta mediante id usuario.
* Recuperar venta mediante id venta.
* Recuperar venta mediante estado.
* Editar estado de una venta.


> Los usuarios sin privilegios de administrador podrán tener acceso a todo aquello relacionado con su cuenta de usuario pudiendo:

* Registrar nuevo usuario.
* Hacer log in.
* Hacer log out.
* Recuperar información sobre su cuenta de usuario.
* Editar información de su cuenta de usuario.
* Realizar compras.
* Valorar (Rating) de producto / servicio.

> Para acceder a los endpoints, cualquier usuario puede hacerlo mediante distintas aplicaciones como Postman o la extensión Thunder de Visual Studio Code.

</pre>

>* Si se desea ejecutar en local, se deberán modificar los datos de conexión del archivo .env y tener acceso a la base de datos en local.

>* La aplicación se ha construido de manera que permite tener un registro de cada una de sus funciones ;

<img src="/public/img/log.png"/>

>* Además, cada usuario recibirá información en caso de error o exito acerca de la función que haya ejecutado.

>* La aplicación cuenta con distintos seeds para ser probada sin tener que rellenar datos desde Workbench. (RoleSeeder por ejemplo.)

<br>
 
 > Para poder hacer uso de la aplicación es necesario clonarla o descargar el archivo zip.

 > Tras clonar o usar el .zip, es necesario ejecutar los siguientes comandos desde el terminal:
 >* composer install
 >* php artisan db:migrate
 >* php artisan db:seed

 > Con esos comandos y configurando el archivo .env para trabajar en local, se podrá hacer uso de la aplicación haciendo las solicitudes en "http://localhost:8000/api/{ENDPOINT}"
 

 > #### En las siguientes imagenes se presentan como muestra varios endpoints a los que se tiene acceso:

<img src="/public/img/login.png" style="width:192px;"/> <img src="/public/img/logout.png" style="width:169px;"/> <img src="/public/img/me.png" style="width:192px;"/> <img src="/public/img/edit-own-profile.png" style="width:169px;"/> <img src="/public/img/ratint-edit.png" style="width:172px;"/> <img src="/public/img/add-superadmin.png" style="width:195px;"/> <img src="/public/img/remove-superadmin.png" style="width:195px;"/> <img src="/public/img/purchase.png" style="width:210px;"/>
 ---

 > La dirección donde se ha desplegado (Heroku) es https://devos-ecommerce.herokuapp.com

 > El usuario de la primera imágen es súper admin y con él se podrá utilizar cualquiera de los endpoints y todas sus funciones.

 > Al añadir las siguientes partes de ruta a la anterior se podrá tener acceso a toda la aplicación.

##### ROUTES FOR USERS
>* /register (POST)
>* /login (POST)
>* /me (GET)
>* /edit/{id} (PUT)
>* /logout (POST)

##### ROUTES FOR SUPER ADMIN
>* /user/addsuperadmin/{id} (POST)
>* /user/removesuperadmin/{id} (POST)
>* /user/getallusersbyadmin/{id} (GET)
>* /user/getallsalesbyadmin (GET)
>* /user/deleteuserby/{id} (DELETE)
>* /user/createproduct (POST)
>* /user/editproduct/{id} (PUT)
>* /user/deleteproduct/{id} (DELETE)
>* /user/getsalesbyuserid/{id (GET)
>* /user/getsalebyid/{id} (GET)
>* /user/getsalesbystatus (GET)
>* /user/editstatus/{id} (PUT)

##### ROUTES FOR PRODUCTS
>* /getallproducts (GET)
>* /getproductsbybrand (GET)
>* /getproductsbyname (GET)
>* /getproductbyid/{id} (GET)

##### ROUTES FOR SALES
>* /getownsales (GET)
>* /getownsalesbyid/{id} (GET)

##### ROUTES FOR PURCHASES
>* /createpurchase (POST)


###### Al realizar una compra, en la tabla sales se obtiene el importe total de cada venta por usuario (un cliente compra varios productos con distintos precios), quedando además por defecto en estado de "in progress".

<pre>
<img src="/public/img/purchases.png" style="width:220px;"/>     <img src="/public/img/sales.png" style="width:250px;"/>     <img src="/public/img/users.png" style="width:280px;"/>     <img src="/public/img/products.png" style="width:280px;"/>

</pre>

##### ROUTES FOR RATING

>* /ratesale/{id} (PUT)

---

>* Aquí concluye la aplicación y sus posibilidades de momento, pues iré corrigiendo pequeños errores de código o mejoras del mismo para ir ampliándola.

>* <b>Por último, indicar que todo feedback es bienvenido !!</b>