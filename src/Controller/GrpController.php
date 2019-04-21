<?php

namespace App\Controller;
use App\Entity\Grp;
use App\Entity\User;

use App\Repository\GrpRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;



class GrpController extends ApiController{
    /**
     * @Route("/groups/", methods="GET")
     */
    public function listGroups(GrpRepository $GrpRepository){        
        $groups = $GrpRepository->transformAll();
        return $this->sendResponse($groups);
    }

    /**
     * @Route("group/create/", methods="POST")
     */
    public function createGroup(Request $request, GrpRepository $GrpRepository, EntityManagerInterface $em){
        $request = $this->transformJsonBody($request);

        if (! $request) {
            return $this->sendValidationError('Please provide a valid request!');
        }
        $group = new Grp;
        $group->setName($request->get('name'));
        $em->persist($group);
        $em->flush();

        return $this->sendSuccessMessage($GrpRepository->transform($group), $code=201);
    }

    /**
     * @Route("/group/adduser/", methods="POST")
     */
    public function assignGroupToUser(Request $request, GrpRepository $GrpRepository, EntityManagerInterface $em){
        $request = $this->transformJsonBody($request);
        if (! $request) {
            return $this->sendValidationError('Please provide a valid request!');
        }

        $group = $this->getDoctrine()->getRepository(Grp::class)->find($request->get('group_id'));
        $user = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));

        if (! $user){
            return $this->sendNotFoundError('User Id do not exist');
        }
        if (! $group){
            return $this->sendNotFoundError('Group Id do not exist');
        }
        $group->addUser($user);
        $em->flush();

        return $this->sendSuccessMessage($GrpRepository->transform($group));
    }

    /**
     * @Route("/group/delete/{id}", methods="DELETE")
     */
    public function delteEmptyroup(Request $request, EntityManagerInterface $em, $id){
        $group = $this->getDoctrine()->getRepository(Grp::class)->find($id);
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if (! $group){
            return $this->sendNotFoundError('Group you  want to delete, does not exist');
        }
        $associate_users = $group->getUsers();
        if ($associate_users){
            return $this->sendNotFoundError('The Group you  want to delete, contains one or more  user(s)');
        }
        $em->remove($group);
        $em->flush();

        return $this->sendSuccessMessage($associate_users);
    }

}