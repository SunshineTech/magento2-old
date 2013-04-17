<?php

/**
 * SunshineBiz_Location area edit form block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Area_Edit_Form extends SunshineBiz_Location_Block_Widget_Form {

    public function _construct() {

        parent::_construct();
        $this->setId('area_form');

        $this->setTitle($this->_helper->__('Area Information'));
    }

    protected function _prepareForm() {

        $model = Mage::registry('locations_area');
        $form = new SunshineBiz_Locale_Block_Data_Form(
                array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );
        $form->setHtmlIdPrefix('area_');

        $fieldset = $form->addFieldset(
                'base_fieldset', array('legend' => $this->_helper->__('Area Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));

            $_locales = array();
            foreach ($model->getNames() as $name) {
                if($name['locale'])
                    $_locales[] = $name['locale'];
            }
            $str = str_ireplace('"', '\'', json_encode($_locales));
            $locales = Mage::app()->getLocale()->getTranslatedOptionLocales();
            array_unshift($locales, array('value' => '', 'label' => $this->_helper->__('Default Locale')));
            $fieldset->addField('locale', 'select', array(
                'name' => 'locale',
                'label' => $this->_helper->__('Locale'),
                'onchange' => "localeChanged(this, '{$this->_getChangeUrl()}', {$str});",
                'id' => 'locale',
                'title' => $this->_helper->__('Locale'),
                'class' => 'input-select'
            ))->setValues($locales);
        } else {
            if (!$model->hasData('is_active')) {
                $model->setIsActive(1);
            }
        }

        $fieldset->addField('default_name', 'text', array(
            'name' => 'default_name',
            'label' => $this->_helper->__('Default Name'),
            'id' => 'default_name',
            'title' => $this->_helper->__('Default Name'),
            'required' => true,
        ));
        
        if($model->getNames()) {
            $i = 0;
            foreach ($model->getNames() as $name) {
                if($name['locale']) {
                    $fieldset->addField("names[{$i}][locale]", 'hidden', array(
                        'name' => "names[{$i}][locale]",
                    ));

                    $label = $this->_helper->getLocaleLabel($name['locale']);
                    $fieldset->addField("names[{$i}][name]", 'text', array(
                        'name' => "names[{$i}][name]",
                        'label' => $this->_helper->__('%s Name', $label),
                        'id' => "names[{$i}][name]",
                        'title' => $this->_helper->__('%s Name', $label),
                    ));
                    $i++;
                }
            }
        }
        
        $fieldset->addField('default_abbr', 'text', array(
            'name' => 'default_abbr',
            'label' => $this->_helper->__('Default Abbr'),
            'id' => 'default_abbr',
            'title' => $this->_helper->__('Default Abbr'),
            'required' => true,
        ));
        
        if($model->getNames()) {
            $i = 0;
            foreach ($model->getNames() as $name) {
                if($name['locale']) {
                    $label = $this->_helper->getLocaleLabel($name['locale']);
                    $fieldset->addField("names[{$i}][abbr]", 'text', array(
                        'name' => "names[{$i}][abbr]",
                        'label' => $this->_helper->__('%s Abbr', $label),
                        'id' => "names[{$i}][abbr]",
                        'title' => $this->_helper->__('%s Abbr', $label),
                    ));
                    $i++;
                }
            }
        }

        $fieldset->addField('country_id', 'select', array(
            'name' => 'country_id',
            'label' => $this->_helper->__('Country'),
            'id' => 'country_id',
            'title' => $this->_helper->__('Country'),
            'class' => 'input-select',
            'onchange' => 'locationChanged(this, \'' . $this->getUrl('*/json/countryRegion') . '\',  \'area_region_id\')'
        ))->setValues(Mage::getResourceModel('Mage_Directory_Model_Resource_Country_Collection')
                        ->load()->toOptionArray(Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --')));

        $options = Mage::getResourceModel('SunshineBiz_Location_Model_Resource_Region_Collection')
                ->addCountryFilter($model->getCountryId())
                ->load()
                ->toOptionArray(Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --'));
        $fieldset->addField('region_id', 'select', array(
            'name' => 'region_id',
            'label' => $this->_helper->__('Regions'),
            'id' => 'region_id',
            'title' => $this->_helper->__('Regions'),
            'class' => 'input-select',
            'onchange' => 'locationChanged(this, \'' . $this->getUrl('*/json/regionArea') . '\',  \'area_parent_id\')',
            'required' => true,
        ))->setValues($options);

        $options = array();
        if ($model->getRegionId()) {
            $options = Mage::getModel('SunshineBiz_Location_Model_Region')
                    ->setId($model->getRegionId())
                    ->getAreas()
                    ->toOptionArray();
        } else {
            array_unshift($options, array('value' => '0', 'label' => ''));
        }
        $fieldset->addField('parent_id', 'select', array(
            'name' => 'parent_id',
            'label' => $this->_helper->__('Parent'),
            'id' => 'parent_id',
            'title' => $this->_helper->__('Parent'),
            'class' => 'input-select'
        ))->setValues($options);
        
        $fieldset->addField('is_active', 'select', array(
            'name' => 'is_active',
            'label' => $this->_helper->__('Status'),
            'id' => 'is_active',
            'title' => $this->_helper->__('Status'),
            'class' => 'input-select',
            'options' => array(
                '1' => $this->_helper->__('Active'),
                '0' => $this->_helper->__('Inactive')
            ),
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function _getChangeUrl() {

        $model = Mage::registry('locations_area');

        return $this->getUrl('*/*/edit', array(
                    'id' => $model->getId()
                ));
    }
}