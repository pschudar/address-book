<?php
use \utility\Utility;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A free tool written in PHP that allows a person to maintain a contact or address book. Utilizes some HTML 5, Bootstrap 5 (alpha), jQuery and some JavaScript">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
        <link rel="stylesheet" href="<?php echo Utility::urlFor('/assets/css/contacts.styles.css'); ?>">
        <link href="<?php echo Utility::urlFor('/assets/fontello/css/open.source.icons.css'); ?>" rel="stylesheet" type="text/css">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Utility::urlFor('/assets/images/favicon/apple-touch-icon.png'); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-32x32.png'); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Utility::urlFor('/assets/images/favicon/favicon-16x16.png'); ?>">
        <link rel="manifest" href="<?php echo Utility::urlFor('/assets/images/favicon/site.webmanifest'); ?>">
        <title>
            <?php
            if (!Utility::hasPresence($page_title)) {
                $page_title = 'Address Book';
            }
            echo $page_title;
            ?>
        </title>
    </head>
    <body class='bg-light'>

        
