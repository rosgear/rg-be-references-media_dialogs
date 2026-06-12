<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\MediaDialogs\Controller;

use Ge;
use Ge\Panel\Controller\FormController;
use Rg\Backend\References\MediaDialogs\Widget\EditWindow;

/**
 * Контроллер формы профиля медиа диалога.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\MediaDialogs\Controller
 * @since 1.0
 */
class Form extends FormController
{

    /**
     * {@inheritdoc}
     */
    public function createWidget(): EditWindow
    {
        /** @var array $mediaFolders Медиапапки профиля медиа диалога */
        $mediaFolders = Ge::getEModel('MediaFolder', 'rg.be.references.media_folders')
            ->getTreeRows(function ($node, $level) {
                $icon = '<span class="rg-references-mediadialogs__folder ' 
                    . ($node['icon_cls'] ? $node['icon_cls'] : 'rg-references-mediafolders__folder') 
                    . '"></span> ';
            return [
                'xtype'      => 'checkbox',
                'ui'         => 'switch',
                'boxLabel'   => $icon . $node['name'],
                'style'      => 'padding-left:' . ($level * 20) . 'px',
                'name'       => 'folders[' . $node['id'] . ']',
                'inputValue' => 1
            ];
        });

        return new EditWindow([
            'mediaFolders' => $mediaFolders
        ]);
    }
}
