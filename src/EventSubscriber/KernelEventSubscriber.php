<?php

namespace App\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class KernelEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $userLogger;
    private LoggerInterface $apiLogger;
    private LoggerInterface $importLogger;
    private LoggerInterface $exportLogger;
    private Security $security;

    public function __construct(
        LoggerInterface $userLogger,
        LoggerInterface $apiLogger,
        LoggerInterface $importLogger,
        LoggerInterface $exportLogger,
        Security $security
    ) {
        $this->userLogger = $userLogger;
        $this->apiLogger = $apiLogger;
        $this->importLogger = $importLogger;
        $this->exportLogger = $exportLogger;
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST    => 'onKernelRequest',
            KernelEvents::CONTROLLER => 'onKernelController',
            KernelEvents::RESPONSE   => 'onKernelResponse',
            KernelEvents::EXCEPTION  => 'onKernelException',
        ];
    }


    public function onKernelRequest(RequestEvent $event): void
    {
        $request  = $event->getRequest();
        $route    = $request->attributes->get('_route');
        $user     = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'ANONYMOUS';
        $ip       = $request->getClientIp();

        if ($route === 'api_login_check') {
            $this->userLogger->info('Tentative de connexion', [
                'username' => $request->get('username', 'N/A'),
                'ip' => $ip,
            ]);
        }

        if (str_contains($route, 'verif-eligible') || str_contains($route, 'notif-payment')) {
            $this->apiLogger->info('Appel API reçu', [
                'route'  => $route,
                'method' => $request->getMethod(),
                'params' => $request->query->all(),
                'username'   => $username,
                'ip'     => $ip,
            ]);
        } elseif (str_contains($route, 'import')) {
            $this->importLogger->info('Début d\'importation', [
                'username' => $username,
                'ip' => $ip,
            ]);
        } elseif (str_contains($route, 'export')) {
            $this->exportLogger->info('Début d\'exportation', [
                'username' => $username,
                'ip' => $ip,
            ]);
        } else {
            $this->userLogger->info('Requête utilisateur', [
                'route' => $route,
                'username' => $username,
                'ip' => $ip,
            ]);
        }
    }

    /**
     * Log du contrôleur exécuté.
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        $route = $event->getRequest()->attributes->get('_route');
        $user = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'ANONYMOUS';

        if (is_array($controller)) {
            $controllerName = get_class($controller[0]);
            $methodName = $controller[1];
        } else {
            $controllerName = get_class($controller);
            $methodName = '__invoke';
        }

        if (str_contains($route, 'verif-eligible') || str_contains($route, 'notif-payment')) {
            $this->apiLogger->info("Contrôleur API exécuté", [
                'controller' => $controllerName,
                'method'     => $methodName,
                'username'    => $username,
            ]);
        } else {
            $this->userLogger->info("Contrôleur exécuté", [
                'controller' => $controllerName,
                'method'     => $methodName,
                'username'   => $username,
            ]);
        }
    }

    /**
     * Log des réponses et validation de la connexion.
     */
    public function onKernelResponse(ResponseEvent $event): void
    {
        $request  = $event->getRequest();
        $route    = $request->attributes->get('_route');
        $response = $event->getResponse();
        $user     = $this->security->getUser();
        $username = $user ? $user->getUserIdentifier() : 'ANONYMOUS';
        $status   = $response->getStatusCode();
        $ip       = $request->getClientIp();

        if ($route === 'api_login_check') {
            if ($status === 200) {
                $this->userLogger->info('Connexion réussie', [
                    'username' => $username,
                    'ip'       => $request->getClientIp(),
                    'status'   => $status,
                ]);
            } else {
                $this->userLogger->warning('Échec de connexion', [
                    'username' => $request->get('username', 'N/A'),
                    'ip' => $request->getClientIp(),
                    'status' => $status,
                ]);
            }
        }

        if (str_contains($route, 'verif-eligible') || str_contains($route, 'notif-payment') && $user) {
            $this->apiLogger->info('Réponse API envoyée', [
                'status_code' => $status,
                'username' => $username,
                'ip' => $ip,
            ]);
        } elseif (str_contains($route, 'import') && $user) {
            if ($status >= 400) {
                $this->importLogger->error('Importation échouée', [
                    'status_code' => $status,
                    'username' => $username,
                    'ip'   => $ip,
                ]);
            } else {
                $this->importLogger->info('Importation terminée', [
                    'status_code' => $status,
                    'username' => $username,
                    'ip'   => $ip,
                ]);
            }
        } elseif (str_contains($route, 'export') && $user) {
            if ($status >= 400) {
                $this->importLogger->error('Exportation échouée', [
                    'status_code' => $status,
                    'username' => $username,
                    'ip'   => $ip,
                ]);
            } else {
                $this->importLogger->info('Exportation terminée', [
                    'status_code' => $status,
                    'username'    => $username,
                    'ip'          => $ip,
                ]);
            }
        } else {
            if ($status >= 400) {
                $this->importLogger->error('Réponse utilisateur envoyée', [
                    'status_code' => $status,
                    'username' => $username,
                    'ip'   => $ip,
                ]);
            } else {
                $this->importLogger->info('Réponse utilisateur envoyée', [
                    'status_code' => $status,
                    'username'    => $username,
                    'ip'          => $ip,
                ]);
            }
        }
    }

    /**
     * Log des erreurs.
     */
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $request   = $event->getRequest();
        $route     = $request->attributes->get('_route');
        $user      = $this->security->getUser();
        $username  = $user ? $user->getUserIdentifier() : 'ANONYMOUS';
        $ip        = $request->getClientIp();

        $logData = [
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'username' => $username,
            'ip'   => $ip,
        ];

        if (str_starts_with($route, 'api_')) {
            $this->apiLogger->error('Erreur API', $logData);
        } elseif ($route === 'import') {
            $this->importLogger->error('Erreur d\'importation', $logData);
        } elseif ($route === 'export') {
            $this->exportLogger->error('Erreur d\'exportation', $logData);
        } else {
            $this->userLogger->error('Erreur utilisateur', $logData);
        }
    }
}