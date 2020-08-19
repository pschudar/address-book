<?php
use \utility\Utility;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A free tool written in PHP that allows a person to  build and maintain an address book. Utilizes HTML5, W3.CSS Framework, jQuery and some JavaScript">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/styles.css'); ?>">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/address-book.css'); ?>">
        <link id="theme-style" rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/themes/blue-grey.css'); ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Utility::urlFor('/assets/images/favicon/apple-touch-icon.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?php echo Utility::urlFor('/assets/images/favicon/site.webmanifest'); ?>">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
        <title>
            <?php
            if (!Utility::hasPresence($page_title)) {
                $page_title = 'Address Book';
            }
            echo $page_title;
            ?>
        </title>
        <script>
            if (localStorage.getItem('stylePreference') !== null) {
                var stylePref = localStorage.getItem('stylePreference');
            } else {
                stylePref = "<?php echo Utility::urlFor('/assets/css/themes/blue-grey.css'); ?>";
            }
            document.getElementById('theme-style').href = stylePref;
        </script>
    </head>
    <body id="site-mode" class="w3-theme-light">

        
