<?php

class TheoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function notes()
    {
        $data = [
            'title' => 'Theory Notes - Radiology Resident',
            'description' => 'Comprehensive radiology theory notes for medical education',
            'page_title' => 'Theory Notes',
            'page_description' => 'Access comprehensive theory notes for radiology education',
            'section' => 'theory',
            'subsection' => 'notes'
        ];
        
        $this->view('frontend/theory/notes', $data);
    }
    
    public function previousYearQuestions()
    {
        $data = [
            'title' => 'Previous Year Questions - Radiology Resident',
            'description' => 'Previous year radiology exam questions for practice',
            'page_title' => 'Previous Year Questions',
            'page_description' => 'Practice with previous year radiology exam questions',
            'section' => 'theory',
            'subsection' => 'previous-year-questions'
        ];
        
        $this->view('frontend/theory/previous-year-questions', $data);
    }
    
    public function videoTutorials()
    {
        $data = [
            'title' => 'Video Tutorials - Radiology Resident',
            'description' => 'Educational video tutorials for radiology theory',
            'page_title' => 'Video Tutorials',
            'page_description' => 'Learn with expert-led video tutorials',
            'section' => 'theory',
            'subsection' => 'video-tutorials'
        ];
        
        $this->view('frontend/theory/video-tutorials', $data);
    }
}
