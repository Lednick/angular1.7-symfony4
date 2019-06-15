<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\TodoList;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Brand controller.
 *
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    /**
     * Lists all Todos.
     * @FOSRest\Get("/lists")
     *
     * @return View
     */
    public function getListsAction(Request $request)
    {
        $searchQuery =  $request->query->get('search', null);

        /** @var EntityRepository $repository */
        $repository = $this->getDoctrine()->getRepository(TodoList::class);

        $todos = [];

        if ($searchQuery === null || $searchQuery === '') {
            $todos = $repository->findall();
        } else {
            $qb = $repository->createQueryBuilder('list');

            $qb
                ->leftJoin('list.todos', 'todos')
                ->where('lower(list.name) LIKE lower(:search_query)')
                ->orWhere('lower(todos.name) LIKE lower(:search_query)')
                ->setParameter('search_query', '%'.$searchQuery.'%');

            $todos = $qb->getQuery()->getResult();
        }


        return View::create($todos, Response::HTTP_OK , []);
    }

    /**
     * Create Todo.
     * @FOSRest\Post("/list/{id}/todo")
     *
     * @return View
     */

    public function createTodoAction(TodoList $todoList, Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $todo = new Todo();
        $todo->setList($todoList);
        $todo->setName($content['name']);

        $todoList->addTodo($todo);

        $em = $this->getDoctrine()->getManager();
        $em->persist($todo);
        $em->persist($todoList);
        $em->flush();

        return View::create($todo, Response::HTTP_CREATED , []);

    }

    /**
     * Remove Todo.
     * @FOSRest\Delete("/list/{id}/todo/{todoId}")
     *
     * @param TodoList $todoList
     * @param string $todoId
     * @param Request $request
     * @return View
     */

    public function deleteTodoAction(TodoList $todoList, string $todoId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $em->getRepository(Todo::class)->createQueryBuilder('todo');

        $qb
            ->where('todo.id = :id')
            ->andWhere('todo.list = :list')
            ->setParameter('id', $todoId)
            ->setParameter('list', $todoList);

        $todo = $qb->getQuery()->getResult();

        if (count($todo) > 0) {
            $em->remove($todo[0]);

            $em->flush();
        }

        return View::create($todo, Response::HTTP_CREATED , []);

    }

    /**
     * Todo by id.
     * @FOSRest\Get("/list/{id}")
     *
     * @return View
     */
    public function getListAction(TodoList $todoList)
    {
        return View::create($todoList, Response::HTTP_OK , []);
    }

    /**
     * Create Todo.
     * @FOSRest\Post("/list")
     *
     * @return View
     */

    public function createListAction(Request $request)
    {
        $content = json_decode($request->getContent(), true);

        $todoList = new TodoList();
        $todoList->setName($content['name']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($todoList);
        $em->flush();

        return View::create($todoList, Response::HTTP_CREATED , []);

    }
}