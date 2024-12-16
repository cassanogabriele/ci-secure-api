<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>API CodeIgniter/Angular</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            <style>
                body {
                    height: 100vh;
                    overflow: hidden;
                }

                .bg-info {
                    height: calc(100vh - 56px); /* Soustrayez la hauteur de la barre de navigation */
                }
            </style>
        </head>

        <body>
            <nav class="navbar navbar-expand-lg navbar-primary bg-primary">
                <div class="container-fluid">
                    <a class="navbar-brand text-white" routerLink="home">Api CodeIgniter/Angular</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarText">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link text-white active" aria-current="page" routerLink="home">Accueil</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" routerLink="/clients">Clients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">Utilisateurs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white" href="#">Modérateurs</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="bg-info text-center text-white d-flex flex-column justify-content-center align-items-center">
                <h1 class="display-4">Api CodeIgniter/Angular</h1>
                <hr class="my-4">
                <p class="lead">Exemple d'application avec une API CodeIgniter et un backend en Angular</p>
            </div>

            <footer class="footer fixed-bottom bg-primary text-white py-3">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <p>&copy; 2023 Gabriele. Tous droits réservés.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <ul class="list-inline">
                                <li class="list-inline-item "><a class="nav-link text-white" >Accuel</a></li>
                                <li class="list-inline-item"><a class="nav-link text-white" >Clients</a></li>
                                <li class="list-inline-item"><a class="nav-link text-white" href="#">Utilisateurs</a></li>
                                <li class="list-inline-item"><a class="nav-link text-white" href="#">Modérateurs</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </body>
    </html>
