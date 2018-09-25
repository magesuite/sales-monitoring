<?php

namespace MageSuite\SalesMonitoring\Setup;


class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $installer,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer->startSetup();

        if (!$installer->tableExists('creativestyle_sales_monitoring_checks')) {
            $checksTable = $installer->getConnection()
                ->newTable($installer->getTable('creativestyle_sales_monitoring_checks'))
                ->addColumn(
                    'id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Id'
                )
                ->addColumn(
                    'name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => false]
                )
                ->addColumn(
                    'triggered_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true]
                )
                ->addColumn(
                    'executed_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                    null,
                    ['nullable' => true]
                )
                ->addColumn(
                    'last_order_count',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['nullable' => true]
                )
                ->addColumn(
                    'state',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    50,
                    ['nullable' => true, 'default' => \MageSuite\SalesMonitoring\Model\Check::STATE_OK]
                )
                ->addColumn(
                    'criteria_data',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false]
                );

            $installer->getConnection()->createTable($checksTable);
        }

        $installer->endSetup();
    }
}
