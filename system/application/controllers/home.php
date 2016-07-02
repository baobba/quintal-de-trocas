<?php

class home extends Controller
{
    public function __construct()
    {
        parent::Controller();
        $this->load->entity('CmsContent');

        $this->load->entity('CmsToy');
        $this->load->entity('CmsToyImage');
        $this->load->entity('CmsClient');
        $this->load->entity('CmsToyAge');
        $this->load->entity('CmsToyCity');
        $this->load->entity('CmsToyState');
        $this->load->entity('CmsToyBrand');
        $this->load->entity('CmsToyCategory');
        $this->load->entity('CmsExchange');
        $this->load->entity('CmsExchangeMessage');
    }

    public function index($toyCitySelected = '', $toyStateSelected = '', $toyAgeSelected = '', $toyCategorySelected = '', $toyBrandSelected = '', $orderBy = 0, $page = 1, $toyName = '')
    {
        $this->load->entity('CmsNews');

        if ($toyCitySelected == 'undefined') {
            return;
        }

        $this->load->entity('CmsContent');
        $this->load->entity('CmsNews');
        $this->load->uiPagination();

        $toyCitySelected = isset($_POST['toy_city']) ? $_POST['toy_city'] : explode('-', $toyCitySelected);
        $toyCitySelected = is_array($toyCitySelected) ? $toyCitySelected : array();

        $toyStateSelected = isset($_POST['toy_state']) ? $_POST['toy_state'] : explode('-', $toyStateSelected);
        $toyStateSelected = is_array($toyStateSelected) ? $toyStateSelected : array();

        $toyAgeSelected = isset($_POST['toy_age']) ? $_POST['toy_age'] : explode('-', $toyAgeSelected);
        $toyAgeSelected = is_array($toyAgeSelected) ? $toyAgeSelected : array();

        $toyCategorySelected = isset($_POST['toy_category']) ? $_POST['toy_category'] : explode('-', $toyCategorySelected);
        $toyCategorySelected = is_array($toyCategorySelected) ? $toyCategorySelected : array();

        $toyBrandSelected = isset($_POST['toy_brand']) ? $_POST['toy_brand'] : explode('-', $toyBrandSelected);
        $toyBrandSelected = is_array($toyBrandSelected) ? $toyBrandSelected : array();

        $toyAges = CmsToyAge::getCombo();

        if (count($toyStateSelected) == 1) {
            $toyCities = CmsToyCity::getComboByStateId(reset($toyStateSelected));

        } else {
            $toyCities = CmsToyCity::getCombo();
        }

        $toyStates = CmsToyState::getCombo();
        $toyCategories = CmsToyCategory::getCombo();
        $toyBrands = CmsToyBrand::getCombo();

        $total = CmsToy::countForPagination($toyCitySelected, $toyStateSelected, $toyAgeSelected, $toyCategorySelected, $toyBrandSelected);

        $orderBy = isset($_POST['order_by']) ? $_POST['order_by'] : 0;
        $orderBy = (int)$orderBy;

        $toyName = isset($_REQUEST['toy_name']) ? $_REQUEST['toy_name'] : $toyName;
        $toyName = str_replace('O que você quer trocar?', '', $toyName);

        $url = base_url() . URL_PRODUTOS;
        $url .= count($toyCitySelected) && $toyCitySelected[0] !== '' ? implode('-', $toyCitySelected) : 0;
        $url .= '/' . (count($toyStateSelected) && $toyStateSelected[0] !== '' ? implode('-', $toyStateSelected) : 0);
        $url .= '/' . (count($toyAgeSelected) && $toyAgeSelected[0] !== '' ? implode('-', $toyAgeSelected) : 0);
        $url .= '/' . (count($toyCategorySelected) && $toyCategorySelected[0] !== '' ? implode('-', $toyCategorySelected) : 0);
        $url .= '/' . (count($toyBrandSelected) && $toyBrandSelected[0] !== '' ? implode('-', $toyBrandSelected) : 0);
        $url .= '/' . $orderBy;
        $url .= '/[PAGE]';
        $url .= '/' . $toyName;

        $orderBy = (int)$orderBy == 1 ? array('t.name', 'DESC') : array('t.name', 'ASC');

        $pagination = new UiPagination();
        $pagination->set_pagination($total, $page, 24, 6);
        $pagination->set_base_url($url);

        $this->load->view('frontend/top', $this->data);
        $this->load->view('frontend/home', array(
            'box1' => CmsContent::get(CmsContent::ID_HOME_BOX_1),
            'isLogged' => $this->useful->isLogged(),
            'toyAges' => $toyAges,
            'toyCities' => $toyCities,
            'toyStates' => $toyStates,
            'toyCategories' => $toyCategories,
            'toyBrands' => $toyBrands,
            'toyAgeSelected' => $toyAgeSelected,
            'toyCitySelected' => $toyCitySelected,
            'toyStateSelected' => $toyStateSelected,
            'toyCategorySelected' => $toyCategorySelected,
            'toyBrandSelected' => $toyBrandSelected,
            'pagination' => $pagination,
            'toyName' => $toyName,
            'toyList' => $this->load->view('frontend/produtos_list', array(
                'toys' => CmsToy::getToys($toyCitySelected, $toyStateSelected, $toyAgeSelected, $toyCategorySelected, $toyBrandSelected, $orderBy, $toyName, $pagination->limit, $pagination->offset),
            ), true)

        ));
        $this->load->view('frontend/footer', array(
            'news' => CmsNews::getNewsFooter()
        ));
    }

    public function politica()
    {
        $this->load->view('frontend/top_lb', $this->data);
        $this->load->view('frontend/politica', array(
            'content' => CmsContent::get(CmsContent::ID_POLITICA_PRIVACIDADE),
        ));
        $this->load->view('frontend/footer_lb');
    }

    public function termos()
    {
        $this->load->view('frontend/top_lb', $this->data);
        $this->load->view('frontend/termos', array(
            'content' => CmsContent::get(CmsContent::ID_TERMOS_DE_USO),
        ));
        $this->load->view('frontend/footer_lb');
    }
}