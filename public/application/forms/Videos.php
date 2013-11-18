<?php

class Application_Form_Videos extends Zend_Form
{

    public function init()
    {
        $this->addElement(
            'text',
            'youtube',
            array(
                'label' => 'url de video en youtube',
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

