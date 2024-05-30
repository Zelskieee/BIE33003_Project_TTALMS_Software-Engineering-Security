<head>
    <script src="https://kit.fontawesome.com/641ebcf430.js" crossorigin="anonymous"></script> 
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

    <style>
        .Btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 45px;
            height: 45px;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition-duration: .3s;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
            background-color: rgb(5, 65, 65);
        }

        /* plus sign */
        .sign {
            width: 100%;
            transition-duration: .3s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sign svg {
            width: 17px;
        }

        .sign svg path {
            fill: white;
        }

        /* text */
        .text {
            position: absolute;
            right: 0%;
            width: 0%;
            opacity: 0;
            color: white;
            font-size: 1.2em;
            font-weight: 600;
            transition-duration: .3s;
        }

        /* hover effect on button width */
        .Btn:hover {
            width: 125px;
            border-radius: 40px;
            transition-duration: .3s;
        }

        .Btn:hover .sign {
            width: 30%;
            transition-duration: .3s;
            padding-left: 20px;
        }

        /* hover effect button's text */
        .Btn:hover .text {
            opacity: 1;
            width: 70%;
            transition-duration: .3s;
            padding-right: 10px;
        }

        /* button click effect*/
        .Btn:active {
            transform: translate(2px, 2px);
        }
    </style>
</head>

<div class="navbar navbar-inverse set-radius-zero" style="background-color: white; border-color: white;">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand">
                <img src="assets\img\logo_ttlams.png" alt="Image" height="65" width="390" style="border-radius: 8px; border: 1px solid #9B00EA;" />
            </a>
        </div>


        <div class="right-div" style="margin-left: 90%;">
            <button class="Btn" onclick="return confirmLogout();">

                <div class="sign"><svg viewBox="0 0 512 512">
                        <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                    </svg></div>

                <div class="text">Logout</div>
            </button>
        </div>





    </div>
</div>
<!-- LOGO HEADER END-->
<section class="menu-section">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="navbar-collapse collapse">
                    <ul id="menu-top" class="nav navbar-nav navbar-right" style="margin-right: 120px;">
                        <li><a href="dashboard.php" class="menu-top-active" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Dashboard</a></li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Categories <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-category.php">Add Category</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-categories.php">Manage Categories</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Authors <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-author.php">Add Author</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-authors.php">Manage Authors</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Books <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="add-book.php">Add Book</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-books.php">Manage Books</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Borrow Books <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="issue-book.php">Borrow New Book</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1" href="manage-issued-books.php">Manage Borrow Books</a></li>
                            </ul>
                        </li>
                        <li><a href="reg-students.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Student</a></li>
                        <li><a href="change-password.php" style="transition: all 0.3s ease; border-radius: 5px; color: black;" onmouseover="this.style.borderRadius='5px'; this.style.backgroundColor='#ccc'" onmouseout="this.style.backgroundColor='transparent'"> Change Password</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function confirmLogout() {
        // Display a confirmation dialog
        var confirmLogout = confirm("Are you sure you want to log out?");

        // If user confirms, redirect to the specified URL
        if (confirmLogout) {
            window.location.href = "../adminlogin.php";
            return true; // This line is optional
        } else {
            return false; // This line is optional
        }
    }
</script>