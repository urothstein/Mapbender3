<?php

namespace Mapbender\CoreBundle\Controller;

use Mapbender\CoreBundle\Component\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Application controller.
 *
 * @author Christian Wygoda <christian.wygoda@wheregroup.com>
 */
class ApplicationController extends Controller {
    /**
     * Render an application at url /application/{slug}
     *
     * @param string $slug The application slug
     * @return Response HTTP response
     * @Route("/application/{slug}", name="mapbender_application")
	 * @Template()
	 */
    public function applicationAction($slug) {
        $application = $this->getApplication($slug);
        $this->checkAllowedRoles($application->getRoles());
        return $application->render();
    }

    /**
     * Call an application element's action at /application/{slug}/element/{id}/{action}
     * @Route("/application/{slug}/element/{id}/{action}", name="mapbender_element")
     */
    public function elementAction($slug, $id, $action) {
        $application = $this->getApplication($slug);
        $this->checkAllowedRoles($application->getRoles());
        $element = $application->getElement($id);
        if(!$element) {
            throw new HttpNotFoundException("Element can not be found.");
        }
        return $element->httpAction($action);
    }

    /**
     * Given an application slug, find it and inflate it
     * @param string $slug
     * @return Application Application
     */
    private function getApplication($slug){
        //TODO: Check for ORM Applications first, YAML Application only come in
        //second place
        $application = $this->getYamlApplication($slug);
        return $application;
    }
    /**
     * Inflate an application from Yaml
     */
    private function getYamlApplication($slug) {
        // Try to load application configurations from parameters
        if(!$this->container->hasParameter('applications')) {
            throw new NotFoundHttpException('No applications are defined.');
        }
        $apps_pars = $this->container->getParameter('applications');

        // Find desired application configuration
        if(!array_key_exists($slug, $apps_pars)) {
            throw new NotFoundHttpException('Application ' . $slug . ' not found.');
        }

        // instantiate application
        return new Application($this->container, $slug, $apps_pars[$slug]);
    }

    /**
     * Check if the allowed roles set by the application are matched by the current user
     */
    private function checkAllowedRoles(array $allowedRoles) {
        $securityContext = $this->get('security.context');
        $isGrantedAccess = false;
        foreach($allowedRoles as $allowedRole) {
            if($securityContext->isGranted($allowedRole)) {
                $isGrantedAccess = true;
                break;
            };
        }
        if(!$isGrantedAccess) {
            throw new AccessDeniedException("You are not allowed to access this application");
        }
    }
}
