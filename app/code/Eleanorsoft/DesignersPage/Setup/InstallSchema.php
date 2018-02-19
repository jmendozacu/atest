<?php

namespace Eleanorsoft\DesignersPage\Setup;
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

        /**
         *
         * Create eleanorsoft_designers Table
         */
        $tableName = $setup->getTable('eleanorsoft_designers');

        if ($setup->getConnection()->isTableExists($tableName) != true){
            $table = $setup->getConnection()->newTable($tableName)

                ->addColumn('designer_id', Table::TYPE_INTEGER, null,
                    array(
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ),
                    'Designer Id'
                )
                ->addColumn('full_name', Table::TYPE_TEXT, 255,
                    array(
                        'nullable' => false
                    ),
                    'Full Name'
                )
                ->addColumn('photo', Table::TYPE_TEXT, 255,
                    array(
                        'nullable' => true
                    ),
                    'Photo of a designer\'s'
                )
                ->addColumn('alternative_photo', Table::TYPE_TEXT, 255,
                    array(
                        'nullable' => true
                    ),
                    'Alternative photo of a designer'
                )
                ->addColumn('banner', Table::TYPE_TEXT, 255,
                    array(
                        'nullable' => true
                    ),
                    'Banner for an individual designer page'
                )
                ->addColumn('description', Table::TYPE_TEXT, Table::MAX_TEXT_SIZE,
                    array(
                        'nullable' => true
                    ),
                    'Description'
                )
                ->addColumn('sort', Table::TYPE_INTEGER, null,
                    array(
                        'nullable' => true,
                        'unsigned' => true
                    ),
                    'Sorting'
                )->addColumn('product_id', Table::TYPE_INTEGER, null,
                    array(
                        'unsigned' => true,
                        'nullable' => true
                        ),
                        'Product Id'
                )->addForeignKey(
                    $setup->getFkName(
                        'eleanorsoft_designers',
                        'product_id',
                        'catalog_product_entity',
                        'entity_id'
                    ),
                    'product_id',
                    $setup->getTable('catalog_product_entity'),
                    'entity_id',
                  Table::ACTION_CASCADE
                );
            $setup->getConnection()->createTable($table);
        }
        $setup->endSetup();
    }
}