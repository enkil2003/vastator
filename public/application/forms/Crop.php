<?php

class Application_Form_Crop extends Zend_Form
{
    public function init()
    {
        $x = new Zend_Form_Element_Hidden('x');
        $x->removeDecorator('DtDdWrapper')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->setValue(0);
        $y = new Zend_Form_Element_Hidden('y');
        $y->removeDecorator('DtDdWrapper')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->setValue(0);
        $w = new Zend_Form_Element_Hidden('w');
        $w->removeDecorator('DtDdWrapper')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->setValue(0);
        $h = new Zend_Form_Element_Hidden('h');
        $h->removeDecorator('DtDdWrapper')
            ->removeDecorator('HtmlTag')
            ->removeDecorator('Label')
            ->setValue(0);
        
        $this->addElements(array($x, $y, $w, $h));
        $submit = new Zend_Form_Element_Submit('submit','Recortar');
        $submit->setName('crop')
            ->setAttrib('disabled', 'disabled');
        $this->addElement($submit);
    }
}
