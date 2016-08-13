<?php

namespace AppBundle\Controller;

use AppBundle\Helper\Errors;
use AppBundle\Repository\ExportBagRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends BaseController
{
    /**
     * Redirects to www.munin-for-android.com
     * @Route("/")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        return $this->redirect('https://www.munin-for-android.com');
    }

    /**
     * @Route("/importExport.php")
     */
    public function compatAction(Request $request)
    {
        if ($request->query->has('import')) {
            return $this->forward('AppBundle:Default:import', [
                'request' => $request
            ]);
        }
        elseif ($request->query->has('export')) {
            return $this->forward('AppBundle:Default:export', [
                'request' => $request
            ]);
        }
        else
            return self::dieOnError(Errors::Bad_Request);
    }

    /**
     * Device1 to server
     * @Route("/export")
     * @Method({"POST"})
     */
    public function exportAction(Request $request)
    {
        $post = $request->request;
        /** @var ExportBagRepository $exportBagRepo */
        $exportBagRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:ExportBag');

        if (!$post->has('dataString'))
            return self::dieOnError(Errors::Missing_POST_Data);

        // Remove expired rows
        $exportBagRepo->cleanDb();

        // Insert new one
        $bag = $exportBagRepo->insertExportBag(
            $post->get('version', 0),
            $post->get('dataString'),
            $post->get('dataType', 'masters')
        );

        if (!$bag)
            return self::dieOnError(Errors::SQL_Insert_Fail);

        return self::liveOnSuccess_export($bag->getPassword());
    }

    /**
     * Server to device2
     * @Route("/import")
     * @Method({"POST"})
     */
    public function importAction(Request $request)
    {
        $post = $request->request;
        /** @var ExportBagRepository $exportBagRepo */
        $exportBagRepo = $this->getDoctrine()->getManager()->getRepository('AppBundle:ExportBag');

        if (!$post->has('pswd'))
            return self::dieOnError(Errors::Missing_POST_Data);

        $bag = $exportBagRepo->findExportBag(
            $post->get('pswd'),
            $post->get('dataType', 'masters')
        );

        if (!$bag)
            self::dieOnError(Errors::SQL_Select_Fail);

        // Delete bag
        $exportBagRepo->destroy($bag);

        // Decode and return JSON string
        $json = json_decode('[' . $bag->getDataString() . ']', true);
        return self::liveOnSuccess_import($json);
    }
}
