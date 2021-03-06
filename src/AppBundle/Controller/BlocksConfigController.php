<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blocks;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/blocks")
 */
class BlocksConfigController extends Controller
{
  /**
   * @Route("/{block}/edit", name="blocks_edit")
   * @ParamConverter("block", options={"mapping": {"block": "slug"}})
   */
   public function editAction(Blocks $block, Request $request)
   {
     $blocksManager = $this->get('blocks.manager');

     $blocksForm = $this->createForm('blocks', $block)->handleRequest($request);

     if($blocksForm->isValid()){
       $blocksManager->save($block);

       return $this->redirect($this->generateUrl('blocks_edit', ["block" => $block->getSlug()]));
     }

     return $this->render('blocks/config/edit.html.twig', array(
       'blocks_form' => $blocksForm->createView(),
          'block' => $block
        ));
    }

    /**
     * @Route("/{block}/categories", name="blocks_categories")
     * @ParamConverter("block", options={"mapping": {"block": "slug"}})
     */
    public function categoriesAction(Blocks $block, Request $request)
    {
      $blocksManager = $this->get('blocks.manager');

      $categoriesOriginal = $blocksManager->collectionsAdd($block->getCategories());

      $blockCategoriesForm = $this->createForm('blocks_categories', $block)->handleRequest($request);

        if($blockCategoriesForm->isValid()){

            $blocksManager->collectionsSave($block->getCategories(), $categoriesOriginal);

            $blocksManager->save($block);

            return $this->redirect($this->generateUrl('blocks_categories', array("block" => $block->getSlug())));
        }

      return $this->render('blocks/config/categories.html.twig', array(
        'blocks_categories_form' => $blockCategoriesForm->createView(),
        'block' => $block
      ));
    }

  /**
   * @Route("/{block}/users", name="blocks_users")
   * @ParamConverter("block", options={"mapping": {"block": "slug"}})
   */
  public function usersAction(Blocks $block, Request $request)
  {
    $blocksManager = $this->get('blocks.manager');

    $usersOriginal = $blocksManager->collectionsAdd($block->getBlocksUsers());

    $usersManager = $this->get('users.manager');

    $blockUsers = $usersManager->getBlockUsers($this->getUser());

    $blocksForm = $this->createForm('block_users', $block, ['blockUsers' => $blockUsers])->handleRequest($request);

    if($blocksForm->isValid()){

      $blocksManager->collectionsSave($block->getBlocksUsers(), $usersOriginal);

      $blocksManager->save($block);

      return $this->redirect($this->generateUrl('blocks_users', array("block" => $block->getSlug())));
    }

    return $this->render('blocks/config/users.html.twig', array(
      'block_users_form' => $blocksForm->createView(),
      'block' => $block
    ));
  }
}
