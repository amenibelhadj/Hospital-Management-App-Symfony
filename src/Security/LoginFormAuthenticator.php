<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class LoginFormAuthenticator extends AbstractLoginFormAuthenticator 
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');

        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        $user = $token->getUser();

        if(in_array('ROLE_ADMIN',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_admin_index'));
        }
        if(in_array('Patient',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_patient_index'));
        }
        if(in_array('Medecin',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_medecin_index'));
        }
        if(in_array('Infirmier',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_infirmier_index'));
        }
        if(in_array('Agent de reclamation',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_agent_index'));
        }
        if(in_array('Pharmacien',$user->getRoles(),true)) {
            return new RedirectResponse($this->urlGenerator->generate('app_pharmacien_index'));
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($this->urlGenerator->generate('app_user_index'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
