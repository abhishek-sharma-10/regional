<!DOCTYPE html>
<html>
    <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Star Infotech College | <?= $this->pageTitle ?></title>
    <?php
        require_once("header.php");
    ?>
    </head>
    <body>
        <?php
            if(isset($_SESSION) && isset($_SESSION['student'])){
                require_once("navbar.php");
            }
            $this->loadView();
            require_once("footer.php");
        ?>
        
    </body>
    
</html>