<?php

/**
 * SunshineBiz_LBS search tag edit form block
 *
 * @category   SunshineBiz
 * @package    SunshineBiz_LBS
 * @author     iSunshineTech <isunshinetech@gmail.com>
 * @copyright   Copyright (c) 2013 SunshineBiz.commerce, Inc. (http://www.sunshinebiz.cn)
 */
class SunshineBiz_LBS_Block_SearchTag_Edit_Form extends SunshineBiz_LBS_Block_Widget_Form {

    public function _construct() {

        parent::_construct();
        $this->setId('searchTag_form');

        $this->setTitle($this->_helper->__('SearchTag Information'));
    }

    protected function _prepareForm() {

        $model = Mage::registry('lbs_searchTag');
        $form = new SunshineBiz_Locale_Block_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getData('action'),
            'method' => 'post',
            'enctype' => 'multipart/form-data')
        );
        $form->setHtmlIdPrefix('searchTag_');

        $fieldset = $form->addFieldset(
                'base_fieldset', array('legend' => $this->_helper->__('SearchTag Information'))
        );

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
            
            $_locales = array();
            if($model->getNames()) {
                foreach ($model->getNames() as $name) {
                    if($name['locale'])
                        $_locales[] = $name['locale'];
                }
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
        
        $fieldset->addField('img', 'image', array(
            'name'  => 'img',
            'label' => $this->_helper->__('Image'),
            'id'    => 'img',
            'title' => $this->_helper->__('Image'),
        ));

        $fieldset->addField('priority', 'text', array(
            'name'  => 'priority',
            'label' => $this->_helper->__('Priority'),
            'id'    => 'priority',
            'title' => $this->_helper->__('Priority'),
        ));
        
        $fieldset->addField('normal_priority', 'text', array(
            'name' => 'normal_priority',
            'label' => $this->_helper->__('Nomal Priority'),
            'id' => 'normal_priority',
            'title' => $this->_helper->__('Nomal Priority'),
        ));
        
        $fieldset->addField('near_priority', 'text', array(
            'name' => 'near_priority',
            'label' => $this->_helper->__('Near Priority'),
            'id' => 'near_priority',
            'title' => $this->_helper->__('Near Priority'),
        ));
        
        $options = Mage::getResourceModel('SunshineBiz_LBS_Model_Resource_SearchTag_Collection')
                ->load()
                ->toOptionArray(false);
        array_unshift($options, array('value' => '0', 'label' => Mage::helper('Mage_Core_Helper_Data')->__('-- Please Select --')));
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

        $model = Mage::registry('lbs_searchTag');

        return $this->getUrl('*/*/edit', array(
                    'id' => $model->getId()
                ));
    }
}