<?php

class TestController extends Zend_Controller_Action
{

    protected $testModel = null;

    protected $base = null;

    public function init()
    {
        $this->testModel = new Application_Model_Test();
        $base = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        echo "<h4><a href='".$base."/Test'>Test Page </a></h4>";
    }

    public function indexAction()
    {
        $testsStatistics = $this->testModel->getTestsStatistics();
        $this->view->testsStatistics = $testsStatistics;  
    }

    public function addAction()
    {
        $addTestForm = new Application_Form_AddType();
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            if ($addTestForm->isValid($formData)) {
                if($this->testModel->checkDuplication(0,$formData['typeName'])) {
                    $addTestForm->populate($formData);
                    $addTestForm->markAsError();
                    $addTestForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $this->testModel->addTest($formData);
                    $this->_forward("list");
                }
            } else {
                $addTestForm->populate($formData);
            }
        }
        
        $this->view->form = $addTestForm;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam("id");
        
        if ($id) {
            $this->testModel->deleteTest($id);        
            $this->_forward("list");
        }
        else {
            $this->render("search");
        }
    }

    public function listAction()
    {
        $this->view->tests = $this->testModel->getAllTests();  
    }

    public function editAction()
    {
        $addTestForm = new Application_Form_AddType();
        $id = $this->_request->getParam("id");
        
        if ($this->_request->isPost()) {
            $formData = $this->_request->getPost();
            
            if ($addTestForm->isValid($formData)) {
                if($this->testModel->checkDuplication($id, $formData['typeName'])) {
                    $addTestForm->populate($formData);
                    $addTestForm->markAsError();
                    $addTestForm->getElement("typeName")->addError("Name is used Before");
                }
                else {
                    $editData = array('name'=>$formData['typeName']);
                    $this->testModel->editTest($id,$editData);
                    $this->_forward("list");
                }
            } else {
                $addTestForm->populate($formData);
            }
        }
        else {    
            if ($id) {
                $test = $this->testModel->viewTest($id);
                
                if ($test) {
                    $formData = array('typeId'=>$test[0]['id'], 'typeName'=>$test[0]['name'], 'submit'=> "Edit");
                    $addTestForm->setName("Edit Test :");
                    $addTestForm->populate($formData); 
                }
                else {
                    $this->render("search");
                }
            }
            else {
                $this->render("search");
            }
        }
        
        $this->view->form = $addTestForm;
    }

    public function viewAction()
    {
        $id = $this->_request->getParam("id");
        
        if ( $id ) {
            $test = $this->testModel->viewTest($id);
            $this->view->test = $test;
        }
        else {
            $this->render("search");
        }    
    }

    public function searchAction()
    {
        $key = $this->_request->getParam("key");
        
        if ($key) {
            $this->view->key = $key;
            $this->view->tests = $this->testModel->searchByName("%".$key."%");
        }
    }


}













