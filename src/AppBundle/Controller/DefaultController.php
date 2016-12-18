<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

use AppBundle\Entity\Games;

/**
 * @Route("/games")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/dbcreate")
     */
    public function createAction()
    {
	$games = new Games();
	$games->setName('Tetris');
	$games->setPrice(19.99);
	$games->setDescription('First AAA game');

	$em = $this->getDoctrine()->getManager();

	$em->persist($games);
	$em->flush();

	return new Response('Saved new product with id: ' .$games->getId());
    }


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
      * @Route("/{gameId}", name="games_show")
      */
    public function showAction($gameId)
    {
	$games = $this->getDoctrine()
		->getRepository('AppBundle:Games')
		->find($gameId);

	if (!$games) {
	    throw $this->createNotFoundException(
		'No game found for id'.$gameId
	);
	}

	$form = $this->createFormBuilder($games)
		->add('name', TextType::class)
		->add('description', TextType::class)
		->add('price', MoneyType::class)
		->getForm();

	return $this->render('default/new.html.twig', array(
		'form' => $form->createView(),
		));
    }


	/**
	 * @Route("/update/{gameId}")
	 */
    public function updateAction($gameId)
    {
	$em = $this->getDoctrine()->getManager();
	$games = $em->getRepository('AppBundle:Games')->find($gameId);

	if(!$games) {
	   throw $this->createNotFoundException(
		'No game found for id ' .$gameId
		);
	}

	$games->setName('Doom');
	$games->setPrice(49.00);
	$em->flush();

	return $this->redirectToRoute('showgamebyid');
    }

}
