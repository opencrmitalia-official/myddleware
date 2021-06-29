<?php

namespace Myddleware\RegleBundle\Solutions;

class filebase extends filecore
{

    protected function get_last_file($directory, $date_ref)
    {
        $file = parent::get_last_file($directory, $date_ref);

        /*
        $fix = '[ -f zzz_fields ] && '
             . '[ "$(head -n1 zzz_fields)" != "$(head -n1 '.$file.')" ] && '
             . 'cat zzz_fields '.$file.' > '.$file.'.tmp && '
             . 'mv '.$file.' '.$file.'.bak && '
             . 'mv '.$file.'.tmp '.$file.' && '
             . 'echo updated ||'
             . 'echo ignored';

        $stream = ssh2_exec($this->connection, 'cd '.$directory.'; '.$fix);
        stream_set_blocking($stream, true);
        $fixMessage = stream_get_contents($stream);
        */

        return $file;
    }

}

$file = __DIR__.'/file.client.php';
if (file_exists($file)) {
    require_once $file;
} else {
    class file extends filebase {}
}
