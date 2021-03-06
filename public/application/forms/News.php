<?php

class Application_Form_News extends Zend_Form
{

    public function init()
    {
        $this->setAttrib('enctype', 'multipart/form-data');
        $this->setName('newsForm');
        
        $this->addElement(
            'hidden',
            'id'
        );
        
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
            'checkbox',
            'shrink',
            array(
                'label' => '¿Recortar y encuadrar imagen al finalizar?',
                'value' => true
            )
        );
        
        $this->addElement(
            'checkbox',
            'facebook',
            array(
                'label' => '¿Publicar en Facebook?',
                'value' => true
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
    
    public function populate(array $data)
    {
        if ($data['image'] != '') {
            $this->addElement(
                'hidden',
                'thumb',
                array(
                    'value' => $data['image'],
                )
            );
            unset($data['image']);
        }
        parent::populate($data);
    }

}

