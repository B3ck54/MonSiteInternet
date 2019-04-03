<?php

namespace App\Tests\Faker;

use App\Entity\Livres;
use App\Entity\User;
use App\Security\Voter\LivreVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LivreVoterTest extends TestCase
{

    /**
     * @dataProvider  voterProvider
     */
   public function testLivreVoter($user, $expected)
   {
       $voter=new LivreVoter();
       $livre = new Livres();



       $livre->setUser($user);

       $token = new AnonymousToken('secret','anonymous');

       if($user)
       {
       $token = new UsernamePasswordToken($user, 'credentials','memory');
        $livre->setUser($user);
       }

       $this->assertSame($expected, $voter->vote($token, $livre, ['EDIT']));
   }

    public function voterProvider()
    {
        $userOne = $this->createMock(User::class);
        $userOne->method('getId')->willReturn(77);

        return [
            [$userOne, 1],
            [null, -1]


        ];
    }
}