<nav class="navbar navbar-expand-lg navbar-light " style="background-color: #e3f2fd;">
    <div class="container-fluid" style="background-color: #e3f2fd;">
        <a class="navbar-brand" href="/">Inicio</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
            aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarScroll">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">


                @can('p_admin')
                    <li class="nav-item">
                        <a class="nav-link" href="">Administrador</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Administrador</a>
                    </li>
                @endcan

            </ul>

        </div>


        <div>
            <div class="nav-item dropdown" id="navbarNavDropdown">
                <ul class="navbar-nav">

                </ul>
            </div>
        </div>

    </div>
</nav>
