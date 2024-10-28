<?php

function lang($phrase)
{
    static $lang = array(
        // Example
        // 'Message' => 'Welcome',
        // 'ADMIN'   => 'Administer'

        // Navbar Links
        'Home_Admin'     => 'Home',
        'CATEGORIES'     => 'Categories',
        'ITEMS'          => 'Items',
        'MEMBERS'        => 'Members',
        'COMMENTS'       => 'Comments',
        'STATISTICS'     => 'Statistics',
        'LOGS'           => 'Logs',
        'Edit_Profile'   => 'Edit Profile',
        'Settings_Page'  => 'Settings',
        'Logout_Account' => 'Logout'
    );
    return $lang[$phrase];
}

?>