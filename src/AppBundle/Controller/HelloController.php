<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
	/**
	 * @Route("/hello/{name}", name="hello")
	 */
	public function indexAction($name)
	{
		return new Response('<html><body>Hello ' .$name.'!</body></html>');
	}
}
?>
