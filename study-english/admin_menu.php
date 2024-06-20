<style>
    header {
        background: #333;
        color: #fff;
        padding: 20px 0;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 80%;
        margin: 0 auto;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
    }

    .logo span {
        color: #ff6b6b;
    }

    nav ul {
        list-style: none;
        display: flex;
        margin: 0;
    }

    nav ul li {
        margin-left: 20px;
    }

    nav ul li a {
        color: #fff;
        text-decoration: none;
        transition: color 0.3s;
    }

    nav ul li a:hover {
        color: #ff6b6b;
    }
</style>
<header>
    <nav>
        <div class="logo">Study<span>English</span></div>
        <ul>
            <li><a href="logout.php">Logout</a></li>

        </ul>
    </nav>
</header>