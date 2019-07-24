<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Form\SigninType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserconnexionController extends AbstractController
{
    /**
     * @Route("/userconnexion", name="userconnexion")
     */
    public function userconnexion(AuthenticationUtils $authenticationUtils){
/*        echo '<pre>';
        var_dump($authenticationUtils);
        echo '</pre>';*/
        $lastusername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('userconnexion/signin.html.twig',[
            'last_username' => $lastusername,
            'error' => $error,
        ]);
    }


    /**
     * @Route("/inscription", name="registration")
     */
    public function registration(ObjectManager $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$user->getId()) {
                $user->setDatesub(new \DateTime());
            }
            $user->setGrade(1);
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('userconnexion');
        }
        return $this->render('userconnexion/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
