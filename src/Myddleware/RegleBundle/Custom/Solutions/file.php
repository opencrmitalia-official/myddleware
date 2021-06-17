<?php

namespace Myddleware\RegleBundle\Solutions;

class filebase extends filecore
{
    protected function get_last_file($directory, $date_ref)
    {
        $file = parent::get_last_file($directory, $date_ref);

        $fix = '[ -f zzz_fields ] && '
             . '[ "$(head -n1 zzz_fields)" != "$(head -n1 '.$file.')" ] && '
             . 'printf "$(cat zzz_fields)\n$(cat '.$file.')\n" > '.$file;

        ssh2_exec($this->connection, 'cd '.$directory.'; '.$fix);

        return $file;
    }
}

$file = __DIR__.'/file.client.php';
if (file_exists($file)) {
    require_once $file;
} else {
    class file extends filebase {}
}
