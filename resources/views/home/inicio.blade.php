<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

    <section class="container">
    <div class="login">
        <h2>INICIO DE SESION</h2>
        <form action="login.php" method="post">
        <label for="usuario"><strong>Usuario</strong></label>
            <input type="text" id="usuario" name="usuario" required>

            <label for="contrasena"><strong>Contraseña</strong></label>
            <input type="password" id="contrasena" name="contrasena" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>

    <div class="novedades">
        <h1>____________________NOVEDADES____________________</h1>
        <div class="cards-contenedor">
            <div class="card">
                <img src="{{ asset('storage/Imagenes principal-nueva/NOVEDAD.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Novedad 1</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Ver mas</a>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('storage/Imagenes principal-nueva/NOVEDAD.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Novedad 2</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Ver mas</a>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('storage/Imagenes principal-nueva/NOVEDAD.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Novedad 3</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Ver mas</a>
                </div>
            </div>
            <div class="card">
                <img src="{{ asset('storage/Imagenes principal-nueva/NOVEDAD.png') }}" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title">Novedad 4</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary">Ver mas</a>
                </div>
            </div>
            
        </div>
    </div>
</section>
  
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
    padding: 20px; 
    background-color: white; 
}

/* CONTAINER LOGIN Y NOVEDADES */
.container {
    display: flex; 
    justify-content: space-between; 
    align-items: flex-start; 
    margin: 40px 20px; 
}

/* BARRA DE BUSQUEDA */
.search-bar {
    background-color: #1E78C8;
    margin: 0px 60px; 
    margin-left: 110px;
    padding: 10px; 
    border: none; 
    border-radius: 10px;
    font-family: 'Inter', sans-serif; 
    font-size: 30px; 
    flex: 1; 
    color: white; 
    height: 60px;
}

.search-bar::placeholder {
    color: white;
}

header .logo img {
    max-width: 400px;
}

/* NAV CON BOTONES */
nav {
    display: flex;
    flex-direction: column;
    align-items: flex-start; 
    margin: 20px 0;
}

.nav-btn {
    background-color: #004a99;
    color: white;
    border-radius: 10px;
    border: none;
    padding: 10px 20px;
    margin: 20px 30px; 
    margin-left: 57px; 
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

/* LOGIN */   
.login {
    background-color: #E0E0E0BF;
    padding: 20px;
    max-width: 300px; 
    margin-right: 20px; 
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    height: 350px;
}

.login h2 {
    text-align: center;  
    margin-bottom: 20px; 
    font-size: 25px;     
    color: #1C547C;
    font-weight: bold;
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

/* NOVEDADES */
.novedades {
    display: flex;
    flex-direction: column; 
    align-items: center;      
    justify-content: flex-start;
    margin-top: -90px; 
    width: 100%; 
    max-width: 800px; 
}

.novedades h1 {
    margin-top: 0; 
    margin-bottom: 20px; 
    color: #196AB2;
    font-weight: 1000;
    font-size: 40px;
    margin-left: 370px;
}

.cards-contenedor {
    display: flex;              
    justify-content: space-between;  
    flex-wrap: nowrap;         
    gap: 70px; /* espacio entre las tarjetas */               
    margin-top: 10px;   
    margin-left: 400px;       
}

.card {
    width: 300px; 
    height: 350px; 
    border: 1px solid #ddd;   
    border-radius: 10px;      
    overflow: hidden;         
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card img {
    max-width: 300px;           
    height: 170px;             
}

/* FOOTER */
footer {
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
    padding: 0;
}

/* Media Queries para pantallas más pequeñas */
@media (max-width: 768px) {
    .login {
        width: 90%; /* Aumentar el ancho del contenedor de login */
        margin: 20px 0; /* Espacio vertical */
    }

    .nav-btn {
        width: 90%; /* Botones ocupan el 90% del ancho */
    }
}

@media (max-width: 480px) {
    .search-bar {
        font-size: 16px; /* Reducir tamaño de fuente */
    }

    .login {
        padding: 10px; /* Ajustar el padding */
    }
}

</style>