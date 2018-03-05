<?php
namespace Eleanorsoft\DesignersPage\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;

class InstallData implements InstallDataInterface
{

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(Product::ENTITY, 'el_designer',[
            'type'      =>  'int',
            'label'     =>  'Designer',
            'input'     =>  'select',
            'source'    => 'Eleanorsoft\DesignersPage\Model\Config\Source\Options',
            'visible'   =>  true,
            'required'  =>  false,
            'filterable' => true,
            'visible_on_front' => true,
            'filterable_in_search' => true,
            'global'    =>  ScopedAttributeInterface::SCOPE_GLOBAL
        ]);
    }
}