<?php

/**
 * SunshineBiz_Location building edit form block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_Location
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_Location_Block_Building_Edit_Form extends SunshineBiz_Location_Block_Widget_Form {

    public function _construct() {

        parent::_construct();
        $this->setId('building_form');
        $this->setTitle($this->_helper->__('Building Information'));
    }

    protected function _prepareForm() {

        $model = Mage::registry('locations_building');
        $form = new SunshineBiz_Locale_Block_Data_Form(
                array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );
        $form->setHtmlIdPrefix('building_');

        $fieldset = $form->addFieldset(
                'base_fieldset', array('legend' => $this->_helper->__('Building Information'))
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
        
        $fieldset->addField('default_mnemonic', 'text', array(
            'name' => 'default_mnemonic',
            'label' => $this->_helper->__('Default Mnemonic'),
            'id' => 'default_mnemonic',
            'title' => $this->_helper->__('Default Mnemonic'),
        ));
        
        if($model->getNames()) {
            $i = 0;
            foreach ($model->getNames() as $name) {
                if($name['locale']) {                    
                    $label = $this->_helper->getLocaleLabel($name['locale']);
                    $fieldset->addField("names[{$i}][mnemonic]", 'text', array(
                        'name' => "names[{$i}][mnemonic]",
                        'label' => $this->_helper->__('%s Mnemonic', $label),
                        'id' => "names[{$i}][mnemonic]",
                        'title' => $this->_helper->__('%s Mnemonic', $label),
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
            'onchange' => 'locationChanged(this, \'' . $this->getUrl('*/json/countryRegion') . '\',  \'building_region_id\')'
        ))->setValues(Mage::getResourceModel('Mage_Directory_Model_Resource_Country_Collection')
                        ->load()->toOptionArray());

        $options = Mage::getModel('Mage_Directory_Model_Country')
                ->setId($model->getCountryId())
                ->getRegions()
                ->toOptionArray();
        $fieldset->addField('region_id', 'select', array(
            'name' => 'region_id',
            'label' => $this->_helper->__('Regions'),
            'id' => 'region_id',
            'title' => $this->_helper->__('Regions'),
            'class' => 'input-select',
            'onchange' => 'locationChanged(this, \'' . $this->getUrl('*/json/regionArea') . '\',  \'building_area_id\')',
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
        $fieldset->addField('area_id', 'select', array(
            'name' => 'area_id',
            'label' => $this->_helper->__('Areas'),
            'id' => 'area_id',
            'title' => $this->_helper->__('Areas'),
            'class' => 'input-select',
            'required' => true,
        ))->setValues($options);

        $fieldset->addField('default_address', 'text', array(
            'name' => 'default_address',
            'label' => $this->_helper->__('Default Address'),
            'id' => 'default_address',
            'title' => $this->_helper->__('Default Address'),
        ));
        
        if($model->getNames()) {
            $i = 0;
            foreach ($model->getNames() as $name) {
                if($name['locale']) {
                    $label = $this->_helper->getLocaleLabel($name['locale']);
                    $fieldset->addField("names[{$i}][address]", 'text', array(
                        'name' => "names[{$i}][address]",
                        'label' => $this->_helper->__('%s Address', $label),
                        'id' => "names[{$i}][address]",
                        'title' => $this->_helper->__('%s Address', $label),
                    ));
                    $i++;
                }
            }
        }

        $fieldset->addField('is_active', 'select', array(
            'name' => 'is_active',
            'label' => $this->_helper->__('Status'),
            'id' => 'is_active',
            'title' => $this->_helper->__('Status'),
            'class' => 'input-select',
            'style' => 'width: 80px',
            'required' => true,
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

        $model = Mage::registry('locations_building');

        return $this->getUrl('*/*/edit', array(
                    'id' => $model->getId()
                ));
    }
}