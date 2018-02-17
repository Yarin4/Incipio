<?php

namespace N7consulting\PrivacyBundle\Controller;

use Mgate\PersonneBundle\Entity\Personne;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class PrivacyController extends Controller
{

    /** GDPR actions */
    public const ACCESS = 'access';
    public const DELETE = 'delete';
    public const MODIFY = 'modifiy';
    public const EXPORT = 'export';

    /**
     * @Security("has_role('ROLE_RGPD')")
     * @Route("/", name="privacy_homepage")
     */
    public function indexAction()
    {
        $personnes = $this->getDoctrine()->getManager()
            ->getRepository('MgatePersonneBundle:Personne')
            ->getAllPersonne(true);
        $firms = $this->getDoctrine()->getManager()->getRepository('MgatePersonneBundle:Prospect')
            ->findAll();

        return $this->render('N7consultingPrivacyBundle:Privacy:index.html.twig', [
            'firms' => $firms,
            'personnes' => $personnes,
        ]);
    }

    /**
     * Entrypoint for the four actions of the GDPR.
     *
     * @Security("has_role('ROLE_RGPD')")
     * @Route("/action/{id}", name="privacy_action", methods={"POST"})
     *
     * @param Request  $request
     * @param Personne $personne
     *
     * @return RedirectResponse
     */
    public function actionAction(Request $request, Personne $personne)
    {
        if (!$request->request->has('token') ||
            $this->isCsrfTokenValid($request->request->get('token'), 'rgpd')
        ) {
            $this->addFlash('danger', 'Token invalide');

            return $this->redirectToRoute('privacy_homepage');
        }

        if (!$request->request->has('action')) {
            $this->addFlash('danger', 'Formulaire invalide');

            return $this->redirectToRoute('privacy_homepage');
        }

        $action = $request->request->get('action');

        if (self::ACCESS == $action) {
            return $this->access($personne);
        }

        if (self::DELETE == $action) {
            return $this->delete($personne);
        }

        if (self::MODIFY == $action) {
            return $this->modify($personne);
        }

        if (self::EXPORT == $action) {
            return $this->export($personne);
        }

        $this->addFlash('danger','Invalid action');
        return $this->redirectToRoute('privacy_homepage');

    }

    private function access(Personne $personne)
    {
        return $this->render('');
    }

    private function delete(Personne $personne)
    {
        $em = $this->getDoctrine()->getManager();
        $personne->anonymize();
        $em->flush();

        try {
            $em->remove($personne);
            $em->flush();
            $this->addFlash('success', 'Personne supprimée');
        } catch (\Exception $e) {
            $this->addFlash('warning','La personne a signée des documents et ne
            peux être supprimée sans nuire à l\'intégrité des données. Le maximum de ses données personnelles
            ont été supprimées et le reste (nom, prénom) a été anonymisé');
        }

        return $this->redirectToRoute('privacy_homepage');
    }

    private function modify(Personne $personne)
    {
        return $this->render('');
    }

    private function export(Personne $personne)
    {
        return $this->render('');
    }
}
