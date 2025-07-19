<?php

class PracticeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function spotters()
    {
        $data = [
            'title' => 'Practice Spotters Quiz - Radiology Resident',
            'description' => 'Interactive spotter quizzes for radiology practice',
            'page_title' => 'Practice Spotters',
            'page_description' => 'Test your knowledge with interactive spotter quizzes',
            'section' => 'practice',
            'subsection' => 'spotters'
        ];
        
        $this->view('frontend/practice/spotters', $data);
    }
    
    public function osce()
    {
        $data = [
            'title' => 'Practice OSCE Quiz - Radiology Resident',
            'description' => 'Interactive OSCE practice sessions',
            'page_title' => 'Practice OSCE',
            'page_description' => 'Practice OSCE scenarios with interactive quizzes',
            'section' => 'practice',
            'subsection' => 'osce'
        ];
        
        $this->view('frontend/practice/osce', $data);
    }
    
    public function examCases()
    {
        $data = [
            'title' => 'Practice Exam Cases - Radiology Resident',
            'description' => 'Interactive clinical case practice sessions',
            'page_title' => 'Practice Exam Cases',
            'page_description' => 'Practice with interactive clinical case scenarios',
            'section' => 'practice',
            'subsection' => 'exam-cases'
        ];
        
        $this->view('frontend/practice/exam-cases', $data);
    }
    
    public function tableViva()
    {
        $data = [
            'title' => 'Practice Table Viva - Radiology Resident',
            'description' => 'Interactive table viva practice sessions',
            'page_title' => 'Practice Table Viva',
            'page_description' => 'Practice table viva with interactive Q&A sessions',
            'section' => 'practice',
            'subsection' => 'table-viva'
        ];
        
        $this->view('frontend/practice/table-viva', $data);
    }
}
