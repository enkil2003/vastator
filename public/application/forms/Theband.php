<?php

class Application_Form_Theband extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->addElement(
            'file',
            'image',
            array(
                'label' => 'Imagen',
                'destination' => APPLICATION_PATH . '/../img/gallery',
                'validators' => array(
                    'Extension' => 'jpg,jpeg,png,gif'
                )
            )
        );
        
        $this->addElement(
            'textarea',
            'history',
            array(
                'label' => 'Historia',
                'required' => true
            )
        );
        
        $this->addElement(
            'submit',
            'addNews',
            array(
                'label' => 'Agregar'
            )
        );
    }


}

