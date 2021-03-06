<?php

class AjaxController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction()
    {
        // action body
    }

    public function addMedicationHistoryAction()
    {
        $medicationHistoryModel = new Application_Model_MedicationHistory();
        $medicationModel = new Application_Model_Medication();
        
        $medicationName = $this->_request->getParam("medication");
        $medication = $medicationModel->getMedicationByName($medicationName);
        
        if($medication) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['medication'] = $medication['id'];

            if($medicationHistoryModel->addMedicationHistory($data)) {
                echo "done";
            }
            else {
                echo "Medication  has been added before";
            }
        }
        else {
            echo "Medication is not Found";
        }
    }

    public function addDiseaseHistoryAction()
    {
        $diseaseHistoryModel = new Application_Model_DiseaseHistory();
        $diseaseModel = new Application_Model_Disease();
        
        $diseaseName = $this->_request->getParam("disease");
        $disease = $diseaseModel->getDiseaseByName($diseaseName);
        
        if($disease) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['disease'] = $disease['id'];
        
            if($diseaseHistoryModel->addDiseaseHistory($data)) {
                echo "done";
            }
            else {
                echo "Disease  has been added before";
            }  
        }
        else {
            echo "Disease is not Found";
        }

    }

    public function addSurgeryHistoryAction()
    {
        $surgeryHistoryModel = new Application_Model_SugeryHistory();
        $surgeryModel = new Application_Model_Surgery();
        
        $surgeryName = $this->_request->getParam("surgery");
        $surgery = $surgeryModel->getSurgeryByName($surgeryName);
        
        if($surgery) {
            $data['patient'] = $this->_request->getParam("patientId");
            $data['surgery'] = $surgery['id'];

            $surgeryHistoryModel->addSurgeryHistory($data);
            
            echo "done";
        }
        else {
            echo "Surgery is not Found";
        }  
    }


}







