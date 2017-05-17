<?php
namespace Controllers\Tools;
use Models\Session;
//use Permission;
/**
 * Class Navi
 * Classe permettant le rafraichissement dynamique des pages
 */
class Navi
{
	private $entityManager;

    /**
     * Retourne le champ de connexion
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @return string contenu en html
     */
    public static function topBar($translation, $variableReturn = false)
    {
        if ($variableReturn) ob_start();
        if (Session::get('Role') == 1) echo '<span><a class="login" href="?p=user.login">'.$translation['login'].'</a><small>'.$translation['or'].'</small><a class="register" href="?p=user.register">'.$translation['register'].'</a></span>';
        else echo '<span style="color:white">'.$translation['welcome'].' '.ucfirst(Session::get('FirstNameUser')).' ! <a class="logout" href="?o=user.logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a></span>';
        if ($variableReturn) return ob_get_clean();
    }

    /**
     * Retourne l'image et la barre de recherche
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @return string contenu en html
     */
    public static function navTop($translation, $variableReturn = false)
    {
    	if ($variableReturn) ob_start();
    	echo '<div class="navbar-left col-sm-6 text-center">
			<a class="navbar-logo" href="?p=home.index"><img src="web/pictures/libiakot.png"></a>
		</div>
		<div class="col-sm-5 offset-sm-1 text-center">
            <form action="?r=housing.search" method="POST">
    			<div class="searchBox">
    	      		<input class="form-control input-search" name="search" id="search" placeholder="'.$translation["search"].'" type="text" required> 
    	      		<button class="button-search input-group-addon" type="submit"><i class="fa fa-search"></i></button>
    	  		</div>
            </form>
    	</div>';
    	if ($variableReturn) return ob_get_clean();
    }

    /**
     * Retourne le menu de navigation
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @return string contenu du menu en html
     */
    public static function navBar($translation, $variableReturn = false)
    {
    	$menus = static::getMenus();
    	if ($variableReturn) ob_start();
    	echo '<div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
	    	<a class="navbar-brand backToHome" href="?p=home.index">'.$translation['home'].'</a>
	  	</div>';
	  	echo '<div class="collapse navbar-collapse">
	  		<ul class="nav navbar-nav">';
	  			foreach($menus as $key => $menu)
	  			{
                    $href = ((is_int($key)) || (!empty(current($menu)))) ? "#" : $key;
	  				echo '<li class="dropdown dropdownMenu"><a href="'.$href.'" class="dropdown-toggle dropdownMenuCat" data-toggle="dropdown">'.key($menu);
                    echo (!empty(current($menu))) ? '<span class="caret"></span></a>' : '</a>';
                    if (!empty(current($menu)))
                    {
                        echo '<ul class="dropdown-menu pull-left">';
			        	foreach($menu as $key=> $subMenu)
			        	{
                            foreach ($subMenu as $key => $value) 
                            {
						  		echo '<li class="dropdownSubMenu">
							  			<a class="textSubMenu" href="'.$key.'">'.$value.'</a>
							  		</li>';
					  	    }
                        }
				        echo '</ul>';
                    }
		      		echo'</li>';
		      	}
	    	echo '</ul>'.
	    	static::getLink($translation).
		'</div>';
		if ($variableReturn) return ob_get_clean();
    }

    /**
     * Retourne le footer
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @return string contenu du footer en html
     */
    public static function footer($translation, $variableReturn = false)
    {
        if ($variableReturn) ob_start();
        echo '<div class="col-sm-4 col-xs-12">
                <div class="footerLink">
                    <h5>'.$translation['footerTitle1'].'</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">'.$translation['footerTitle1'].'</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="footerLink">
                    <h5>'.$translation['footerTitle2'].'</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">'.$translation['lease'].'</a></li>
                        <li><a href="#">'.$translation['housing'].'</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="footerLink">
                    <h5>'.$translation['footerTitle3'].'</h5>
                    <ul class="list-unstyled">
                        <li><a href="?p=home.contact">'.$translation['contact'].'</a></li>
                        <li>081/72 50 82</li>
                        <li><a href="mailto:cathy.jentgen@unamur.be">cathy.jentgen@unamur.be</a></li>
                    </ul>
                    <ul class="list-inline">
                        <li><a href="https://twitter.com/unamur"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="https://www.facebook.com/UniversitedeNamur/?fref=ts"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="https://www.unamur.be"><img class="unamur" src="web/pictures/unamur.png"/></a></li>
                    </ul>
                </div>
            </div>';
        if ($variableReturn) return ob_get_clean();
    }

