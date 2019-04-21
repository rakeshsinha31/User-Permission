<?php

namespace App\Controller;
use App\Entity\User;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends ApiController{

    /**
     * @Route("/users/", methods="GET")
     */
    public function listUsers(UserRepository $UserRepository){        
        $users = $UserRepository->transformAll();
        return $this->sendResponse($users);
    }

    /**
     * @Route("/createuser/", methods="POST")
     */
    public function createUser(Request $request, UserRepository $UserRepository, EntityManagerInterface $em){
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->sendValidationError('Please provide a valid request!');
        }
        // persist the new user
        $user = new User;
        $user->setName($request->get('name'));
        $em->persist($user);
        $em->flush();

        return $this->sendSuccessMessage($UserRepository->transform($user), $code=201);
    }    
    /**
     * @Route("user/delete/{id}", methods="DELETE")
     */
    public function deleteUser(Request $request, EntityManagerInterface $em, $id){
        $request = $this->transformJsonBody($request);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (! $user){
            return $this->sendNotFoundError('The User you want to delete, does not exist');
        }
        $em->remove($user);
        $em->flush();
        return $this->sendSuccessMessage('User deleted successfully');


    }
}

