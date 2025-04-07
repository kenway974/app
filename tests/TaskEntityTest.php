<?php 

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\Stat;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskTest extends KernelTestCase
{
    protected function assertHasError(Task $task, int $number = 0)
    {
        self::bootKernel();
        $errors = static::getContainer()->get(ValidatorInterface::class)->validate($task);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "\n❌ " . $error->getPropertyPath() . ': ' . $error->getMessage();
            }
        }
        $this->assertCount($number, $errors);
    }

    // RENVOIE UN USER
    public function getUser(): User
    {
        $user = (new User())
                ->setEmail('kpignolet18@gmail.com')
                ->setRoles(['ROLE_USER'])
                ->setPassword('Tikensabun')
                ->setUsername('kenway');

        return $user;
    }

    // RENVOIE UNE STAT
    public function getStat1(): Stat
    {
        $stat1 = (new Stat())
            ->setTitle('Force')
            ->setScore(0)
            ->setDescription('systèeme nerveux tout ca tout ca');

        return $stat1;
    }

    // RENVOIE UNE AUTRE STAT
    public function getStat2(): Stat
    {
        $stat2 = (new Stat())
            ->setTitle('App Symfony')
            ->setScore(0)
            ->setDescription('avancée app');

        return $stat2;
    }

    // CREE UNE TACHE
    public function createValidTask(): Task
    {
        $task = new Task();
        $task->setTitle('Faire le rapport')
             ->setSousTaches('Compléter le rapport de mission.')
             ->setDatebutoir(new \DateTime('+2 days'))
             ->setImportance(3)
             ->setChecked(false);

        $user = $this->getUser();
        $stat1 = $this->getStat1();
        $stat2 = $this->getStat2();

        $task->setUser($user);
        $task->addStat($stat1);
        $task->addStat($stat2);

        return $task;
    }

    // TESTE UNE TACHE VALIDE
    public function testValidTask() 
    {
        $task = $this->createValidTask();
        $this->assertHasError($task, 0);
    }

    // TESTE UNE TACHE INVALIDE
    public function testInvalidTask() 
    {
        $task = $this->createValidTask()->setTitle('');
        $this->assertHasError($task, 2);
    }

}
