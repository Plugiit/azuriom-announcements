<?php

return [
    'nav' => [
        'title' => 'Announcements',
        'announcements' => 'Announcements',
        'settings' => 'Settings',
    ],

    'permission' => 'Manage announcements (bars and popups)',

    'announcements' => [
        'title' => 'Announcements',
        'create' => 'Create an announcement',
        'edit' => 'Edit announcement :title',
        'empty' => 'No announcement yet.',
        'info' => 'Several bars can be active at the same time; only one popup is displayed per page load (the highest priority one not already seen).',

        'content' => 'Content',
        'display' => 'Display',
        'schedule' => 'Schedule',
        'targeting' => 'Targeting',

        'fields' => [
            'title' => 'Title',
            'message' => 'Message',
            'format' => 'Message format',
            'type' => 'Type',
            'style' => 'Style',
            'color' => 'Background color',
            'text_color' => 'Text color',
            'button_label' => 'Button text',
            'button_url' => 'Button link',
            'starts_at' => 'Start',
            'ends_at' => 'End',
            'has_countdown' => 'Display a countdown until the end date',
            'audience' => 'Audience',
            'roles' => 'Roles',
            'pages' => 'Pages',
            'page_patterns' => 'URL patterns',
            'is_dismissible' => 'Can be closed by the visitor (stored in the browser)',
            'frequency' => 'Display frequency',
            'priority' => 'Priority',
            'is_enabled' => 'Enabled',
            'period' => 'Period',
            'status' => 'Status',
        ],

        'help' => [
            'message' => 'Markdown: **bold**, *italic*, [link](https://…). Simple HTML: <strong>, <em>, <a>, <br>, <ul>… (other tags and attributes are removed).',
            'button' => 'Optional. Absolute (https://…) or relative (/shop) link.',
            'dates' => 'Leave empty for no start or no end. Website timezone.',
            'page_patterns' => 'One pattern per line, * matches anything. Examples: vote, forum/*, shop/categories/*. Use / for the home page.',
            'priority' => 'Higher priority announcements are displayed first.',
            'dismissible' => 'Changing the title, message or button displays the announcement again to visitors who closed it.',
            'frequency' => 'The popup can always be closed; the frequency sets when it is displayed again.',
        ],

        'formats' => [
            'markdown' => 'Markdown',
            'html' => 'Simple HTML',
        ],
        'types' => [
            'bar' => 'Bar at the top of the website',
            'popup' => 'Popup (modal window)',
        ],
        'styles' => [
            'info' => 'Information (blue)',
            'success' => 'Success (green)',
            'warning' => 'Warning (orange)',
            'danger' => 'Danger (red)',
            'custom' => 'Custom colors',
        ],
        'audiences' => [
            'all' => 'Everyone',
            'guests' => 'Guests',
            'users' => 'Logged in members',
            'roles' => 'Specific roles',
        ],
        'pages_options' => [
            'all' => 'All pages',
            'home' => 'Home page',
            'shop' => 'Shop',
            'pattern' => 'URL pattern',
        ],
        'frequencies' => [
            'once' => 'Only once',
            'session' => 'Once per browsing session',
            'always' => 'On every page load',
        ],
        'statuses' => [
            'active' => 'Running',
            'scheduled' => 'Scheduled',
            'expired' => 'Ended',
            'disabled' => 'Disabled',
        ],
        'no_start' => 'Now',
        'no_end' => 'no end',
    ],

    'settings' => [
        'title' => 'Announcements settings',
        'bar_mode' => 'Display of several active bars',
        'bar_modes' => [
            'stacked' => 'Stacked',
            'rotate' => 'Rotating (one bar at a time)',
        ],
        'rotation_delay' => 'Display time of each rotating bar',
        'seconds' => 'seconds',
        'integration' => 'Theme integration',
        'integration_info' => 'Bars are automatically inserted right after the <body> tag. To place them elsewhere (for example under the navbar), add :include in the theme: the automatic insertion is then disabled.',
    ],

    'logs' => [
        'announcements' => [
            'created' => 'Created announcement #:id',
            'updated' => 'Updated announcement #:id',
            'deleted' => 'Deleted announcement #:id',
        ],

        'settings' => 'Updated announcements settings',
    ],
];
