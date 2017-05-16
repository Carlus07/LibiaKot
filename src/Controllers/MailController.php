<?php

namespace Controllers;

class MailController extends Controller {
	
    public function confirmation() {
        $page = (isset($_POST['page'])) ? $_POST['page'] : $_GET['m'];
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