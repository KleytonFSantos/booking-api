<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/form')]
class TesteController extends AbstractController
{

    #[Route(path: '/teste', name: 'teste-index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $request->getSession()->set('teste', 'testando');
        return $this->render('form.html.twig');
    }

    #[Route(path: '/teste', name: 'teste-send', methods: 'POST')]
    public function sendForm(Request $request): Response
    {
        $data = $request->request->all();
        dd($data, $request->getSession()->get('teste'));
    }

}