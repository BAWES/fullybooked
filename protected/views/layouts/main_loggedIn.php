<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/fmain.css" />
        <link rel="stylesheet" type='text/css' href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom-theme/jquery-ui-1.10.1.custom.css" />
        <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css' />
        <!--[if IE 7]>
            <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie7.css" rel='stylesheet' type='text/css'/>
        <![endif]-->

        <script>
            document.createElement('header');
            document.createElement('nav');
            document.createElement('article');
            document.createElement('footer');
        </script>

        <!--[if lt IE 9]>
            <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/html5shiv.js"></script>
        <![endif]-->

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.3.min.js" type='text/javascript'></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/khalidm.js" type='text/javascript'></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.10.1.custom.min.js" type='text/javascript'></script>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>

    <body>
        <header>
            <a href="<?php echo Yii::app()->createUrl('site/index'); ?>" id='logo'>
                <p class='hide-text'>Fully Booked</p>
                <img src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.jpg"/>
            </a>

            <nav>
                <a href="<?php echo $this->createUrl("appointment/search") ?>"><span class='appointment'></span>Book Appointment</a>
                <a href="<?php echo $this->createUrl("directory/") ?>"><span class='directory'></span>Online Directory</a>
                <a href='#account1' id='account'><span class='account'></span>Account<br/>Info</a>
            </nav>
        </header>
        <div id='accountPopup'>
            <p class='logged'>Welcome <a href="<?php echo $this->createUrl("site/logout") ?>">Logout</a></p>
            <section class='textbox'>
                <div class='profileName'><?php echo User::model()->findByPk(Yii::app()->user->getId())->user_name; ?></div>
                <ol class='loggedNav'>
                    <li><a href="<?php echo $this->createUrl("user/appointments") ?>">View / Edit Appointments</a></li>
                    <li><a href="<?php echo $this->createUrl("user/view") ?>">View / Edit Account Info</a></li>
                </ol>
            </section>
        </div>
        <?php echo $content; ?>


        <footer>
            <nav>
                <ul>
                    <li><a href='#'>About Us</a></li>
                    <li><a href='#'>Services</a></li>
                    <li><a href='#'>Prices</a></li>
                    <li><a href='#'>Book Now</a></li>
                    <li><a href='#'>Directory</a></li>
                    <li><a href='#'>FAQ</a></li>
                    <li><a href='#'>Demo</a></li>
                    <li><a href='#'>Terms & Conditions</a></li>
                    <li><a href='#'>Copyrights</a></li>
                    <li><a href='#'>Client Login</a></li>
                    <li class='last'><a href='#'>Member Login</a></li>
                </ul>
            </nav>
            <p>&copy; Fullybookedkw.com All Rights Reserved 2012.</p>

        </footer>


    </body>
</html>
