<head>

<script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script>

    <style>
    a:focus {
    background-color: #9B00EA;
}
</style>
</head>

<div class="navbar navbar-inverse set-radius-zero" style="background-color: white; border-color: white;">
    <div class="container">
        <center>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <center> <a class="navbar-brand">

                        <center> <img src="assets\img\logo_ttlams.png" alt="Logo" height="70" width="390" style="border-radius: 8px; border: 1px solid #9B00EA;" /> </center>
                    </a></center>
            </div>
        </center>
        <?php if ($_SESSION['login']) {
        ?>
            <div class="right-div">
                <a href="logout.php" class="btn btn-primary pull-right Btn" style="margin-top: 5px; margin-bottom: 5px;" onclick="return confirmLogout();">
                    <div class="sign"><svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                        </svg></div>

                    <div class="text">Logout</div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>
<!-- LOGO HEADER END-->
<?php if ($_SESSION['login']) {
?>
    <section class="menu-section">
        <div class="container">
            <div class="row ">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse ">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="dashboard.php" class="menu-top" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Dashboard</a></li>
                            <li><a href="issued-books.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Borrowed Books</a></li>
                            <li>
                                <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Account <i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="my-profile.php" style="outline: none;" onfocus="this.style.backgroundColor='#9B00EA'" onblur="this.style.backgroundColor=''"> My Profile</a></li>
                                    <li role="presentation"><a role="menuitem" tabindex="-1" href="change-password.php" style="outline: none;" onfocus="this.style.backgroundColor='#9B00EA'" onblur="this.style.backgroundColor=''"> Change Password</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>
<?php

} else { ?>
    <section class="menu-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="navbar-collapse collapse">
                        <ul id="menu-top" class="nav navbar-nav navbar-right">
                            <li><a href="index.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Home</a></li>
                            <li><a href="index.php#ulogin" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Student Login</a></li>
                            <li><a href="signup.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Student Register</a></li>
                            <li><a href="adminlogin.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Librarian Login</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php } ?>


<script>
    // Function to set active class and styles based on the current URL
    function setActiveOnLoad() {
        var currentURL = window.location.href;
        var menuItems = document.querySelectorAll('.sidebar-nav-item a, #menu-top a');

        menuItems.forEach(item => {
            if (currentURL.includes(item.getAttribute('href'))) {
                item.parentElement.classList.add('active');
                item.style.border = '1px solid #000';
                item.style.borderRadius = '8px';
                item.style.boxShadow = '0 0 5px rgba(0, 0, 0, 0.3)';
                item.style.borderColor = 'lightgrey';
                item.style.fontWeight = 'bold';
                item.style.color = 'black';
            }
        });
    }

    // Function to set active class and styles when a link is clicked
    function setActive(element, event) {
        event.preventDefault();
        console.log('setActive function is triggered!');

        var menuItems = document.querySelectorAll('.sidebar-nav-item a, #menu-top a');
        menuItems.forEach(item => {
            item.parentElement.classList.remove('active');
            item.style.border = '1px solid transparent';
            item.style.borderRadius = '8px';
            item.style.boxShadow = 'none';
            item.style.borderColor = 'transparent';
            item.style.fontWeight = 'normal';
            item.style.color = 'black';
        });

        element.parentElement.classList.add('active');
        element.style.border = '1px solid #000';
        element.style.borderRadius = '8px';
        element.style.boxShadow = '0 0 5px rgba(0, 0, 0, 0.3)';
        element.style.borderColor = 'lightgrey';
        element.style.fontWeight = 'bold';
        element.style.color = 'black';

        var url = element.getAttribute('href');
        window.location.href = url;
    }

    // Call the setActiveOnLoad function on page load
    document.addEventListener('DOMContentLoaded', setActiveOnLoad);
</script>

<script>
    function confirmLogout() {
        // Display a confirmation dialog
        var confirmLogout = confirm("Are you sure you want to logout?");

        // If user confirms, proceed with logout
        if (confirmLogout) {
            return true;
        } else {
            // If user cancels, prevent the default action (logout)
            return false;
        }
    }
</script>