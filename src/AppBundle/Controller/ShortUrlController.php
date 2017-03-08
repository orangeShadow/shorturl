<?php

namespace AppBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\ShortUrl;

class ShortUrlController extends Controller
{

    /**
     * Список url
     * @Route("/shorturl", name="urlList")
     * @Method("GET")
     */
    public function getUrlListAction()
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:ShortUrl');
        $all = $repository->findAll();

        return new JsonResponse($all);
    }

    /**
     * Создать новый короткий url
     * @Route("/shorturl")
     * @Method("POST")
     */
    public function postUrlAction(Request $request)
    {

        try {
            $post = json_decode($request->getContent());

            if(!empty($post->origin_url)) {
                $originUlr  = $post->origin_url;
            } else {
                $originUlr = $request->get('origin_url',null);
            }

            if(!empty($post->slug)) {
                $slug  = $post->slug;
            } else {
                $slug = $request->get('slug',null);
            }

            $shortUrl = new ShortUrl();

            $shortUrl->setOriginUrl($originUlr);

            $shortUrl->setSlug($slug);

            $validator = $this->get('validator');
            $errors = $validator->validate($shortUrl);

            if (count($errors) > 0) {
                $response = [];
                foreach ($errors as $error) {
                    $index = $error->getPropertyPath();
                    if ( !isset($response[$index]) ) {
                        $response[$error->getPropertyPath()] = $error->getMessage();
                    }
                }

                return new JsonResponse($response,422);
            }



            $em = $this->getDoctrine()->getManager();

            $em->persist($shortUrl);

            $em->flush();

        } catch (\Exception $e) {
            $this->get('logger')->error('Ошибка при создании короткого url', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine()
            ]);

            return new JsonResponse(['error' => $e->getCode(), 'message' => "Error. Url doesn't create!"], 500);
        }

        return new JsonResponse($shortUrl);
    }

    /**
     * Удалить url по id
     * @Route("/shorturl/{id}")
     * @Method("DELETE")
     */
    public function deleteUrlAction($id)
    {
        $shortUrl = $this->getDoctrine()
            ->getRepository('AppBundle:ShortUrl')
            ->find($id);

        if (!$shortUrl) {
            return new JsonResponse(['error' => 404, 'message' => 'No url found for id ' . $id], 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($shortUrl);
        $em->flush();

        return new JsonResponse(['message' => 'Url was delete']);
    }


    /**
     * @Route("/{slug}" , name = "shortLink")
     */
    public function getUrlBySlug($slug)
    {
        $shortUrl = $this->getDoctrine()
            ->getRepository('AppBundle:ShortUrl')
            ->findOneBySlug($slug);

        if (!$shortUrl) {
            return new Response('Not Found Url',404);
        }

        return $this->redirect($shortUrl->getOriginUrl(), 301);
    }
}
