<header class="header u-container">
    <nav class="header__nav d-flex justify-content-between align-items-center">
        <a href="#" class="header__logo-box">
            <img src="assets/img/liag.png" alt="LIAG" class="header__logo img-fluid">
        </a>
        <ul class="header__nav-list d-lg-flex d-none flex-row list-unstyled my-0">
            <li>
                <a href="#">ACT</a>
            </li>
            <li>
                <a href="#">Artigos</a>
            </li>
            <li>
                <a href="#">Jogos Educativos</a>
            </li>
            <li>
                <a href="#">Métodos</a>
            </li>
            <li id="menu_login">
                <a href="login.php">Login</a>
            </li>
        </ul>
        <div class="header__nav-button d-lg-none d-flex flex-column justify-content-between u-cursor-pointer" onclick="">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </nav>
</header>
<script>
    if (typeof currentPage == "string" && document.getElementById(`menu_${currentPage}`)) {
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].classList.add('active');
        document.getElementById(`menu_${currentPage}`).getElementsByTagName('a')[0].href = "#";
    }
</script>
<script src="<?php echo $CFG['system_url'] ?>js/header.js"></script>