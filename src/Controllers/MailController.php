<?php

namespace Controllers;
use Models\Session;

class MailController extends Controller {
	
    public function confirmation() {
        $value['t'] = (isset($_GET['t'])) ? $_GET['t'] : "";
        $value['m'] = (isset($_GET['m'])) ?  $_GET['m'] : "";
        Session::set('settings', $value);

        $page = (isset($_POST['page'])) ? $_POST['page'] : $value['m'];

        $finalization = 0;
        if (isset($_GET['t']))
        {
            $user = $this->getConnection()->getRepository('User')->findByToken($_GET['t']);
            if (!empty($user)) 
            {
                $user[0]->setConfirmed(true);
                $this->getConnection()->flush();
                $finalization = 1;
            }
        }
        $this->render('mail.confirmation', compact("page", "finalization"));
    }
}