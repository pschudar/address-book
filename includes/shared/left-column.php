<!-- Left Column -->
<div class="w3-col m3">
    <!-- Profile -->
    <div class="w3-card w3-round w3-white">
        <div class="w3-container">
            <h4 class="w3-center"><?php echo $_SESSION['full_name']; ?></h4>
            <p class="w3-center"><img src="<?php echo $user->displayProfileImage(); ?>" class="w3-circle" style="height:106px;width:106px" alt="Avatar"></p>
            <hr>
            <p class="w3-center"><a href="<?php echo \utility\Utility::urlFor('index.php'); ?>">Dashboard</a></p>
        </div>
    </div>
    <br>

    <!-- Accordion -->

    <div class="w3-card w3-round w3-margin-bottom">
        <div class="w3-theme-l5">
            <button class="w3-button w3-block w3-theme-l1 w3-left-align"><i class="fa fa-address-book-o fa-fw w3-margin-right"></i> Quick Links - Contacts</button>
            <div id="groups" class="w3-show w3-container">
                <p><i class="fa fa-address-book-o fa-fw w3-margin-right w3-text-theme"></i><a href="<?php echo \utility\Utility::urlFor('/staff/contacts/index.php'); ?>">Contacts Home</a></p>
                <p><i class="fa fa-plus fa-fw w3-margin-right w3-text-theme"></i><a href="<?php echo \utility\Utility::urlFor('/staff/contacts/add.php'); ?>">Add New</a></p>
            </div>
        </div>      
    </div>
    
    <div class="w3-card w3-round w3-margin-bottom">
        <div class="w3-theme-l5">
            <button class="w3-button w3-block w3-theme-l1 w3-left-align"><i class="fa fa-user-circle-o fa-fw w3-margin-right"></i> Quick Links - Users</button>
            <div id="groups" class="w3-show w3-container">
                <p><i class="fa fa-users fa-fw w3-margin-right w3-text-theme"></i><a href="<?php echo \utility\Utility::urlFor('/staff/admins/index.php'); ?>">Users Home</a></p>
                <p><i class="fa fa-user-plus fa-fw w3-margin-right w3-text-theme"></i><a href="<?php echo \utility\Utility::urlFor('/staff/admins/add.php'); ?>">Add New</a></p>
            </div>
        </div>      
    </div>
    
    <div class="w3-card w3-round w3-margin-bottom">
        <div class="w3-theme-l5">
            <button class="w3-button w3-block w3-theme-l1 w3-left-align"><i class="fa fa-tasks fa-fw w3-margin-right"></i> Quick Links - Roles</button>
            <div id="groups" class="w3-show w3-container">
                <p><i class="fa fa-tasks fa-fw w3-margin-right w3-text-theme"></i><a href="<?php echo \utility\Utility::urlFor('/staff/roles/index.php'); ?>">Roles Home</a></p>
            </div>
        </div>      
    </div>

    <!-- End Left Column -->
</div>