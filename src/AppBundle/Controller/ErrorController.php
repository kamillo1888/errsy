<?php
/**
 * Created by PhpStorm.
 * Date: 01.09.2016
 *
 * @author Kamil Bednarek <kb@protonmail.ch>
 */
namespace AppBundle\Controller;

use MongoDB\BSON\ObjectID;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends Controller
{
    private function getDataProvider()
    {
        return $this->get('mongodb_provider')->getCollection('error');
    }

    /**
     * @Route("/fix_like_this/{id}", name="error_fix_like_this")
     */
    public function fixLikeThisAction($id, Request $request)
    {
        $error = $this->getDataProvider()->findOne(['_id' => new ObjectID($id)]);
        if (null === $error) {
            throw new NotFoundHttpException('Error with identifier not found');
        }

        $this->getDataProvider()->deleteMany([
            'message' => $error->message,
            'app' => $error->app,
            'env' => $error->env
        ]);

        $referer = $request->headers->get('referer');
        return new RedirectResponse($referer);
    }
}