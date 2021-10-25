<nav>
    <div class=" vh-100 d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Sidebar</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link text-white
                 <?php if (!isset($_GET['page'])) {
                        echo 'active';
                    } ?>
                    " aria-current="page">
                    Spectacles
                </a>
            </li>
            <li>
                <a href="?page=salles" class="nav-link text-white 
                <?php if ($_GET['page'] === "salles") {
                    echo 'active';
                } ?>">
                    Salles
                </a>
            </li>
            <li>
                <a href="?page=prestataires" class="nav-link text-white 
                <?php if ($_GET['page'] === "prestataires") {
                    echo 'active';
                } ?>">
                    Prestataires
                </a>
            </li>
            <li>
                <a href="?page=types" class="nav-link text-white 
                <?php if ($_GET['page'] === "types") {
                    echo 'active';
                } ?>">
                    Types
                </a>
            </li>

        </ul>


    </div>
</nav>