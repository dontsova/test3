<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use AppBundle\Entity\Games;

/**
 * @Route("/games")
 */
class DefaultController extends Controller
{
     /**
      * @Route("/index", name="games_index")
      */
     public function indexAction()
	{
	  $games = $this->getDoctrine()
		->getRepository('AppBundle:Games')
		->findAll();

 	return $this->render('default/index.html.twig', array(
		'games' => $games,
		));
	}

     /**
      * @Route("/edit/{gameId}", name="game_edit")
      */
    public function editAction(Request $request, $gameId)
    {
	$game = $this->getDoctrine()
		->getRepository('AppBundle:Games')
		->find($gameId);

	if (!$game) {
	    throw $this->createNotFoundException(
		'No game found for id'.$gameId
	);
	}

	$form = $this->createFormBuilder($game)
		->add('name', TextType::class)
		->add('description', TextType::class)
		->add('price', NumberType::class)
        ->add('save', SubmitType::class, array('label' => 'Save Item'))
		->getForm();


    $form->handleRequest($request);

    if($form->isValid() && $form->isSubmitted()) {

       $game = $form->getData();
       $em = $this->getDoctrine()->getManager();
       $em->persist($game);
       $em->flush();

       return $this->redirectToRoute('games_index');
    }

	return $this->render('default/edit.html.twig', array(
		'form' => $form->createView(),
		));
    }

    /**
     * @Route("/add", name="game_add")
     */
    public function addAction(Request $request)
    {
    $game = new Games();
    $game->setImageuri("set later");
    $game->setQuantity(50);

    $form = $this->createFormBuilder($game)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', NumberType::class)
            ->add('add', SubmitType::class, array('label' => 'Add New Item'))
            ->getForm();

    $form->handleRequest($request);

    if($form->isValid() && $form->isSubmitted()) {
       $game = $form->getData();

       $em = $this->getDoctrine()->getManager();
       $em->persist($game);
       $em->flush();

       return $this->redirectToRoute('games_index');
    }

    return $this->render('default/add.html.twig', array(
        'form' => $form->createView(),
        ));
    }



    /**
     * @Route("/remove/{gameId}", name="game_remove")
     */
    public function removeAction(Request $request, $gameId)
    {
    $game = $this->getDoctrine()
          ->getRepository('AppBundle:Games')
          ->find($gameId);

    if(!$game) {
          throw $this->createNotFoundException(
          'No game found for id: '.$gameId
        );
        }

    $form = $this->createFormBuilder($game)
            ->add('remove', SubmitType::class, array('label' => 'Remove Game'))
            ->getForm();

    $form->handleRequest($request);

    if($form->isValid() && $form->isSubmitted()) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($game);
        $em->flush();

        return $this->redirectToRoute('games_index');
    }



    return $this->render('default/remove.html.twig', array(
                'game' => $game,
                'form' => $form->createView(),
                ));
    }

}
