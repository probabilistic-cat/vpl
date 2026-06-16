<?php

declare(strict_types=1);

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedbackController extends AbstractController
{
    public function index(EntityManagerInterface $em): void {
        throw $this->createNotFoundException();
        //$mainPage = $em->getRepository(Entity\MainPage::class)->find(Entity\MainPage::ID);
        //$mail = $mainPage->getMail();
        //
        //$sex = $request->get('sex');
        //$firstName = $request->get('first_name');
        //$lastName = $request->get('last_name');
        //$phone = $request->get('phone');
        //$callbackTime = $request->get('callback_time');
        //$text = $request->get('text');
        //
        //
        //$message = (new \Swift_Message('Anfrage'))
        //    ->setFrom('feedback@vpl-bau.de')
        //    ->setTo($mail)
        //    ->setBody(
        //        $this->renderView(
        //            'email/feedback.html.twig',
        //            [
        //                'sex' => $sex,
        //                'first_name' => $firstName,
        //                'last_name' => $lastName,
        //                'phone' => $phone,
        //                'callback_time' => $callbackTime,
        //                'text' => $text
        //            ]
        //        ),
        //        'text/html'
        //    );
        //
        //$mailer->send($message);
        //
        //return $this->redirectToRoute('app_index');
    }
}
