<header class="header u-container">
    <nav class="header__nav d-flex justify-content-between align-items-center">
        <a href="#" class="header__logo-box">
            <img src="assets/img/liag.png" alt="LIAG" class="header__logo img-fluid">
        </a>
        <ul class="header__nav-list d-flex flex-nowrap flex-row list-unstyled my-0">
            <li>
                <a href="#">ACT</a>
            </li>
            <li id="menu_searchCatalogs">
                <a href="searchCatalogs.php">Catálogos</a>
            </li>
            <li id="menu_login">
                <a href="login.php">Login</a>
            </li>
        </ul>
    </nav>
</header>
<script>
    if (typeof currentPage == "string" && document.getElementById(`menu_${currentPage}`)) {
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].classList.add('active');
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].href = "#";
    }
</script>
<script src="<?php echo $CFG['system_url'] ?>js/header.js"></script>