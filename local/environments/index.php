<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *  '<type>' => [
 *      'name' => '<Full name>',
 *      'path' => '<directory>'
 *  ]
 * ];
 * ```
 */

return [
    'dev' => [
        'name' => 'Development',
        'path' => 'dev'
    ],
    'dev_crm_test' => [
        'name' => 'Development CRM',
        'path' => 'dev_crm_test'
    ],
    'prod' => [
        'name' => 'Production',
        'path' => 'prod',
    ],
];
