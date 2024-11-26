<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use App\Form\ProfileImageType;
use App\Form\UserProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $userProfile = $user->getUserProfile() ?? new UserProfile();
        $form = $this->createForm(UserProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile = $form->getData();
            $user->setUserProfile($userProfile);

            $manager->persist($profile);
            $manager->flush();

            flash()->success('Profile Saved Successfully');

            return $this->redirectToRoute('app_blog');
        }

        return $this->render(
            'profile/profile.html.twig',
            [
                'form' => $form->createView(),
                'profile' => $userProfile
            ]
        );
    }

    #[Route('/profile/profile-image', name: 'app_profile_image')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function profileImage(Request $request, SluggerInterface $slugger, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(ProfileImageType::class);
        /** @var User $user */
        $user = $this->getUser();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profileImageFile = $form->get('profileImage')->getData();

            if ($profileImageFile) {
                $originalFileName = pathinfo(
                    $profileImageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );

                $safeFileName = $slugger->slug($originalFileName);
                $newFileName = $safeFileName . '-' . uniqid() . '.' . $profileImageFile->guessExtension();

                try {
                    $profileImageFile->move($this->getParameter('profile_directory'), $newFileName);
                } catch (FileException $e) {
                    //catch logic 
                }

                $profile = $user->getUserProfile() ?? new UserProfile();
                $profile->setImage($newFileName);

                $manager->persist($user, true);
                $manager->flush();

                flash()->success('Profile Image Uploaded Successfully');

                return $this->redirectToRoute('app_profile');
            }
        }

        return $this->render(
            'profile/profile_image.html.twig',
            [
                'form' => $form->createView(),
                
            ]
        );
    }
}
