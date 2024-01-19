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

            //si c'est un signin
            if ($user->getPlainPassword() == 'null') {
                $user->setPassword(
                    $this->hash->hashPassword(
                        $user,
                        $user->getPassword()
                    )
                );
                return;
            }

            //login hash
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