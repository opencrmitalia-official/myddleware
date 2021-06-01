<?php
/*********************************************************************************
 * This file is part of Myddleware.

 * @package Myddleware
 * @copyright Copyright (C) 2013 - 2015  Stéphane Faure - CRMconsult EURL
 * @copyright Copyright (C) 2015 - 2016  Stéphane Faure - Myddleware ltd - contact@myddleware.com
 * @link http://www.myddleware.com

 This file is part of Myddleware.

 Myddleware is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Myddleware is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Myddleware.  If not, see <http://www.gnu.org/licenses/>.
*********************************************************************************/

$moduleFields = [
    'users' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'username' => ['label' => 'Username', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'password' => ['label' => 'Password', 'type' => PasswordType::class, 'type_bdd' => 'varchar(255)', 'required' => 0],
        'createpassword' => ['label' => 'Create password', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'firstname' => ['label' => 'Firstname', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'lastname' => ['label' => 'Lastname', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'email' => ['label' => 'Email', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'auth' => ['label' => 'Auth', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            'email' => 'Email-based self-registration',
            'manual' => 'Manual accounts',
            'nologin' => 'No login',
            'cas' => 'CAS server (SSO)',
            'db' => 'External database',
            'fc' => 'FirstClass server',
            'imap' => 'IMAP server',
            'ldap' => 'LDAP server',
            'mnet' => 'MNet authentication',
            'nntp' => 'NNTP server',
            'none' => 'No authentication',
            'pam' => 'PAM (Pluggable Authentication Modules)',
            'pop3' => 'POP3 server',
            'radius' => 'RADIUS server',
            'shibboleth' => 'Shibboleth',
            'webservice' => 'Web services authentication',
        ]],
        'idnumber' => ['label' => 'Id number', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'lang' => ['label' => 'Language', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'calendartype' => ['label' => 'Calendar type', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'theme' => ['label' => 'Theme ', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timezone' => ['label' => 'Timezone', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'mailformat' => ['label' => 'Mail format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'description' => ['label' => 'Description', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'city' => ['label' => 'City', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'country' => ['label' => 'Country', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'firstnamephonetic' => ['label' => 'Firstname phonetic', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'lastnamephonetic' => ['label' => 'Lastname phonetic', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'middlename' => ['label' => 'Middlename', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'alternatename' => ['label' => 'Alternatename', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
    ],

    'courses' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'fullname' => ['label' => 'Full name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'shortname' => ['label' => 'Short name  ', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'categoryid' => ['label' => 'Category ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'idnumber' => ['label' => 'ID number  ', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'summary' => ['label' => 'Summary', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'summaryformat' => ['label' => 'Summary format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'MOODLE',
            '1' => 'HTML',
            '2' => 'PLAIN',
            '4' => 'MARKDOWN',
        ]],
        'format' => ['label' => 'Format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            'singleactivity' => 'Single activity format',
            'social' => 'Social format',
            'topics' => 'Topics format',
            'weeks' => 'Weekly format',
        ]],
        'showgrades' => ['label' => 'Showgrades', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'newsitems' => ['label' => 'News items', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'startdate' => ['label' => 'Start date', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'numsections' => ['label' => 'Num sections', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'maxbytes' => ['label' => 'Max bytes', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'Site upload limit (2MB)',
            '2097152' => '2MB',
            '1048576' => '1MB',
            '512000' => '500KB',
            '102400' => '100KB',
            '51200' => '50KB',
            '10240' => '10KB',
        ]],
        'showreports' => ['label' => 'Show reports', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'visible' => ['label' => 'Visible', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'hiddensections' => ['label' => 'Hidden sections', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'Hidden sections are shown in collapsed form',
            '1' => 'Hidden sections are completely invisible',
        ]],
        'groupmode' => ['label' => 'Group mode', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'No groups',
            '1' => 'Separate groups',
            '2' => 'Visible groups',
        ]],
        'groupmodeforce' => ['label' => 'Group mode force', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'defaultgroupingid' => ['label' => 'default grouping ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'enablecompletion' => ['label' => 'Enable completion', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'completionnotify' => ['label' => 'Completion notify', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'lang' => ['label' => 'Language', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'forcetheme' => ['label' => 'Force theme', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
    ],

    'groups' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'name' => ['label' => 'Name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'description' => ['label' => 'Description', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'descriptionformat' => ['label' => 'Description format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'MOODLE',
            '1' => 'HTML',
            '2' => 'PLAIN',
            '4' => 'MARKDOWN',
        ]],
        'enrolmentkey' => ['label' => 'Enrolment key', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'idnumber' => ['label' => 'ID number', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'manual_enrol_users' => [
        'roleid' => ['label' => 'Role ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'timestart' => ['label' => 'Time start', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timeend' => ['label' => 'Time end', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'suspend' => ['label' => 'Description format', 'type' => 'bool', 'type_bdd' => 'bool', 'required' => 0],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'get_enrolments_by_date' => [
        'roleid' => ['label' => 'Role ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
        'status' => ['label' => 'Status', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'enrol' => ['label' => 'Enrolment method', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timestart' => ['label' => 'Time start', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timeend' => ['label' => 'Time end', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timecreated' => ['label' => 'Time created', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timemodified' => ['label' => 'Time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'manual_unenrol_users' => [
        'roleid' => ['label' => 'Role ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 1],
		'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],

    ],

    'notes' => [
        'publishstate' => ['label' => 'Publish state ', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            'personal' => 'Personal',
            'course' => 'Course',
            'site' => 'Site',
        ]],
        'text' => ['label' => 'Text', 'type' => TextType::class, 'type_bdd' => 'text', 'required' => 0],
        'format' => ['label' => 'Format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'MOODLE',
            '1' => 'HTML',
            '2' => 'PLAIN',
            '4' => 'MARKDOWN',
        ]],
        'clientnoteid' => ['label' => 'Client note id', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'get_users_completion' => [
        'instance' => ['label' => 'Instance', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'section' => ['label' => 'Section', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'moduletype' => ['label' => 'Module', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'completionstate' => ['label' => 'Completion state', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'modulename' => ['label' => 'Module name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'coursemodulename' => ['label' => 'Course module name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timemodified' => ['label' => 'Time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'coursemoduleid' => ['label' => 'Course module ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'get_course_completion_by_date' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timeenrolled' => ['label' => 'Time enrolled', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timestarted' => ['label' => 'Time start', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timecompleted' => ['label' => 'Time completed', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'get_users_last_access' => [
        'lastaccess' => ['label' => 'Last access', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],

    'get_user_compentencies_by_date' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'status' => ['label' => 'Status', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'proficiency' => ['label' => 'Proficiency', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'grade' => ['label' => 'Grade', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timecreated' => ['label' => 'Time created', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timemodified' => ['label' => 'Time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'usermodified' => ['label' => 'User modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_shortname' => ['label' => 'Competency shortname ', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_description' => ['label' => 'Competency description ', 'type' => TextType::class, 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_descriptionformat' => ['label' => 'Competency description format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'option' => [
            '0' => 'MOODLE',
            '1' => 'HTML',
            '2' => 'PLAIN',
            '4' => 'MARKDOWN',
        ]],
        'competency_idnumber' => ['label' => 'Competency idnumber', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_path' => ['label' => 'Competency path', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_sortorder' => ['label' => 'Competency sort order', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_ruletype' => ['label' => 'Competency rule type', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_ruleoutcome' => ['label' => 'Competency rule outcome', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_ruleconfig' => ['label' => 'Competency rule config', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_scaleconfiguration' => ['label' => 'Competency scale configuration', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_timecreated' => ['label' => 'Competency time created', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_timemodified' => ['label' => 'Competency time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'competency_usermodified' => ['label' => 'Competency user modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'competencyid' => ['label' => 'Competency ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'reviewerid' => ['label' => 'Reviewer ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'competency_competencyframeworkid' => ['label' => 'Competency framework ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'competency_parentid' => ['label' => 'Competency parent ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'competency_scaleid' => ['label' => 'Competency scale ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
    ],

    'get_competency_module_completion_by_date' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timecreated' => ['label' => 'Time created', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timemodified' => ['label' => 'Time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'usermodified' => ['label' => 'User modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'sortorder' => ['label' => 'Sort order', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'modulename' => ['label' => 'Module name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'coursemodulename' => ['label' => 'Course module name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'ruleoutcome' => ['label' => 'Rule outcome', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'cmid' => ['label' => 'Course module ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'competencyid' => ['label' => 'Competency ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 0, 'relate' => true],
    ],

    'get_user_grades' => [
        'id' => ['label' => 'ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timecreated' => ['label' => 'Time created', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'timemodified' => ['label' => 'Time modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'usermodified' => ['label' => 'User modified', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'itemid' => ['label' => 'Item ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'rawgrade' => ['label' => 'Raw grade', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'rawgrademax' => ['label' => 'Raw grade max', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'rawgrademin' => ['label' => 'Raw grade min', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'rawscaleid' => ['label' => 'Raw scale ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'finalgrade' => ['label' => 'Final grade', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'hidden' => ['label' => 'Hidden', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'locked' => ['label' => 'Locked', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'locktime' => ['label' => 'Lock time', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'exported' => ['label' => 'Exported', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'overridden' => ['label' => 'Overridden', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'excluded' => ['label' => 'Excluded', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'feedback' => ['label' => 'Feedback ', 'type' => TextType::class, 'type_bdd' => 'varchar(255)', 'required' => 0],
        'feedbackformat' => ['label' => 'Feedback format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'information' => ['label' => 'Information ', 'type' => TextType::class, 'type_bdd' => 'varchar(255)', 'required' => 0],
        'informationformat' => ['label' => 'Information format', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'information' => ['label' => 'Information ', 'type' => TextType::class, 'type_bdd' => 'varchar(255)', 'required' => 0],
        'aggregationstatus' => ['label' => 'Aggregation status', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'aggregationweight' => ['label' => 'Aggregation weight', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'itemname' => ['label' => 'Item name', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'course_fullname' => ['label' => 'Course fullname', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
        'course_shortname' => ['label' => 'Course shortname', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0],
		'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'courseid' => ['label' => 'Course ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],
	
    'group_members' => [
        'groupid' => ['label' => 'Group ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
        'userid' => ['label' => 'User ID', 'type' => 'varchar(255)', 'type_bdd' => 'varchar(255)', 'required' => 0, 'required_relationship' => 1, 'relate' => true],
    ],
];


// Metadata override if needed
$file = __DIR__.'/../../../Custom/Solutions/lib/moodle/metadata.php';
if (file_exists($file)) {
    require_once $file;
}