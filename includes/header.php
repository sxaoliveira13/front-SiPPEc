<header class="header u-container">
    <nav class="header__nav d-flex d-flex flex-sm-row flex-column justify-content-between align-items-center">
        <a href="https://liag.ft.unicamp.br/" class="header__logo-box">
            <img src="assets/img/liag.png" alt="LIAG" class="header__logo img-fluid">
        </a>
        <ul class="header__nav-list d-flex flex-nowrap flex-row list-unstyled mb-0 mt-sm-0 mt-5">
            <li>
                <a class="text-nowrap" href="https://liag.ft.unicamp.br/act/sobre/">ACT</a>
            </li>
            <li id="menu_searchCatalogs">
                <a class="text-nowrap" href="searchCatalogs.php">Catálogos</a>
            </li>
            <li id="menu_login">
                <a class="text-nowrap" href="login.php">Login</a>
            </li>
        </ul>
    </nav>
</header>
<script>
    promiseHeaderLoad();
    if (typeof currentPage == "string" && document.getElementById(`menu_${currentPage}`)) {
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].classList.add('active');
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].href = "#";
    }
</script>