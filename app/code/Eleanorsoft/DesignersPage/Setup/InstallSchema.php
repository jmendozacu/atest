<?php

namespace Eleanorsoft\DesignersPage\Setup;
use Eleanorsoft\DesignersPage\Api\Data\DesignerInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


/**
 * Class InstallSchema
 *
 * @package Eleanorsoft\DesignersPage\Setup
 * @author Pisarenko Denis <denis.pisarenko@eleanorsoft.com>
 * @copyright Copyright (c) 2018 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $entity = DesignerInterface::ENTITY;

        /**
         * Create eleanorsoft_designers table
         */
        $tableName = $setup->getTable($entity);

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()->newTable($tableName)

                ->addColumn('designer_id', Table::TYPE_INTEGER, null,
                    array(
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ),
                    'Designer ID'
                )
                ->addColumn('sort', Table::TYPE_INTEGER, null,
                    array(
                        'nullable' => true,
                        'unsigned' => true
                    ),
                    'Sorting'
                );
            $setup->getConnection()->createTable($table);
        }

        /**
         * Create eleanorsoft_designers_full_name table
         */
        $this->createTable($setup, $entity, 'full_name');

        /**
         * Create eleanorsoft_designers_photo table
         */
        $this->createTable($setup, $entity, 'photo');

        /**
         * Create eleanorsoft_designers_alternative_photo table
         */
        $this->createTable($setup, $entity, 'alternative_photo');

        /**
         * Create eleanorsoft_designers_banner table
         */
        $this->createTable($setup, $entity, 'banner');

        /**
         * Create eleanorsoft_designers_description table
         */
        $this->createTable($setup, $entity, 'description');

        $setup->endSetup();
    }

    /**
     * Create new table
     *
     * @param SchemaSetupInterface $setup
     * @param $entity
     * @param $suffix
     */
    private function createTable(SchemaSetupInterface $setup, $entity, $suffix)
    {
        $tableName = $setup->getTable($entity . '_' . $suffix);

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()->newTable($tableName)

                ->addColumn($suffix . '_id', Table::TYPE_INTEGER, null,
                    array(
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ),
                    'ID'
                )
                ->addColumn('store_id', Table::TYPE_SMALLINT, null,
                    array(
                        'unsigned' => true,
                        'nullable' => false,
                        'default' => '0'
                    ),
                    'Store ID'
                )
                ->addColumn('designer_id', Table::TYPE_INTEGER, null,
                    array(
                        'unsigned' => true,
                        'nullable' => false,
                    ),
                    'Designer ID'
                )
                ->addColumn('value', Table::TYPE_TEXT, null,
                    array(
                        'nullable' => true,
                    ),
                    'Value'
                )
                ->addForeignKey(
                    $setup->getFkName(
                        $entity . '_' . $suffix,
                        'designer_id',
                        $entity,
                        'designer_id'
                    ),
                    'designer_id',
                    $setup->getTable($entity),
                    'designer_id',
                    Table::ACTION_CASCADE
                )
                ->addForeignKey(
                    $setup->getFkName(
                        $entity . '_' . $suffix,
                        'store_id',
                        'store',
                        'store_id'
                    ),
                    'store_id',
                    $setup->getTable('store'),
                    'store_id',
                    Table::ACTION_CASCADE
                );
            $setup->getConnection()->createTable($table);
        }
    }
}
