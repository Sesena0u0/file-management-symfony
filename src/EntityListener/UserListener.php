<?php
    
    namespace App\EntityListener;
    use App\Entity\User;
    use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

    class UserListener 
    {

        private $hash;
        public function __construct(UserPasswordHasherInterface $hash) {
            $this->hash = $hash;
        }
        function prePersist(User $user) {
            $this->hash($user);
        }

        function preUpdate(User $user) {
            $this->hash($user);
        }

        function hash(User $user) {
            if ($user->getPlainPassword() === null) {
                return;
            }

            $user->setPassword(
                $this->hash->hashPassword(
                    $user,
                    $user->getPlainPassword()
                )
            );

            //$user->setPlainPassword(null);

        }

    }
    

?>