<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * Файл конфигурации установки расширения.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

return [
    'id'          => 'rg.be.references.media_dialogs',
    'moduleId'    => 'rg.be.references',
    'name'        => 'Media dialogues',
    'description' => 'Media file selection dialogs',
    'namespace'   => 'Rg\Backend\References\MediaDialogs',
    'path'        => '/rg/rg.be.references.media_dialogs',
    'route'       => 'media-dialogs',
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => ['any', 'view', 'read', 'info'],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'RG Workspace'],
        ['app', 'code' => 'RG CMS'],
        ['app', 'code' => 'RG CRM'],
        ['module', 'id' => 'rg.be.references'],
        ['extension', 'id' => 'rg.be.references.media_folders'],
    ]
];
