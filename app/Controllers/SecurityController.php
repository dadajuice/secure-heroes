<?php namespace Controllers;

use Zephyrus\Exceptions\IntrusionDetectionException;
use Zephyrus\Exceptions\InvalidCsrfException;
use Zephyrus\Exceptions\UnauthorizedAccessException;
use Zephyrus\Network\Response;
use Zephyrus\Security\Authorization;
use Zephyrus\Security\ContentSecurityPolicy;
use Zephyrus\Security\Controller as ZephyrusBaseController;
use Zephyrus\Security\CrossOriginResourcePolicy;


abstract class SecurityController extends ZephyrusBaseController
{
    public function before(): ?Response
    {
        $this->applyContentSecurityPolicies();
        $this->setupAuthorizations();
        //$this->applyCrossOriginResourceSharing();

        try {
            parent::before();
        } catch (IntrusionDetectionException $exception) {

            $data = $exception->getIntrusionData();
            if ($data['impact'] >= 10) {
                // Do something (logs, database report, redirect, ...)
                // return $this->abortForbidden();
            }
        } catch (InvalidCsrfException $exception) {

            // Do something (logs, database report, redirect, ...)
            return $this->abortForbidden();
        } catch (UnauthorizedAccessException $exception) {

            // Do something (logs, database report, redirect, ...)
            //return $this->abortForbidden();
            die("UNAUTHORIZED ACCESS DETECTED");
        }

        // No security issue found, continue processing of middleware chain or
        // route processing.
        return null;
    }

    private function setupAuthorizations()
    {
        parent::getAuthorization()->setMode(Authorization::MODE_WHITELIST);

        // Setup rules
        parent::getAuthorization()->addSessionRule('connected', 'user');
        parent::getAuthorization()->addRule('public', function () {
            return true;
        });

        // Route protection
        parent::getAuthorization()->protect('/', Authorization::ALL, 'public');
        parent::getAuthorization()->protect('/logout', Authorization::ALL, 'public');
        parent::getAuthorization()->protect('/heroes', Authorization::ALL, 'connected');
    }

    /**
     * Defines the Content Security Policies (CSP) to use for all inherited controllers. The ContentSecurityPolicy class
     * helps to craft and maintain the CSP headers easily. These headers should be seriously crafted since they greatly
     * help to prevent cross-site scripting attacks.
     *
     * @see https://content-security-policy.com/
     */
    private function applyContentSecurityPolicies()
    {
        $csp = new ContentSecurityPolicy();
        $csp->setDefaultSources(["'self'"]);
        $csp->setFontSources(["'self'", 'https://fonts.googleapis.com', 'https://fonts.gstatic.com']);
        $csp->setStyleSources(["'self'", "'unsafe-inline'", 'https://fonts.googleapis.com']);
        $csp->setScriptSources(["'self'", 'https://ajax.googleapis.com', 'https://maps.googleapis.com',
            'https://www.google-analytics.com', 'http://connect.facebook.net']);
        $csp->setChildSources(["'self'", 'http://staticxx.facebook.com']);
        $csp->setImageSources(["'self'", 'data:']);
        $csp->setBaseUri([$this->request->getBaseUrl()]);

        /**
         * The SecureHeader class is the instance that will actually sent all the headers concerning security including
         * the CSP. Other headers includes policy concerning iframe integration, strict transport security and xss
         * protection. These headers are sent automatically from the Zephyrus security controller this class inherits
         * from.
         */
        parent::getSecureHeader()->setContentSecurityPolicy($csp);
    }

    /**
     * Defines the Access-Control-Allow-* headers to use for all inherited controllers. The CrossOriginResourcePolicy
     * class helps craft and maintain the CORS headers easily.
     */
    private function applyCrossOriginResourceSharing()
    {
        $cors = new CrossOriginResourcePolicy();
        $cors->setAccessControlAllowOrigin('*');
        parent::getSecureHeader()->setCrossOriginResourcePolicy($cors);
    }
}
