<?php

namespace AppBundle\Controller;

use AppBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FeedbackController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request, \Swift_Mailer $mailer)
    {
        throw $this->createNotFoundException();

        /*$mainPage = $this->getDoctrine()->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);
        $mail = $mainPage->getMail();

        $sex = $request->get('sex');
        $firstName = $request->get('first_name');
        $lastName = $request->get('last_name');
        $phone = $request->get('phone');
        $callbackTime = $request->get('callback_time');
        $text = $request->get('text');


        $message = (new \Swift_Message('Anfrage'))
            ->setFrom('feedback@vpl-bau.de')
            ->setTo($mail)
            ->setBody(
                $this->renderView(
                    '@App/email/feedback.html.twig',
                    [
                        'sex' => $sex,
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $phone,
                        'callback_time' => $callbackTime,
                        'text' => $text
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->redirectToRoute('app_index');*/
    }
}
