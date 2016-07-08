<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Quintal de Trocas</title>
    <link href='https://fonts.googleapis.com/css?family=Lato:400,400italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/colorbox.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/form.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/flexslider.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>js/fancybox/jquery.fancybox-1.3.4.css"
          media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/jquery.rating.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/styles.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/jquery.autocomplete.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/flat.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/chosen.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/grid.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>slick/slick-theme.css"/>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/custom-form.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/icheck.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.flexslider-min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.ui.core.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.ui.widget.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.ui.accordion.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.ui.tabs.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.carousel.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.rating.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/admin/buscacep.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/autocomplete.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.jCombo.min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/chosen.jquery.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.colorbox.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/jquery.colorbox-min.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>slick/slick.min.js"></script>
    <script>
        var base_url = '<?php echo $base_url; ?>';
        var selected_city = '<?php echo isset($toyCity) ? $toyCity : '';?>';
    </script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/scripts.js"></script>
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <script type="text/javascript" src="<?php echo $base_url; ?>js/PIE.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/ie.css" media="all"/>
    <![endif]-->
</head>
<body>
<?php
if (preg_match("/www.*.quintaldetrocas.com.br/", $_SERVER['HTTP_HOST'])) {
    ?>
    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

        ga('create', 'UA-48166026-1', 'quintaldetrocas.com.br');
        ga('send', 'pageview');
    </script>
    <div id="fb-root"></div>
    <script>(function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s);
            js.id = id;
            js.src = "//connect.facebook.net/pt_BR/all.js#xfbml=1&appId=1404504076472014";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <?php
}
?>
<script>
    jQuery(document).ready(function () {
        jQuery('header .welcome').click(function () {
            $('ul.add-switcher').slideToggle();
        });
    });
</script>
<div class="wrapper inner-page">
    <div class="w1-extra">
        <header>
            <div class="top">
                <a href="<?php echo $base_url; ?>" class="header-logo">
                    <img src="<?php echo $base_url; ?>img/logo-horizontal.jpg" />
                </a>
                <div class="socials">
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/pages/Quintal-de-Trocas/566744666744086" target="_blank">
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="http://instagram.com/quintaldetrocas" target="_blank">
                                <i class="fa fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UCLRkwDF11WZMEFiX7kOLagg" target="_blank">
                                <i class="fa fa-youtube-play"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="top-menu">
                    <nav>
                        <a href="#" class="open-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </a>
                        <ul class="menu-geral">
                            <li><a href="<?php echo $base_url . URL_QUEM_SOMOS; ?>">Sobre</a></li>
                            <li><a href="<?php echo $base_url . URL_PRODUTOS; ?>">Quero Trocar</a></li>
                            <li><a href="<?php echo $base_url . URL_NA_MIDIA; ?>">Na Mídia</a></li>
                            <li><a href="<?php echo $base_url . 'apoie'; ?>">Apoie</a></li>
                            <li><a href="<?php echo $base_url . URL_PONTOS_DE_TROCAS; ?>">Pontos de Troca</a></li>
                        </ul>
                    </nav>
                    <?php
                    $CI =& get_instance();

                    $CI->load->model('useful');
                    $CI->load->entity('CmsToyCategory');

                    if ($CI->useful->isLogged() == false) {
                        ?>
                        <div class="login-area">
                            <ul>
                                <li><a href="<?php echo $base_url . URL_USUARIO_LOGIN; ?>">Login</a></li>
                            </ul>
                        </div>
                    <?php } else {
                        $user = $CI->useful->getLoggedUser();
                        $user['name'] = strtok($user['name'], ' ');

                        echo sprintf('
								<div class="user-box">
									<div class="welcome">
										<span>BEM-VINDO,</span>
										<strong class="name">%s</strong>
										<span class="icon-arrow"><img src="' . $base_url . 'img/btn-cs-3.png" /></span>
									</div>
									<ul class="add-switcher">
										<li><a href="%s#1">MEUS DADOS</a></li>
										<li><a href="%s#2">MEUS BRINQUEDOS</a></li>
										<li><a href="%s#4">MINHAS TROCAS</a></li>
										<li class="d-block"><a href="%s">SAIR</a></li>
									</ul>
								</div>', truncate($user['name'], 10), $base_url . URL_USUARIO_MEUS_DADOS, $base_url . URL_USUARIO_MEUS_DADOS_MEUS_BRINQUEDOS, $base_url . URL_USUARIO_MEUS_DADOS_MINHAS_TROCAS, $base_url . URL_USUARIO_LOGOUT);
                    }
                    ?>
                </div>
                <div class="clear"></div>
            </div><!-- /top -->
        </header><!-- /header -->
