<?php

chdir('/var/www/html');

echo 'Total: ';

require_once 'config.php';
if (file_exists('config_override.php')) {
    include_once 'config_override.php';
}

require_once 'include/Webservices/Relation.php';
require_once 'vtlib/Vtiger/Module.php';
require_once 'includes/main/WebUI.php';
require_once 'modules/Contacts/Contacts.php';

global $adb, $current_user;
$user = new Users();
$user->retrieveCurrentUserInfoFromFile(1);
$current_user = $user;

function create_contact()
{
    $ticket = Vtiger_Record_Model::getCleanInstance('Contacts');
    $ticket->set('assigned_user_id', '1');
    $ticket->set('ticket_title', 'Contact'.time().'-'.rand(1000, 9999));
    $ticket->set('mode', '');
    $ticket->save();
    return $ticket->get('id');
}

$count = 0;
for ($i = 0; $i < 200; $i++) {
    $id = create_contact();
    if ($id) {
        $count++;
    }
}
echo $count."\n";
