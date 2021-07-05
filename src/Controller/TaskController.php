<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaskController extends AbstractController
{
    public function index() : Response 
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $pendingEntities = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->where('t.status = :pending')
            ->setParameter('pending', 0)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
        
        $doneEntities = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->where('t.status = :done')
            ->setParameter('done', 1)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
        
        $rejectedEntities = $entityManager->createQueryBuilder()
            ->select('t')
            ->from(Task::class, 't')
            ->where('t.status = :rejected')
            ->setParameter('rejected', 2)
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('Task/index.html.twig', [
            'pendingEntities' => $pendingEntities,
            'doneEntities' => $doneEntities,
            'rejectedEntities' => $rejectedEntities
        ]);
    }
    
    public function new(Request $request) 
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entity = new Task();

        $form = $this->createForm(TaskType::class, $entity, array(
            'action' => $this->generateUrl('task_new'),
            'method' => 'POST'
        ));
        
        $form->add('submit', SubmitType::class, ['label' => 'Create task']);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($entity);
            $entityManager->flush();

            $this->get('session')->getFlashBag()->add('success', 'Success');

            if ($form->get('submit')->isClicked()) {
                return $this->redirect($this->generateUrl('task_edit', array('id' => $entity->getId())));
            }
        }
        
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->get('session')->getFlashBag()->add('error', 'Error');
        }
        
        return $this->render('Task/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function edit(Request $request, Task $entity) : ?Response 
    {
        $form = $this->createForm(TaskType::class, $entity);
        $form->add('submit', SubmitType::class, ['label' => 'Save changes']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($entity);
            $entityManager->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Success');
            
            if ($form->get('submit')->isClicked()) {
                return $this->redirectToRoute('task_edit', ['id' => $entity->getId()]);
            }
        }
        
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Error');
        }
        
        return $this->render('Task/edit.html.twig', [
            'entity' => $entity,
            'form' => $form->createView()
        ]);
    }
    
    public function show(Task $entity) : ?Response 
    {   
        return $this->render('Task/show.html.twig', [
            'entity' => $entity
        ]);
    }
    
    public function changeStatus(Request $request, Task $entity)
    {
        $data = $request->getContent();
        
        if (!empty($data)) {
            $params = json_decode($data, true);
        }
        
        $status = $params['status'];
        
        $entityManager = $this->getDoctrine()->getManager();
        
        $entity->setStatus($status);
        $entityManager->persist($entity);
        $entityManager->flush();
        
        return new JsonResponse('OK');
    }
}
