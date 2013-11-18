<?php

class Application_Form_News extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $this->addElement(
            'text',
            'title',
            array(
                'label' => 'Titulo',
                'required' => true
            )
        );
        
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
            'text',
            'youtube',
            array(
                'label' => 'Codigo de video en youtube'
            )
        );
        
        $this->addElement(
            'textarea',
            'comment',
            array(
                'label' => 'Contenido',
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

