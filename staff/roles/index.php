<?php

use \user\Role,
    \utility\Utility,
    \paginate\Pagination;

require_once('../../includes/initialize.php');

# enforce authentication
$session->requireLogin();
# enforce persmissions
if (!$session->hasPermission($_SESSION['role_permissions'], 1)) {
    Utility::accessDenied();
}

$page_title = 'Address Book: View Roles';
require_once(SHARED_PATH . 'public_header.php');

# define get variable 'page'
$page = filter_input(INPUT_GET, 'page', 519);
# define current page
$current_page = $page ?? 1; # If page is not set, page is defaults to 1
# count records
$total_count = Role::countAll();
# per page
$per_page = 10;
# instantiate paginate
$pagination = new Pagination($current_page, $per_page, $total_count);
$roles = Role::getAll($per_page, $pagination->offset());

## Note: class='d-none d-md-table-cell' hides the cell below md break point
?>

<main role="main"> 
    <div class='container mt-4'>
        <div class="my-3 p-3 bg-white rounded shadow">
            
            <span id="sessionMsg"><?php echo $session->displaySessionMessage(); ?></span>

            <table class="table table-responsive media-body pb-3 lh-125 border-bottom border-gray">
                <thead>
                    <tr>
                        <th scope="col">Role Name</th>
                        <th scope="col">Permissions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role) : ?>
                        <tr>
                            <td><?php echo $role->role_name; ?>
                                <div class="clearfix"></div>

                                <span id="<?php echo Utility::h($role->role_id); ?>" class="hidden small">
                                    <?php #echo $role->displayAdminLinks('roles', $session);  ?>
                                </span>

                            </td>
                            <td>
                                <div class="row">
                                    <?php
                                    $card_classes = 'text-dark'; # card bg-dark text-white
                                    echo "<div class='col-4'>\r\n";
                                    echo "<div class='{$card_classes}'>\r\n";
                                    foreach ($role->getRolePermissions($role->role_id) AS $r) {
                                        $permCount = \user\Admin::count();
                                        echo '' . $r->perm_desc . "<br>\r\n";
                                        if ($permCount % 4 == 0) {
                                            echo "</div>\r\n</div>\r\n
                                    <div class='col-4'>\r\n
                                            <div class='{$card_classes}'>";
                                        }
                                    }
                                    echo "</div>\r\n</div>\r\n";
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="mx-auto">
                <?php echo $pagination->pageLinks(Utility::urlFor('/staff/roles/index.php')); ?>
            </div>
        </div>
    </div>
</main>
<?php
require_once(SHARED_PATH . 'public_footer.php');
