<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blocks;
use AppBundle\Entity\Records;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/blocks")
 */


class RecordsController extends Controller
{
    /**
     * @Route("/{block}/records", name="records")
     * @ParamConverter("block", options={"mapping": {"block": "slug"}})
     */
    public function listAction($block, Request $request)
    {

      $blocksManager = $this->get("blocks.manager");

      $block = $blocksManager->getRepository()->findOneBySlug($block);

      $recordsManager = $this->get('records.manager');

      $records = $recordsManager->create();

      $recordsForm = $this->createForm('records', $records, ['block' => $block])->handleRequest($request);

      if($recordsForm->isValid() && $recordsForm->isSubmitted()){
        $records->setBlocks($block);
        $recordsManager->save($records);
        return $this->redirect($this->generateUrl('records', array('block' => $block->getSlug())));
      }

      $records = $recordsManager->getRepository()->findBy(array('blocks'=> $block));

      return $this->render('records/list.html.twig', array(
        'block' => $block,
        'records_form' => $recordsForm->createView(),
        'records'     => $block->getRecords()
      ));
    }

    /**
     * @Route("/{block}/records/{record}", name="records_edit")
     * @ParamConverter("block", options={"mapping": {"block": "slug"}})
     * @ParamConverter("record", options={"mapping": {"record": "slug"}})
     */
    public function editAction(Blocks $block, Records $record, Request $request)
    {
      $recordsManager = $this->get('records.manager');

      $records = $recordsManager->getRepository()->findOneBy(array('id' => $record->getid(), 'blocks' => $block));

      $recordsForm = $this->createForm('records', $records, ['block' => $block])->handleRequest($request);

      if($recordsForm->isValid()){

        $records->setBlocks($block);
        $recordsManager->save($records);

        return $this->redirect($this->generateUrl('records_edit', array('block' => $block->getSlug(), 'record' => $record->getSlug())));
      }

      $records = $recordsManager->getRepository()->findOneBy(array('blocks'=> $block));

      return $this->render('records/edit.html.twig', array(
        'block' => $block,
        'records_form' => $recordsForm->createView(),
        'records'     => $block->getRecords()
      ));
    }
}
