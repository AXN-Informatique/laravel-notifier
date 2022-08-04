<?php

use Axn\Notifier\Notify;

return [

    /*
    |--------------------------------------------------------------------------
    | Default view
    |--------------------------------------------------------------------------
    |
    | The name of the blade view to use by default:
    |
    | - 'notifier::bootstrap-5'
    | - 'notifier::bootstrap-5-toast'
    | - 'notifier::bootstrap-5-alert'
    | - 'notifier::bootstrap-5-alert-advanced'
    | - 'notifier::bootstrap-4'
    | - 'notifier::bootstrap-4-toast'
    | - 'notifier::bootstrap-4-alert'
    | - 'notifier::bootstrap-4-alert-advanced'
    | - 'notifier::sweetalert2'
    | - 'notifier::pnotify-5'
    |
    | Or for example, for your custom implementation:
    | 'components.custom-notify'
    |
    */

    'default_view' => 'notifier::sweetalert2',

    /*
    |--------------------------------------------------------------------------
    | Sort by type
    |--------------------------------------------------------------------------
    |
    | Allows you to group messages according to their type
    | rather than by the order of their declaration.
    |
    */

    'sort_by_type' => true,

    /*
    |--------------------------------------------------------------------------
    | Sort type order
    |--------------------------------------------------------------------------
    |
    | If the sort_by_type option is set to "true" here you can define the order
    | in which messages should appear according to their type.
    |
    */

    'sort_type_order' => [
        Notify::ERROR,
        Notify::WARNING,
        Notify::SUCCESS,
        Notify::INFO,
    ],

    /*
    |--------------------------------------------------------------------------
    | Group by type
    |--------------------------------------------------------------------------
    |
    | This option allows you to group messages of the same type
    | in a single notification.
    |
    */

    'group_by_type' => false,

    /*
    |--------------------------------------------------------------------------
    | Grouped messages formats
    |--------------------------------------------------------------------------
    |
    | If the group_by_type option is set to "true" here you can define
    | the HTML format of the grouped messages.
    |
    | They use the sprintf PHP function.
    |
    | - group_messages_format: The HTML element that wraps the grouped messages
    | - group_title_format: The HTML of the title if present
    | - group_message_format : The HTML of the message with possibly its title in front
    |
    */

    'group_messages_format' => '<ul class="list-unstyled mb-0">%s</ul>',

    'group_title_format' => '<strong>%s&nbsp;:&nbsp;</strong>',

    'group_message_format' => '<li>%s%s</li>',

];
