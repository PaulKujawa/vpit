<?php

namespace Barra\BackBundle\Controller;

use Barra\BackBundle\Form\Type\ManufacturerType;
use Barra\BackBundle\Entity\Manufacturer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManufacturerController
 * @author Paul Kujawa <p.kujawa@gmx.net>
 * @package Barra\BackBundle\Controller
 */
class ManufacturerController extends BasicController
{
    /**
     * @param int $pageIndex
     * @return Response
     */
    public function indexAction($pageIndex)
    {
        $pages = $this->getPaginationPages('Manufacturer', 10);
        $form  = $this->createForm(new ManufacturerType(), new Manufacturer());

        return $this->render('BarraBackBundle:Manufacturer:manufacturers.html.twig', [
            'pageIndex' => $pageIndex,
            'pages'     => $pages,
            'form'      => $form->createView(),
        ]);
    }
}
