<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
            <a class="nav-link" href="<?php echo "$url_base/"?>index.php">Início <span class="sr-only">(current)</span></a>
            </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" action="result.php">
            <input class="form-control mr-sm-2" type="text" placeholder="Pesquisar" aria-label="Pesquisar" name="search">
            <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Pesquisar</button>
            </form>              
            <li class="nav-item navbar-nav">
            <a class="nav-link" href="about.php">Sobre</a>
            </li>            
        </div>

        <?php if (!isset($_SESSION["login"])) : ?>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Login
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form class="form-signin" method="post" action="result.php">
                    <h1 class="h3 mb-3 font-weight-normal">Login</h1>
                    <label for="inputUser" class="sr-only">Usuário</label>
                    <input type="text" id="inputUser" class="form-control" name="username" placeholder="Usuário" required autofocus>
                    <label for="inputPassword" class="sr-only">Senha</label>
                    <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Senha" required>
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                    <p class="mt-5 mb-3 text-muted"><?= $errorMsg ?></p>
                </form>
                </div>
                </div>
            </div>
            </div>
        <?php endif; ?>
    </div>
</nav>