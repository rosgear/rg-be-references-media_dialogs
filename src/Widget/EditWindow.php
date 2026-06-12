<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\MediaDialogs\Widget;

/**
 * Виджет для формирования интерфейса окна редактирования записи.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\MediaDialogs\Widget
 * @since 1.0
 */
class EditWindow extends \Ge\Panel\Widget\EditWindow
{
    /**
     * Медиапапки профиля медиа диалога.
     * 
     * @var array
     */
    protected array $mediaFolders;

    /**
     * {@inheritdoc}
     */
    public array $passParams = ['mediaFolders'];

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        // панель формы (Ge.view.form.Panel GeJS)
        $this->form->router->route = $this->creator->route('/form');
        $this->form->loadJSONFile('/form', 'items', [
            '@mediaFolders' => $this->mediaFolders
        ]);

        // окно компонента (Ext.window.Window Sencha ExtJS)
        $this->resizable = false;
        $this->width = 660;
        $this->height = 800;
        $this->layout = 'fit';
        $this->responsiveConfig = [
            'height < 800' => ['height' => '99%'],
            'width < 660' => ['width' => '99%'],
        ];
        $this
            ->addCss('/form.css')
            ->addCss('@module::/rg/rg.be.references.media_folders/assets/css/folders.css');
    }
}
