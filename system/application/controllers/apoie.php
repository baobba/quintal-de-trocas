<?php

class apoie extends Controller {

    public function __construct() {
        parent::Controller();
    }

    public function index() {
        $this->load->entity('CmsNews');

        $this->load->view('frontend/top', $this->data);
        $this->load->view('frontend/apoie');
        $this->load->view('frontend/footer', array(
            'news' => CmsNews::getNewsFooter()
        ));
    }

}
