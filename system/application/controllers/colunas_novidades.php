<?php

class colunas_novidades extends Controller
{
	public function __construct()
	{
		parent::Controller();
		$this->load->model('useful');
		$this->load->entity('CmsNewsletter');
	}
	
	public function index()
	{
	    $this->lista();
	}
	
	public function lista($catId = null, $authorId = null, $page = 1)
	{
	    $catId = isset($_POST['cat']) ? $_POST['cat'] : null;
	    $authorId = isset($_POST['author']) ? $_POST['author'] : null;

	    $this->load->entity('CmsNews');
	    $this->load->entity('CmsNewsCategory');
	    $this->load->entity('CmsNewsAuthor');
	    
	    $this->load->uiPagination();
	    
	    $catId = (int) $catId;
	    $authorId = (int) $authorId;
	    
	    $total = CmsNews::countForPagination($catId, $authorId);
	    
	    $pagination = new UiPagination();
	    $pagination->set_pagination($total, $page, 5, 5);
	    $pagination->set_base_url(base_url() . URL_COLUNAS_E_NOVIDADES . $catId . '/' . $authorId . '/[PAGE]');
	    
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/colunas_novidades', array(
	        '_catId' => $catId,
	        '_authorId' => $authorId, 
            'categories' => CmsNewsCategory::getCombo(),
	        'authors' => CmsNewsAuthor::getCombo(),
	        'pagination' => $pagination,
	        'news' => CmsNews::getNews($catId, $authorId, $pagination->limit, $pagination->offset),
	        'newsletter' => $this->load->view('frontend/form/newsletter', array('formx' => $this->useful->getNewsletterForm()), true),
	    ));
	    $this->load->view('frontend/footer', array(
            'news' => CmsNews::getNewsFooter()
	    ));
	}
	
	public function detalhe($slug)
	{
	    $this->load->entity('CmsNews');
	    
	    $news = CmsNews::getNewsBySlug($slug);
	    
	    if (count($news) !== 1) {
	        redirect(URL_COLUNAS_E_NOVIDADES);
	    }
	    
	    $this->load->view('frontend/top', $this->data);
	    $this->load->view('frontend/colunas_novidades_detalhes', array(
	       'news' => array_shift($news),
           'newsletter' => $this->load->view('frontend/form/newsletter', array('formx' => $this->useful->getNewsletterForm()), true),
	    ));
	    $this->load->view('frontend/footer', array(
	        'news' => CmsNews::getNewsFooter()
		));
	}
}
