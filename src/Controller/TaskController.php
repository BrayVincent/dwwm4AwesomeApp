<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{

    /**
     * @Route("/task/listing", name="task")
     */
    public function index(): Response
    {

        // On va chercher avec Doctrine le Repository de nos Task
        $repository = $this->getDoctrine()->getRepository(Task::class);

        // Dans ce repository nous récupérons toutes les données
        $tasks = $repository->findAll();

        // Affichage des données dans le var_dumper
        // var_dump($tasks);
        // die;
        // dd($tasks);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }

    /**
     * @Route("/task/create", name="task_create")
     */
    public function createTask(Request $request)
    {

        $task = new Task;

        $form = $this->createForm(TaskType::class, $task, []);

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
