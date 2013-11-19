<?php

class Application_Form_Gallery extends Zend_Form
{

    public function init()
    {
        $this->addElement(
            'file',
            'image',
            array(
                'label' => 'Imagen',
                'destination' => APPLICATION_PATH . '/../img/gallery',
                'validators' => array(
                    'Extension' => 'jpg,jpeg'
                )
            )
        );
        
        $this->addElement(
            'select',
            'year',
            array(
                'label' => '¿De que año es la imagen?',
                'required' => true
            )
        );
        $year = $this->getElement('year');
        for($i = 1986; $i <= date('Y'); $i++) {
            $year->addMultiOption($i, $i);
        }
        $this->setDefault('year', date('Y'));
        
        $this->addElement(
            'text',
            'description',
            array(
                'label' => 'Descripcion opcional'
            )
        );
        
        $this->addElement(
            'submit',
            'addTour',
            array(
                'label' => 'Agregar'
            )
        );
    }


}

