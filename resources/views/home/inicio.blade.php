<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <title>Laboratorios Lafedar</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <div class="logo">
        <img src="{{ asset('storage/Imagenes principal-nueva/LOGO-LAFEDAR.png') }}" alt="Logo de la empresa">
    </div>
    <input type="text" class="search-bar" placeholder=" Buscar por palabra clave">
</header>

    <nav>
      
        <button class="nav-btn">Internos</button>
        <button class="nav-btn">Solicitudes</button>
        <button class="nav-btn">Documentos</button>
    </nav>

    <section class="login">
      <img src="{{ asset('storage/Imagenes principal-nueva/LOGIN.png') }}" alt="Logo de la empresa">
        <h2>Inicio de Sesión</h2>
        <form action="login.php" method="post">
            <label for="usuario">Usuario</label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena">Contraseña</label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Ingresar</button>
        </form>
    </section>

    <div class="novedades">
    <h2>NOVEDADES</h2>
    <div class="novedad-container">
        <div class="novedad-item">
            
        </div>
        <div class="novedad-item">
            
        </div>
        <div class="novedad-item">
            
        </div>
       
        
    </div>
</div>
    <footer >
        <p>​Laboratorios Lafedar S.A.<br>
              Paraná, Entre Rios.<br>
              ​0343- 4363000 <br>
              ​​www.lafedar.com</p>
    </footer> 
</body>
</html>


<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: white;
    overflow: hidden; /* Saco el scroll */
}

header {
    display: flex; 
    align-items: center; 
    padding: 20px; /* Espaciado alrededor del header */
    background-color: white; 
}

/*BARRA DE BUSQUEDA*/
.search-bar {
    background-color:#1E78C8;
    margin-left: 20px;
    padding: 10px; 
    border: 1px solid #ccc; 
    border-radius: 10px 10px 10px 10px;
    font-family: 'Inter', sans-serif; 
    font-size: 30px; 
    flex: 1; 
    color: white; 
    width: 898px;
    height: 60px;
    top: 55px;
    left: 313px;
    gap: 0px;
    opacity: 0px;

}

.search-bar::placeholder {
    color: white;/* Color del placeholder, blanco con un poco de opacidad */
    
}

header .logo img {
    max-width: 400px;
}

/*NAV CON BOTONES*/
nav {
    display: flex;
    flex-direction: column;
    align-items: flex-start; /* Alinea a la izquierda */
    margin: 20px 0;
}

.nav-btn {
    background-color: #004a99;
    color: white;
    border-radius: 10px 10px 10px 10px;
    border:none;
    padding: 10px 20px;
    margin: 20px 40px; /* Margen vertical para separarlos */
    cursor: pointer;
    width: 250px; 
    height: 50px;
    text-align: center;
     box-shadow: 0 20px 20px rgba(1, 1, 1, 0.6);
    
   
}

.nav-btn:hover {
    background-color: white;
    color: #004a99;
}

.login {
    background-color: #E0E0E0BF;
    padding: 20px;
    max-width: 300px;
    margin: 50px 0px; 
    margin-left: 20px; 
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.login h2 {
    text-align: center;
    margin-bottom: 20px;
}

.login label {
    display: block;
    margin-bottom: 5px;
}

.login input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.login button {
    width: 100%;
    padding: 10px;
    background-color: #003a7a;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.login button:hover {
    background-color: #003a7a;
}

.login img{
  max-width: 60px;
  
}

/*NOVEDADES*/
.novedades {
    margin: 0; 
    padding: 0; 
    position: relative;
    top: -480px; 
    text-align: center;  
    margin-top: 60px; 
}

.novedad-container {
    display: flex; 
    flex-wrap: wrap; /* Permite que los elementos se ajusten a la siguiente fila si no hay suficiente espacio */
    justify-content: center; 
    margin: 20px 0; 
}

.novedad-item {
    background-image: url('storage/Imagenes principal-nueva/NOVEDAD.png'); 
    padding: 20px;
   
    width: 350px; 
    height: 335px; 
    margin: 35px; 
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    opacity: 1; 
}

.novedad-item img {
    max-width: 100%;
    border-radius: 10px;
}

.novedad-item p {
    margin: 30px 0; 
}


.novedad-item a {
    color: #004a99;
    text-decoration: none;
}

.novedad-item a:hover {
    text-decoration: underline;
}

/*FOOTER*/

footer{
  
    background-color: #1E78C8; 
    color: white; 
    text-align: center; 
    padding: 20px; 
    position: fixed; 
    width: 100%; 
    bottom: 0; 
    font-family: 'Inter', sans-serif; 
    font-weight: 200; 
    
}

footer p {
    margin: 0; 
}




</style>