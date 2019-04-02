<?php
/**
 * Created by PhpStorm.
 * User: Utisateur
 * Date: 19/03/2019
 * Time: 15:38
 */

namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class MaintenanceSubscriber implements EventSubscriberInterface // on implemente l'interface - on définit un contrat avec l'EventSubscriberInterface
    //class pour enregister un event
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig; // pour la vue on récupère twig en l'injectant dans le constructeur
    }


    public function methodCalledOnKernelResponse(FilterResponseEvent $filterResponseEvent) //filtrer la réponse
    {

            $maintenance = getenv('MAINTENANCE');

            //dd($maintenance);

        if ($maintenance==='true')
        { // si je suis en maintenance je vais créer une vue
            $content = $this->twig->render('maintenance/maintenance.hmtl.twig');//je recupère $tqig et appel la methode render
            $response = new Response($content);//je construit un objet response parce que symfony attend forcement une response
            // a l'interieur on met le contenu de ma reponse

            return $filterResponseEvent->setResponse($response); //et j'injecte ma réponse dans la réponse qui sera rendue
            //du coup ça va remplacer la réponse qui était prévue
        }
            // si on est en maintenance on remplace la réponse que tu vas envoyer par notre vue twig
            return $filterResponseEvent->getResponse()->getContent();// si je veux afficher le contenu - appelle la rép qui devrait être envoyée
    }

    public static function getSubscribedEvents()
    {
        return [ // retourner un tableau avec en clé ce que je veux écouter et en valeur ma methode qui sera appelée
            KernelEvents::RESPONSE => 'methodCalledOnKernelResponse'
        ];
    }

}