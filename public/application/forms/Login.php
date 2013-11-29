<?php

class Application_Form_Login extends Zend_Form
{

    public function init()
    {
        $this->addElement(
            'text',
            'user',
            array(
                'label' => 'Usuario',
                'required' => true
            )
        );
        
        $this->addElement(
            'password',
            'password',
            array(
                'label' => 'Password',
                'required' => true
            )
        );
        
        $this->addElement(
            'checkbox',
            'remember',
            array(
                'label' => '¿Recordar usuario logueado?',
                'description' => 'Si esta opción esta marcada, no volvera a pedir la contraseña hasta no eliminar las cookies del navegador. Esta opcion no registra la contraseña en ningun lado',
                'required' => true
            )
        );
        
        $this->addElement(
            'submit',
            'login',
            array(
                'label' => 'Ingresar'
            )
        );
    }

}

