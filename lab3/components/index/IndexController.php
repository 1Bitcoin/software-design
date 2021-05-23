<?php

require_once(COMPONENT_INDEX . 'IndexView.php');
require_once(COMPONENT_INDEX . 'IndexModel.php');
require_once(COMPONENT_BASE . 'Controller.php');

require_once(REPOSITORY . 'statistics/StatisticsRepository.php');

class IndexController extends Controller 
{
    public function __construct() 
    {
        $roleID = $this->getRole();

        $statisticsRepository = new StatisticsRepository();

        $this->model = new IndexModel($statisticsRepository, $roleID);
        $this->view = new IndexView();
    }

    public function indexPage() 
    {
        $this->pageData['statistics'] = $this->model->getStatistics()[0];

        if (isset($_COOKIE['logged_user']))
        {
            $data = json_decode($_COOKIE['logged_user'], true);
            $this->pageData['session']['role_id'] = $data['role_id'];
            
            $this->view->render($this->pageData);
        }
        else
        {
            $this->view->renderGuestPage($this->pageData);
        }
    }
}