    /**
     * Retourne la redirection du mail
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @link $link lien vers la redirection
     * @return string contenu de la redirection en html
     */
    public static function getRedirection($translation, $variableReturn = false, $link = "")
    {
        if ($variableReturn) ob_start();
        echo '<tr>
               <td class="webversion" style="padding: 9px;vertical-align: top;font-size: 11px;line-height: 16px;text-align: center;width: 250px;color: #9a9c9e;font-family: sans-serif">'.$translation['redirection'].'<a href="'.$link.'" style="color: #9a9c9e;text-decoration: none;font-weight: bold"> '.$translation['click'].'</a> </td>
            </tr>';
        if ($variableReturn) return ob_get_clean();
    }

    /**
     * Retourne le contenu du mail en fonction de son type
     * @param $translation reprend les traductions
     * @param $variableReturn permet de dire si on affiche directement ou si on retourne l'ensemble du texte
     * @param $pseudo prénom de l'utilisateur
     * @param $type type du mail
     * @link $link lien pour pouvoir utiliser un bouton
     * @return string contenu du mail en html
     */
    public static function getContentMail($translation, $variableReturn = false, $pseudo, $type = "register", $link = " ", $password = "")
    {
        if ($variableReturn) ob_start();
        switch ($type)
        {
            case "register" :
            {
                echo '<table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                               
                                  <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold">'.$translation['welcome'].' '.$pseudo.'</strong> </h1>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['contentRegister'].'</p>
                                  </p> 
                               </td>
                            </tr>
                         </tbody>
                      </table>
                      <table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                                  <div class= "btn" style="Margin-bottom: 20px;text-align: center">
                                     <a href="'.$link.'" style= "display: inline-block;text-align: center;text-decoration: none;border-radius: 6px;-moz-border-radius: 6px;color: #fff;font-size: 16px;line-height: 24px;font-family: sans-serif;background-color: #55ab26;padding: 15px 60px">'.$translation['buttonRegister'].'</a>
                                  </div>
                               </td>
                            </tr>
                         </tbody>
                      </table>';
                break;
            }
            case "addHousing" :
            {
                echo '<table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                               
                                  <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold">'.$translation['welcome'].' '.$pseudo.'</strong> </h1>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['contentAddHousing'].'</p>
                                  </p> 
                               </td>
                            </tr>
                         </tbody>
                      </table>';
                break;
            }
            case "newRequest" :
            {
                echo '<table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                               
                                  <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold">'.$translation['welcome'].' '.$pseudo.'</strong> </h1>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['request'].'</p>
                                  </p> 
                               </td>
                            </tr>
                         </tbody>
                      </table>';
                break;
            }
            case "updateHousing" :
            {
                echo '<table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                               
                                  <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold">'.$translation['welcome'].' '.$pseudo.'</strong> </h1>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['contentAddHousing'].'</p>
                                  </p> 
                               </td>
                            </tr>
                         </tbody>
                      </table>';
                break;
            }
            case "changepassword" :
            {
                echo '<table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                               
                                  <h1 style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 19px;Margin-bottom: 20px;text-align : center;line-height: 22px"> <strong style="font-weight: bold">'.$translation['welcome'].' '.$pseudo.'</strong> </h1>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['contentChangePassword'].'</p>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['yourPassword'].'<strong>'.$password.'</strong></p>
                                  <p style= "Margin-top: 0;font-weight: normal;color: #808285;font-family: sans-serif;font-size: 14px;Margin-bottom: 20px;text-align:center;line-height: 22px">'.$translation['recallOption'].'</p>
                               </td>
                            </tr>
                         </tbody>
                      </table>
                      <table style= "border-collapse: collapse;border-spacing: 0;width: 100%" width="100%">
                         <tbody>
                            <tr>
                               <td class= "padded" style= "padding: 0;vertical-align: top;padding-left: 40px;padding-right: 40px;text-align: left">
                                  <div class= "btn" style="Margin-bottom: 20px;text-align: center">
                                     <a href="'.$link.'" style= "display: inline-block;text-align: center;text-decoration: none;border-radius: 6px;-moz-border-radius: 6px;color: #fff;font-size: 16px;line-height: 24px;font-family: sans-serif;background-color: #55ab26;padding: 15px 60px">'.$translation['buttonChangePassword'].'</a>
                                  </div>
                               </td>
                            </tr>
                         </tbody>
                      </table>';
                break;
            }
        }
        echo '</div></div>';
        if ($variableReturn) return ob_get_clean();
    }

