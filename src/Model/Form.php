<?php
/**
 * Этот файл является частью расширения модуля веб-приложения RosGear.
 * 
 * @link https://rosgear.ru/
 * @copyright Copyright (c) 2015 RosGear
 * @license https://rosgear.ru/license/
 */

namespace Rg\Backend\References\MediaDialogs\Model;

use Ge;
use Ge\Helper\Json;
use Ge\Panel\Data\Model\FormModel;

/**
 * Модель данных профиля медиа диалога.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Rg\Backend\References\MediaDialogs\Model
 * @since 1.0
 */
class Form extends FormModel
{
    use MediaDialogOptionsTrait;

    /**
     * {@inheritdoc}
     */
    public function getDataManagerConfig(): array
    {
        return [
            'useAudit'   => true,
            'tableName'  => '{{reference_media_dialogs}}',
            'primaryKey' => 'id',
            'fields'     => [
                ['id'],
                ['name'], // название
                ['alias'], // пседоним диалога
                ['folders'], // идент. доступных медиапапок
                ['folder_id', 'alias' => 'folderId'], // идент. медиапапки диалога
                ['folder_name', 'alias' => 'folderName'], // название папки диалога
                ['browse_label', 'alias' => 'browseLabel'], // метка поля
                ['browse_type', 'alias' => 'browseType'], //  тип выберамего файла / папки
                ['browse_stripe', 'alias' => 'browseStripe'], // убирать путь у выбранного файла / папки 
                ['toolbar'], // панель инструментов диалога
                ['options'], // параметры
                ['enabled'] // доступен
            ],
            'uniqueFields' => ['alias', 'name'],
            // правила форматирования полей
            'formatterRules' => [
                [['name', 'alias'], 'safe'],
                [['folderId'], 'combo']
            ],
            // правила валидации полей
            'validationRules' => [
                [['name', 'alias'], 'notEmpty'],
                // название
                [
                    'name',
                    'between',
                    'max' => 255, 'type' => 'string'
                ],
                // псевдоним
                [
                    'alias',
                    'between',
                    'max' => 100, 'type' => 'string'
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        parent::init();

        $this
            ->on(self::EVENT_AFTER_SAVE, function ($isInsert, $columns, $result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            })
            ->on(self::EVENT_AFTER_DELETE, function ($result, $message) {
                /** @var \Ge\Panel\Controller\GridController $controller */
                $controller = $this->controller();
                // всплывающие сообщение
                $this->response()
                    ->meta
                        ->cmdPopupMsg($message['message'], $message['title'], $message['type']);
                // обновить список
                $controller->cmdReloadGrid();
            });
    }

    /**
     * Выполняет добавление параметров формата для указанного ключа.
     * 
     * @param array $format Формат параметров.
     * @param array $options Параметры в виде пар "ключ - значение".
     * 
     * @return void
     */
    protected function addOptionsFromFormat(array $format, array $options): void
    {
        foreach ($format as $option) {
            $name = $option[0];
            $value = $options[$name] ?? $option['default'];
            $this->attributes['options[' . $name . ']'] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function beforeLoad(array &$data): void
    {
        /** @var array $format Формат параметров */
        $format = $this->getOptionsFormat();
        // параметры
        $data['options'] = $this->formatOptions($format, $data['options'] ?? []);
        // медиапапки
        if (!isset($data['folders'])) {
            $data['folders'] = [];
        }
    }

    /**
     * Возвращает значение атрибута "folderId" элементу интерфейса формы.
     * 
     * @param null|string|int $value
     * 
     * @return array
     */
    public function outFolderId($value): array
    {
        /** @var \Rg\Backend\References\MediaFolders\Model\MediaFolder $folder */
        $folder = $value ? Ge::getEModel('MediaFolder', 'rg.be.references.media_folders') : null;
        $folder = $folder ? $folder->selectByPk($value) : null;
        if ($folder) {
            return [
                'type'  => 'combobox', 
                'value' => $folder->id, 
                'text'  => $folder->name
            ];
        }
        return [
            'type'  => 'combobox',
            'value' => 0,
            'text'  => Ge::t(BACKEND, '[None]')
        ]; 
    }

    /**
     * Возвращает значение атрибута "folders" элементу интерфейса формы.
     * 
     * @param null|string|int $value
     * 
     * @return array|null
     */
    public function outFolders(?string $value): ?array
    {
        if ($value) {
            $value = explode(',', $value);
            foreach ($value as $index => $folderId) {
                $this->attributes["folders[$folderId]"] = 1;
            }
        }
        return null;
    }

    /**
     * Возращает значение для сохранения в поле "folders".
     * 
     * @return string
     */
    public function unFolders(): ?string
    {
        $folders = $this->folders;
        if ($folders) {
            if (is_array($folders))
                // получить идент. медиапапок
                return implode(',', array_keys($folders));
            else
                return $folders;
        }
        return null;
    }

    /**
     * Возращает значение для сохранения в поле "options".
     * 
     * @return string
     */
    public function unOptions(): string
    {
        $option = (array) $this->options;
        return Json::encode($option);
    }

    /**
     * {@inheritdoc}
     */
    public function processing(): void
    {
        parent::processing();

        // параметры
        if ($this->options) {
            $options = json_decode($this->options, true);
            $this->addOptionsFromFormat($this->getOptionsFormat(), $options);
        }
    }
}
