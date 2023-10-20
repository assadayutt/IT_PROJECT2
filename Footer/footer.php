<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Thai:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
    footer {
        background-color: #016dcb;
        color: #fff;
        position: fixed;
        bottom: -150px;
        left: 0;
        right: 0;
        transition: bottom 0.3s ease;
    }


    footer.active {
        bottom: 0;
    }

    body {
        font-family: 'IBM Plex Sans Thai', sans-serif;
    }
    </style>
    <script>
    window.addEventListener('scroll', function() {
        var footer = document.querySelector('footer');
        var windowHeight = window.innerHeight;
        var scrollY = window.scrollY;

        var footerHeight = footer.offsetHeight;

        if (scrollY + windowHeight >= document.body.scrollHeight - footerHeight) {
            footer.classList.add('active');
        } else {
            footer.classList.remove('active');
        }
    });
    </script>
</head>

<body>
    <footer class="bg-dark text-light text-center py-3">
<p>&copy; <?php echo date("Y"); ?> imsproject.online. All Rights Reserved. <a href="/project/Template/Logout.php"><span style="color: red; font-weight: bold;">Logout</span></a></p>

    </footer>
</body>

</html>