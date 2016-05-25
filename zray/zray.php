<?php
/*********************************
	Bewotec Z-Ray Extension
	Version: 1.00
**********************************/
namespace Bewotec;

use ZRayExtension;

require __DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Cms.php';
require __DIR__.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'Reise.php';

// Create new extension - disabled
$zre = new \ZRayExtension('Bewotec');
$zre->setEnabledAfter('Zend\Mvc\Application::init');

$cms = new Cms();
$reise = new Reise();

$zre->traceFunction('Application\Controller\SiteController::indexAction', 
                     function($context, &$storage) {}, 
                     array($cms, 'onLeaveIndexAction')
);

$zre->traceFunction('Reise\Controller\DetailsController::indexAction',
    function($context, &$storage) {},
    array($reise, 'onLeaveIndexAction')
);

// Collect information for the requests
$zre->traceFunction('Myjack\Service\RestInvokable::doRequest',
                    function($context, &$storage) {},
                    array($cms, 'onLeaveDoRequest')
);

// Summarize that information
$zre->traceFunction('Bewotec\Cms::shutdown', 
                     function($context, &$storage) {},
                     array($cms, 'onLeaveShutdown')
);

register_shutdown_function(array($cms,'shutdown'));

$zre->setMetadata(array(
    array(
        'logo' => __DIR__ . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'logo.png',
    )
));
