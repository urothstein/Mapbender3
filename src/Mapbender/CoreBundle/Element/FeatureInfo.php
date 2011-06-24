<?php

namespace Mapbender\CoreBundle\Element;

use Mapbender\CoreBundle\Component\Element;
use Mapbender\CoreBundle\Component\ElementInterface;
use Symfony\Component\HttpFoundation\Response;

class FeatureInfo extends Element implements ElementInterface {
	public function getTitle() {
		return "FeatureInfo";
	}

	public function getDescription() {
		return "Renders a button to show the response of a featureinfo request";
	}

	public function getTags() {
		return array('button', 'getfeatureinfo');
	}

	public function getAssets() {
            return array(
                'js' => array(
                    'mapbender.element.button.js',
                    'mapbender.element.featureinfo.js'
                ),
                'css' => array(
                    'mapbender.element.featureinfo.css'
                )
            );
	}


    public function getConfiguration() {
        $opts = $this->configuration;
        $elementId = $this->configuration['target'];
        $finalId = $this->application->getFinalId($elementId);
        $opts = array_merge($opts, array('target' => $finalId));
        return array(
            'options' => $opts,
            'init' => 'mb_featureinfo'
        );
    }

    public function httpAction($action) {
        $response = new Response();
        switch($action) {
            case 'get':
                
                $output = array(
                    'test' => 'Ajax Response'
                );

                $response->setContent(json_encode($output));
                $response->headers->set('Content-Type', 'application/json');
                return $response;
        }
    }

	public function	render() {
            return $this->get('templating')->render('MapbenderCoreBundle:Element:featureinfo.html.twig', array(
                'id' => $this->id,
                'configuration' => $this->configuration));
	}
}

