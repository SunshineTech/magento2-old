<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Backend
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Mage_Backend_Block_Widget_Grid_Extended
    extends Mage_Backend_Block_Widget_Grid
    implements Mage_Backend_Block_Widget_Grid_ExportInterface
{
    /**
     * Columns array
     *
     * array(
     *      'header'    => string,
     *      'width'     => int,
     *      'sortable'  => bool,
     *      'index'     => string,
     *      //'renderer'  => Mage_Backend_Block_Widget_Grid_Column_Renderer_Interface,
     *      'format'    => string
     *      'total'     => string (sum, avg)
     * )
     * @var array
     */
    protected $_columns = array();

    /**
     * Collection object
     *
     * @var Varien_Data_Collection
     */
    protected $_collection = null;

    /**
     * Export flag
     *
     * @var bool
     */
    protected $_isExport = false;

    /**
     * Grid export types
     *
     * @var array
     */
    protected $_exportTypes = array();

    /**
     * Rows per page for import
     *
     * @var int
     */
    protected $_exportPageSize = 1000;

    /**
     * Identifier of last grid column
     *
     * @var string
     */
    protected $_lastColumnId;

    /**
     * Massaction row id field
     *
     * @var string
     */
    protected $_massactionIdField = null;

    /**
     * Massaction row id filter
     *
     * @var string
     */
    protected $_massactionIdFilter = null;

    /**
     * Massaction block name
     *
     * @var string
     */
    protected $_massactionBlockName = 'Mage_Backend_Block_Widget_Grid_Massaction_Extended';

    /**
     * Columns view order
     *
     * @var array
     */
    protected $_columnsOrder = array();

    /**
     * Label for empty cell
     *
     * @var string
     */
    protected $_emptyCellLabel = '';

    /**
     * Columns to group by
     *
     * @var array
     */
    protected $_groupedColumn = array();

    /**
     * Column headers visibility
     *
     * @var boolean
     */
    protected $_headersVisibility = true;

    /**
     * Filter visibility
     *
     * @var boolean
     */
    protected $_filterVisibility = true;

    /**
     * Empty grid text
     *
     * @var string|null
     */
    protected $_emptyText;

    /**
     * Empty grid text CSS class
     *
     * @var string|null
     */
    protected $_emptyTextCss    = 'a-center';

    /*
    * @var boolean
    */
    protected $_isCollapsed;

    /**
     * Count subtotals
     *
     * @var boolean
     */
    protected $_countSubTotals = false;

    /**
     * SubTotals
     *
     * @var array
     */
    protected $_subtotals = array();

    /**
     * @var string
     */
    protected $_template = 'Mage_Backend::widget/grid/extended.phtml';

    /**
     * @var string
     */
    protected $_exportPath;

    protected function _construct()
    {
        parent::_construct();
        $this->_emptyText = Mage::helper('Mage_Backend_Helper_Data')->__('No records found.');
        $this->_exportPath = Mage::getBaseDir('var') . DS . 'export';
    }

    /**
     * Initialize child blocks
     *
     * @return Mage_Core_Block_Abstract
     */
    protected function _prepareLayout()
    {
        $this->setChild('export_button',
            $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Button')
                ->setData(array(
                'label'     => Mage::helper('Mage_Adminhtml_Helper_Data')->__('Export'),
                'onclick'   => $this->getJsObjectName().'.doExport()',
                'class'   => 'task'
            ))
        );
        $this->setChild('reset_filter_button',
            $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Button')
                ->setData(array(
                'label'     => Mage::helper('Mage_Backend_Helper_Data')->__('Reset Filter'),
                'onclick'   => $this->getJsObjectName().'.resetFilter()',
            ))
        );
        $this->setChild('search_button',
            $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Button')
                ->setData(array(
                'label'     => Mage::helper('Mage_Backend_Helper_Data')->__('Search'),
                'onclick'   => $this->getJsObjectName().'.doFilter()',
                'class'   => 'task'
            ))
        );
        return parent::_prepareLayout();
    }

    /**
     * Retrieve column set block
     *
     * @return Mage_Core_Block_Abstract
     */
    public function getColumnSet()
    {
        if (!$this->getChildBlock('grid.columnSet')) {
            $this->setChild('grid.columnSet',
                $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Grid_ColumnSet')
            );
        }
        return parent::getColumnSet();
    }

    /**
     * Generate export button
     *
     * @return string
     */
    public function getExportButtonHtml()
    {
        return $this->getChildHtml('export_button');
    }

    /**
     * Add new export type to grid
     *
     * @param   string $url
     * @param   string $label
     * @return  Mage_Backend_Block_Widget_Grid
     */
    public function addExportType($url, $label)
    {
        $this->_exportTypes[] = new Varien_Object(
            array(
                'url'   => $this->getUrl($url, array('_current'=>true)),
                'label' => $label
            )
        );
        return $this;
    }

    /**
     * Add column to grid
     *
     * @param   string $columnId
     * @param   array || Varien_Object $column
     * @return  Mage_Backend_Block_Widget_Grid
     */
    public function addColumn($columnId, $column)
    {
        if (is_array($column)) {
            $this->getColumnSet()->setChild(
                $columnId,
                $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Grid_Column')
                    ->setData($column)
                    ->setId($columnId)
                    ->setGrid($this)
            );
            $this->getColumnSet()->getChildBlock($columnId)->setGrid($this);
        } else {
            throw new Exception(Mage::helper('Mage_Backend_Helper_Data')->__('Wrong column format.'));
        }

        $this->_lastColumnId = $columnId;
        return $this;
    }

    /**
     * Remove existing column
     *
     * @param string $columnId
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function removeColumn($columnId)
    {
        if ($this->getColumnSet()->getChildBlock($columnId)) {
            $this->getColumnSet()->unsetChild($columnId);
            if ($this->_lastColumnId == $columnId) {
                $this->_lastColumnId = array_pop($this->getColumnSet()->getChildNames());
            }
        }
        return $this;
    }

    /**
     * Add column to grid after specified column.
     *
     * @param   string $columnId
     * @param   array|Varien_Object $column
     * @param   string $after
     * @return  Mage_Backend_Block_Widget_Grid
     */
    public function addColumnAfter($columnId, $column, $after)
    {
        $this->addColumn($columnId, $column);
        $this->addColumnsOrder($columnId, $after);
        return $this;
    }

    /**
     * Add column view order
     *
     * @param string $columnId
     * @param string $after
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function addColumnsOrder($columnId, $after)
    {
        $this->_columnsOrder[$columnId] = $after;
        return $this;
    }

    /**
     * Retrieve columns order
     *
     * @return array
     */
    public function getColumnsOrder()
    {
        return $this->_columnsOrder;
    }

    /**
     * Sort columns by predefined order
     *
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function sortColumnsByOrder()
    {
        foreach ($this->getColumnsOrder() as $columnId => $after) {
            $this->getLayout()->reorderChild(
                $this->getColumnSet()->getNameInLayout(),
                $this->getColumn($columnId)->getNameInLayout(),
                $this->getColumn($after)->getNameInLayout()
            );
        }

        $columns = $this->getColumnSet()->getChildNames();
        $this->_lastColumnId = array_pop($columns);
        return $this;
    }

    /**
     * Retrieve identifier of last column
     *
     * @return string
     */
    public function getLastColumnId()
    {
        return $this->_lastColumnId;
    }

    /**
     * Initialize grid columns
     *
     * @return Mage_Backend_Block_Widget_Grid_Extended
     */
    protected function _prepareColumns()
    {
        $this->sortColumnsByOrder();
        return $this;
    }

    /**
     * Prepare grid massaction block
     *
     * @return Mage_Backend_Block_Widget_Grid
     */
    protected function _prepareMassactionBlock()
    {
        $this->setChild('massaction', $this->getLayout()->createBlock($this->getMassactionBlockName()));
        $this->_prepareMassaction();
        if ($this->getMassactionBlock()->isAvailable()) {
            $this->_prepareMassactionColumn();
        }
        return $this;
    }

    /**
     * Prepare grid massaction actions
     *
     * @return Mage_Backend_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * Prepare grid massaction column
     *
     * @return Mage_Backend_Block_Widget_Grid_Extended
     */
    protected function _prepareMassactionColumn()
    {
        $columnId = 'massaction';
        $massactionColumn = $this->getLayout()->createBlock('Mage_Backend_Block_Widget_Grid_Column')
            ->setData(array(
            'index'        => $this->getMassactionIdField(),
            'filter_index' => $this->getMassactionIdFilter(),
            'type'         => 'massaction',
            'name'         => $this->getMassactionBlock()->getFormFieldName(),
            'is_system'    => true,
            'header_css_class'  => 'col-select',
            'column_css_class'  => 'col-select'
        ));

        if ($this->getNoFilterMassactionColumn()) {
            $massactionColumn->setData('filter', false);
        }

        $massactionColumn->setSelected($this->getMassactionBlock()->getSelected())
            ->setGrid($this)
            ->setId($columnId);

        $this->getColumnSet()->insert(
            $massactionColumn, count($this->getColumnSet()->getColumns()) + 1, false, $columnId
        );
        return $this;
    }

    /**
     * Apply sorting and filtering to collection
     *
     * @return Mage_Backend_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        if ($this->getCollection()) {
            parent::_prepareCollection();

            if (!$this->_isExport) {
                $this->getCollection()->load();
                $this->_afterLoadCollection();
            }
        }

        return $this;
    }

    /**
     * Process collection after loading
     *
     * @return Mage_Backend_Block_Widget_Grid
     */
    protected function _afterLoadCollection()
    {
        return $this;
    }

    /**
     * Initialize grid before rendering
     *
     * @return Mage_Backend_Block_Widget_Grid_Extended|void
     */
    protected function _prepareGrid()
    {
        $this->_prepareColumns();
        $this->_prepareMassactionBlock();
        parent::_prepareGrid();
        return $this;
    }

    /**
     * Retrieve grid HTML
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->toHtml();
    }

    /**
     * Retrieve massaction row identifier field
     *
     * @return string
     */
    public function getMassactionIdField()
    {
        return $this->_massactionIdField;
    }

    /**
     * Set massaction row identifier field
     *
     * @param  string    $idField
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setMassactionIdField($idField)
    {
        $this->_massactionIdField = $idField;
        return $this;
    }

    /**
     * Retrieve massaction row identifier filter
     *
     * @return string
     */
    public function getMassactionIdFilter()
    {
        return $this->_massactionIdFilter;
    }

    /**
     * Set massaction row identifier filter
     *
     * @param string $idFilter
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setMassactionIdFilter($idFilter)
    {
        $this->_massactionIdFilter = $idFilter;
        return $this;
    }

    /**
     * Retrive massaction block name
     *
     * @return string
     */
    public function getMassactionBlockName()
    {
        return $this->_massactionBlockName;
    }

    /**
     * Set massaction block name
     *
     * @param  string    $blockName
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setMassactionBlockName($blockName)
    {
        $this->_massactionBlockName = $blockName;
        return $this;
    }

    /**
     * Retrive massaction block
     *
     * @return Mage_Backend_Block_Widget_Grid_Massaction_Extended
     */
    public function getMassactionBlock()
    {
        return $this->getChildBlock('massaction');
    }

    /**
     * Generate massaction block
     *
     * @return string
     */
    public function getMassactionBlockHtml()
    {
        return $this->getChildHtml('massaction');
    }

    /**
     * Retrieve columns to render
     *
     * @return array
     */
    public function getSubTotalColumns()
    {
        return $this->getColumns();
    }

    /**
     * Check whether should render cell
     *
     * @param Varien_Object $item
     * @param Mage_Backend_Block_Widget_Grid_Column $column
     * @return boolean
     */
    public function shouldRenderCell($item, $column)
    {
        if ($this->isColumnGrouped($column) && $item->getIsEmpty()) {
            return true;
        }
        if (!$item->getIsEmpty()) {
            return true;
        }
        return false;
    }

    /**
     * Retrieve label for empty cell
     *
     * @return string
     */
    public function getEmptyCellLabel()
    {
        return $this->_emptyCellLabel;
    }

    /**
     * Set label for empty cell
     *
     * @param string $label
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setEmptyCellLabel($label)
    {
        $this->_emptyCellLabel = $label;
        return $this;
    }

    /**
     * Return row url for js event handlers
     *
     * @param Mage_Catalog_Model_Product|Varien_Object
     * @return string
     */
    public function getRowUrl($item)
    {
        $res = parent::getRowUrl($item);
        return ($res ? $res : '#');
    }

    /**
     * Get children of specified item
     *
     * @param Varien_Object $item
     * @return array
     */
    public function getMultipleRows($item)
    {
        return $item->getChildren();
    }

    /**
     * Retrieve columns for multiple rows
     * @return array
     */
    public function getMultipleRowColumns()
    {
        $columns = $this->getColumns();
        foreach ($this->_groupedColumn as $column) {
            unset($columns[$column]);
        }
        return $columns;
    }

    /**
     * Check whether subtotal should be rendered
     *
     * @param Varien_Object $item
     * @return boolean
     */
    public function shouldRenderSubTotal($item)
    {
        return ($this->_countSubTotals && count($this->_subtotals) > 0 && count($this->getMultipleRows($item)) > 0);
    }

    /**
     * Retrieve rowspan number
     *
     * @param Varien_Object $item
     * @param Mage_Backend_Block_Widget_Grid_Column $column
     * @return integer|boolean
     */
    public function getRowspan($item, $column)
    {
        if ($this->isColumnGrouped($column)) {
            return count($this->getMultipleRows($item)) + count($this->_groupedColumn);
        }
        return false;
    }

    /**
     * Check whether given column is grouped
     *
     * @param string|object $column
     * @param string $value
     * @return boolean|Mage_Backend_Block_Widget_Grid
     */
    public function isColumnGrouped($column, $value = null)
    {
        if (null === $value) {
            if (is_object($column)) {
                return in_array($column->getIndex(), $this->_groupedColumn);
            }
            return in_array($column, $this->_groupedColumn);
        }
        $this->_groupedColumn[] = $column;
        return $this;
    }

    /**
     * Check whether should render empty cell
     *
     * @param Varien_Object $item
     * @param Mage_Backend_Block_Widget_Grid_Column $column
     * @return boolean
     */
    public function shouldRenderEmptyCell($item, $column)
    {
        return ($item->getIsEmpty() && in_array($column['index'], $this->_groupedColumn));
    }

    /**
     * Retrieve colspan for empty cell
     *
     * @return int
     */
    public function getEmptyCellColspan()
    {
        return $this->getColumnCount() - count($this->_groupedColumn);
    }

    /**
     * Retrieve subtotal item
     *
     * @param Varien_Object $item
     * @return Varien_Object
     */
    public function getSubTotalItem($item)
    {
        foreach ($this->_subtotals as $subtotalItem) {
            foreach ($this->_groupedColumn as $groupedColumn) {
                if ($subtotalItem->getData($groupedColumn) == $item->getData($groupedColumn)) {
                    return $subtotalItem;
                }
            }
        }
        return '';
    }

    /**
     * Count columns
     *
     * @return int
     */
    public function getColumnCount()
    {
        return count($this->getColumns());
    }

    /**
     * Set visibility of column headers
     *
     * @param boolean $visible
     */
    public function setHeadersVisibility($visible=true)
    {
        $this->_headersVisibility = $visible;
    }

    /**
     * Return visibility of column headers
     *
     * @return boolean
     */
    public function getHeadersVisibility()
    {
        return $this->_headersVisibility;
    }

    /**
     * Set visibility of filter
     *
     * @param boolean $visible
     */
    public function setFilterVisibility($visible=true)
    {
        $this->_filterVisibility = $visible;
    }

    /**
     * Return visibility of filter
     *
     * @return boolean
     */
    public function getFilterVisibility()
    {
        return $this->_filterVisibility;
    }

    /**
     * Set empty text for grid
     *
     * @param string $text
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setEmptyText($text)
    {
        $this->_emptyText = $text;
        return $this;
    }

    /**
     * Return empty text for grid
     *
     * @return string
     */
    public function getEmptyText()
    {
        return $this->_emptyText;
    }

    /**
     * Set empty text CSS class
     *
     * @param string $cssClass
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setEmptyTextClass($cssClass)
    {
        $this->_emptyTextCss = $cssClass;
        return $this;
    }

    /**
     * Return empty text CSS class
     *
     * @return string
     */
    public function getEmptyTextClass()
    {
        return $this->_emptyTextCss;
    }

    /**
     * Set flag whether is collapsed
     * @param $isCollapsed
     * @return Mage_Backend_Block_Widget_Grid_ColumnSet
     */
    public function setIsCollapsed($isCollapsed)
    {
        $this->_isCollapsed = $isCollapsed;
        return $this;
    }

    /**
     * Retrieve flag is collapsed
     * @return mixed
     */
    public function getIsCollapsed()
    {
        return $this->_isCollapsed;
    }

    /**
     * Retrieve file content from file container array
     *
     * @param array $fileData
     * @return string
     */
    protected function _getFileContainerContent(array $fileData)
    {
        return $this->_filesystem->read($fileData['value'], $this->_exportPath);
    }

    /**
     * Retrieve Headers row array for Export
     *
     * @return array
     */
    protected function _getExportHeaders()
    {
        $row = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $row[] = $column->getExportHeader();
            }
        }
        return $row;
    }

    /**
     * Retrieve Totals row array for Export
     *
     * @return array
     */
    protected function _getExportTotals()
    {
        $totals = $this->getTotals();
        $row    = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $row[] = ($column->hasTotalsLabel()) ? $column->getTotalsLabel() : $column->getRowFieldExport($totals);
            }
        }
        return $row;
    }

    /**
     * Iterate collection and call callback method per item
     * For callback method first argument always is item object
     *
     * @param string $callback
     * @param array $args additional arguments for callback method
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function _exportIterateCollection($callback, array $args)
    {
        $originalCollection = $this->getCollection();
        $count = null;
        $page  = 1;
        $lPage = null;
        $break = false;

        while ($break !== true) {
            $collection = clone $originalCollection;
            $collection->setPageSize($this->_exportPageSize);
            $collection->setCurPage($page);
            $collection->load();
            if (is_null($count)) {
                $count = $collection->getSize();
                $lPage = $collection->getLastPageNumber();
            }
            if ($lPage == $page) {
                $break = true;
            }
            $page ++;

            foreach ($collection as $item) {
                call_user_func_array(array($this, $callback), array_merge(array($item), $args));
            }
        }
    }

    /**
     * Write item data to csv export file
     *
     * @param Varien_Object $item
     * @param Magento_Filesystem_StreamInterface $stream
     */
    protected function _exportCsvItem(Varien_Object $item, Magento_Filesystem_StreamInterface $stream)
    {
        $row = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $row[] = $column->getRowFieldExport($item);
            }
        }
        $stream->writeCsv($row);
    }

    /**
     * Retrieve a file container array by grid data as CSV
     *
     * Return array with keys type and value
     *
     * @return array
     */
    public function getCsvFile()
    {
        $this->_isExport = true;
        $this->_prepareGrid();

        $name = md5(microtime());
        $file = $this->_exportPath . DS . $name . '.csv';

        $this->_filesystem->setIsAllowCreateDirectories(true);
        $stream = $this->_filesystem->createAndOpenStream($file, 'w+', $this->_exportPath);
        $stream->lock(true);
        $stream->writeCsv($this->_getExportHeaders());

        $this->_exportIterateCollection('_exportCsvItem', array($stream));

        if ($this->getCountTotals()) {
            $stream->writeCsv($this->_getExportTotals());
        }

        $stream->unlock();
        $stream->close();

        return array(
            'type'  => 'filename',
            'value' => $file,
            'rm'    => true // can delete file after use
        );
    }

    /**
     * Retrieve Grid data as CSV
     *
     * @return string
     */
    public function getCsv()
    {
        $csv = '';
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();

        $data = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $data[] = '"'.$column->getExportHeader().'"';
            }
        }
        $csv.= implode(',', $data)."\n";

        foreach ($this->getCollection() as $item) {
            $data = array();
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                        $column->getRowFieldExport($item)) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        if ($this->getCountTotals()) {
            $data = array();
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    $data[] = '"' . str_replace(array('"', '\\'), array('""', '\\\\'),
                        $column->getRowFieldExport($this->getTotals())) . '"';
                }
            }
            $csv.= implode(',', $data)."\n";
        }

        return $csv;
    }

    /**
     * Retrieve data in xml
     *
     * @return string
     */
    public function getXml()
    {
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $indexes = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $indexes[] = $column->getIndex();
            }
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml.= '<items>';
        foreach ($this->getCollection() as $item) {
            $xml.= $item->toXml($indexes);
        }
        if ($this->getCountTotals()) {
            $xml.= $this->getTotals()->toXml($indexes);
        }
        $xml.= '</items>';
        return $xml;
    }

    /**
     *  Get a row data of the particular columns
     *
     * @param Varien_Object $data
     * @return array
     */
    public function getRowRecord(Varien_Object $data)
    {
        $row = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $row[] = $column->getRowFieldExport($data);
            }
        }
        return $row;
    }

    /**
     * Retrieve a file container array by grid data as MS Excel 2003 XML Document
     *
     * Return array with keys type and value
     *
     * @param string $sheetName
     * @return array
     */
    public function getExcelFile($sheetName = '')
    {
        $this->_isExport = true;
        $this->_prepareGrid();

        $convert = new Magento_Convert_Excel($this->getCollection()->getIterator(), array($this, 'getRowRecord'));

        $name = md5(microtime());
        $file = $this->_exportPath . DS . $name . '.xml';

        $this->_filesystem->setIsAllowCreateDirectories(true);
        $stream = $this->_filesystem->createAndOpenStream($file, 'w+', $this->_exportPath);
        $stream->lock(true);

        $convert->setDataHeader($this->_getExportHeaders());
        if ($this->getCountTotals()) {
            $convert->setDataFooter($this->_getExportTotals());
        }

        $convert->write($stream, $sheetName);
        $stream->unlock();
        $stream->close();

        return array(
            'type'  => 'filename',
            'value' => $file,
            'rm'    => true // can delete file after use
        );
    }

    /**
     * Retrieve grid data as MS Excel 2003 XML Document
     *
     * @return string
     */
    public function getExcel()
    {
        $this->_isExport = true;
        $this->_prepareGrid();
        $this->getCollection()->getSelect()->limit();
        $this->getCollection()->setPageSize(0);
        $this->getCollection()->load();
        $this->_afterLoadCollection();
        $headers = array();
        $data = array();
        foreach ($this->getColumns() as $column) {
            if (!$column->getIsSystem()) {
                $headers[] = $column->getHeader();
            }
        }
        $data[] = $headers;

        foreach ($this->getCollection() as $item) {
            $row = array();
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    $row[] = $column->getRowField($item);
                }
            }
            $data[] = $row;
        }

        if ($this->getCountTotals()) {
            $row = array();
            foreach ($this->getColumns() as $column) {
                if (!$column->getIsSystem()) {
                    $row[] = $column->getRowField($this->getTotals());
                }
            }
            $data[] = $row;
        }

        $convert = new Magento_Convert_Excel(new ArrayIterator($data));
        return $convert->convert('single_sheet');
    }

    /**
     * Retrieve grid export types
     *
     * @return array|bool
     */
    public function getExportTypes()
    {
        return empty($this->_exportTypes) ? false : $this->_exportTypes;
    }
    /**
     * Set collection object
     *
     * @param Varien_Data_Collection $collection
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }

    /**
     * get collection object
     *
     * @return Varien_Data_Collection
     */
    public function getCollection()
    {
        return $this->_collection;
    }

    /**
     * Set subtotals
     *
     * @param boolean $flag
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setCountSubTotals($flag = true)
    {
        $this->_countSubTotals = $flag;
        return $this;
    }

    /**
     * Return count subtotals
     *
     * @return boolean
     */
    public function getCountSubTotals()
    {
        return $this->_countSubTotals;
    }

    /**
     * Set subtotal items
     *
     * @param array $items
     * @return Mage_Backend_Block_Widget_Grid
     */
    public function setSubTotals(array $items)
    {
        $this->_subtotals = $items;
        return $this;
    }

    /**
     * Retrieve subtotal items
     *
     * @return array
     */
    public function getSubTotals()
    {
        return $this->_subtotals;
    }

    /**
     * Generate list of grid buttons
     *
     * @return string
     */
    public function getMainButtonsHtml()
    {
        $html = '';
        if ($this->getFilterVisibility()) {
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;
    }
}
