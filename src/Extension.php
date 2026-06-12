<?php
/**
 * Расширение модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\MediaDialogs;

/**
 * Расширение "Медиа диалоги".
 * 
 * Расширение принадлежит модулю "Справочники".
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\MediaDialogs
 * @since 1.0
 */
class Extension extends \Ge\Panel\Extension\Extension
{
    /**
     * {@inheritdoc}
     */
    public string $id = 'rg.be.references.media_dialogs';

    /**
     * {@inheritdoc}
     */
    public string $defaultController = 'grid';

    /**
     * Возвращает активную запись медиапапки.
     *
     * @return MediaFolder
     * 
     * @throws CreateObjectException
     */
    public function createMediaFolder(): MediaFolder
    {
        /** @var MediaFolder|null $model */
        $model = Ge::$app->extensions->getModel('MediaFolder', 'rg.be.references.media_folders');
        if ($model === null) {
            throw new CreateObjectException(
                Ge::t('app', 'Could not defined data model "{0}"', [MediaFolder::class])
            );
        }
        return $model;
    }
}