    /**
     * Recupère le menu en fonction du rôle de l'utilisateur
     * @return array $menus
     */
    private static function getMenus()
    {
        //Recupère le role de l'utilisateur
    	$role = Permission::getRole(Session::get("idUser"));
        $menus = array();
        //Recherche du contenu du menu en fonction du rôle de l'utilisateur
        $menu = ($role == 1) ? "Type" : 'Menu';
        $subMenu = ($role == 1) ? "SubType" : "SubMenu";
        $typeRepo = static::getConnection()->getRepository($menu);
        $types = ($role == 1) ? $typeRepo->findAll() : $typeRepo->findByIdRole($role);
        $subTypeRepo = static::getConnection()->getRepository($subMenu);
        $subTypes = $subTypeRepo->findAll();     

        $association = array();
        $preKey = "?p=housing.view&r=10&id=";
        foreach($types as $type)
        {
            if (($role != 1) && method_exists($type, 'getLink')) $preKey = "";
            $key = (($role != 1) && method_exists($type, 'getLink') && ($type->getLink() != "")) ? $type->getLink() : $preKey.$type->getId();
            $association[$key] = Language::getLabelTranslation($type->getIdLabel());
            $menus[$key][static::getTraduction($type->getIdLabel())] = array();
        }
        $method = "getId".$menu;
        foreach($subTypes as $subType)
        {
            if(array_key_exists($preKey.$subType->$method()->getId(), $menus))
            {
                if ($role == 1) $link = "?p=housing.view&r=10&t=".$subType->getId();
                else if (!method_exists($subType->$method(), "getLink")) $link = "?p=".$subType->$method()->getLink(); 
                else $link = "?p=".$subType->getLink();

                $menus[$preKey.$subType->$method()->getId()][$association[$preKey.$subType->$method()->getId()]][$link] = static::getTraduction($subType->getIdLabel()->getId());
            }
        }
        return $menus;
    }
    private static function getLink($translation)
    {
        $role = Permission::getRole(Session::get("idUser"));
        switch ($role) {
            case 2:
            {
                $trad = "profile";
                $link = "?p=user.profile";
                break;
            }
            case 3 :
            {
                return "";
                break;
            }
            default:
                $trad = "searchmenu";
                $link = "?p=housing.view&r=10";
                break;
        }
        return '<ul class="nav navbar-nav navbar-right hidden-sm">
                <li><a class="promotion" href="'.$link.'">'.$translation[$trad].'</a></li>
            </ul>';
    }

    /**
     * Recupère l'objet de connexion
     * @return obj $entityManager
     */
    private static function getConnection()
    {
        if (empty($entityManager)) $entityManager = Connection::getConnection();
        return $entityManager;
    }

    private static function getTraduction($idLabel)
    {
        return Language::getLabelTranslation($idLabel);
    }
}
