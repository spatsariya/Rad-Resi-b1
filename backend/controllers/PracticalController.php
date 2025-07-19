<?php

class PracticalController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function spotters()
    {
        $data = [
            'title' => 'Practical Spotters - Radiology Resident',
            'description' => 'Medical imaging spotters for practical exam preparation',
            'page_title' => 'Practical Spotters',
            'page_description' => 'Practice with medical imaging spotters for your practical exams',
            'section' => 'practical',
            'subsection' => 'spotters'
        ];
        
        $this->view('frontend/practical/spotters', $data);
    }
    
    public function osce()
    {
        $data = [
            'title' => 'OSCE - Radiology Resident',
            'description' => 'Objective Structured Clinical Examination for radiology',
            'page_title' => 'OSCE',
            'page_description' => 'Prepare for OSCE with structured clinical scenarios',
            'section' => 'practical',
            'subsection' => 'osce'
        ];
        
        $this->view('frontend/practical/osce', $data);
    }
    
    public function examCases()
    {
        $data = [
            'title' => 'Exam Cases - Radiology Resident',
            'description' => 'Clinical exam cases for radiology practice',
            'page_title' => 'Exam Cases',
            'page_description' => 'Study real clinical cases for exam preparation',
            'section' => 'practical',
            'subsection' => 'exam-cases'
        ];
        
        $this->view('frontend/practical/exam-cases', $data);
    }
    
    public function tableViva()
    {
        $data = [
            'title' => 'Table Viva - Radiology Resident',
            'description' => 'Table viva questions and scenarios for radiology exams',
            'page_title' => 'Table Viva',
            'page_description' => 'Practice table viva questions and clinical scenarios',
            'section' => 'practical',
            'subsection' => 'table-viva'
        ];
        
        $this->view('frontend/practical/table-viva', $data);
    }
}
