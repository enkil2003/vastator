<?php

class Application_Form_Slider extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->addElement(
            'file',
            'image',
            array(
                'label' => 'Imagen',
                'destination' => APPLICATION_PATH . '/../img/slider',
                'validators' => array(
                    'Extension' => 'jpg,jpeg'
                )
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

