<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Cooking;
use AppBundle\Form\CookingType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("is_authenticated()")
 */
class CookingController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @param int $recipeId
     *
     * @return View
     */
    public function newAction(int $recipeId): View
    {
        return $this->view($this->createForm(CookingType::class));
    }

    /**
     * @param int $recipeId
     *
     * @return View
     */
    public function cgetAction(int $recipeId): View
    {
        $cookings = $this->get('app.repository.cooking')->getCookings($recipeId);

        return $this->view($cookings);
    }

    /**
     * @param int $recipeId
     * @param int $id
     *
     * @return View
     */
    public function getAction(int $recipeId, int $id): View
    {
        $cooking = $this->get('app.repository.cooking')->getCooking($recipeId, $id);

        return null === $cooking
            ? $this->view(null, Response::HTTP_NOT_FOUND)
            : $this->view($cooking);
    }

    /**
     * @param Request $request
     * @param int $recipeId
     *
     * @return View
     */
    public function postAction(Request $request, int $recipeId): View
    {
        $recipe = $this->get('app.repository.recipe')->getRecipe($recipeId);
        if (null === $recipe) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        $position = $this->get('app.repository.cooking')->getPosition($recipeId);
        $cooking = new Cooking($recipeId, $position);
        $form = $this->createForm(CookingType::class, $cooking);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }

        $cooking = $this->get('app.repository.cooking')->addCooking($cooking);

        return View::createRouteRedirect('api_get_recipe_cooking', [
            'recipeId' => $recipeId,
            'id' => $cooking->id,
        ]);
    }

    /**
     * @param Request $request
     * @param int $recipeId
     * @param int $id
     *
     * @return View
     */
    public function putAction(Request $request, int $recipeId, $id): View
    {
        $cooking = $this->get('app.repository.cooking')->getCooking($recipeId, $id);

        if (null === $cooking) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(CookingType::class, $cooking, ['method' => $request->getMethod()]);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->view($form, Response::HTTP_BAD_REQUEST);
        }

        $this->get('app.repository.cooking')->setCooking($cooking);

        return View::createRouteRedirect(
            'api_get_recipe_cooking',
            ['recipeId' => $recipeId, 'id' => $id],
            Response::HTTP_NO_CONTENT
        );
    }

    /**
     * @param int $recipeId
     * @param int $id
     *
     * @return View
     */
    public function deleteAction(int $recipeId, int $id): View
    {
        $cooking = $this->get('app.repository.cooking')->getCooking($recipeId, $id);

        if (null === $cooking) {
            return $this->view(null, Response::HTTP_NOT_FOUND);
        }

        try {
            $this->get('app.repository.cooking')->deleteCooking($cooking);
        } catch (ForeignKeyConstraintViolationException $ex) {
            return $this->view(null, Response::HTTP_CONFLICT);
        }

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}
