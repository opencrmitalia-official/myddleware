<?php


namespace Myddleware\RegleBundle\Solutions;

class microsoftsql extends microsoftsqlbase
{
    protected function generateId($param,$record)
    {
        switch ($param['rule']['id']) {
            case 'asdasdasdasd':
                return $record['XXXX'].'_'.$record['YYYY'].'_'.$record['ZZZZ'];
        }

        return uniqid('', true);
    }

    // Redifine read function
    public function read($param)
    {
        // Campure ruleId on simulation
        $ruleId = $this->getCurrentRuleId($param);

        // Change reference date for a specific rule
        if ($ruleId == 'asdasdasdasd' || $ruleId == 'asdasdasdasd') {
            $param['date_ref'] = '1999-01-01 00:00:00';
        }

        // Call standard read function
        $result = parent::read($param);

        // Return to caller
        return $result;
    }
}
