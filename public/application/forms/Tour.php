<?php

class Application_Form_Tour extends Zend_Form
{

    public function init()
    {
        $this->addElement(
            'text',
            'location',
            array(
                'label' => '¿Donde se realizara el evento?',
                'required' => true
            )
        );
        
        $this->addElement(
            'text',
            'created',
            array(
                'label' => '¿Cuando se realizara el evento?',
                'required' => true
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

