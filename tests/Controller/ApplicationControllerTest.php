<?php

namespace App\Tests\Controller;

use App\Repository\AccountRepository;
use App\Repository\FirmRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApplicationControllerTest extends WebTestCase
{
    public function testLoadingPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8001/');
        $client->followRedirects();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label.required', 'Otestujte společnost');
    }

    public function testFillInBadICO(): void
    {
        $client= static::createClient();
        $crawler = $client->request("GET","http://127.0.0.1:8001/");
        $buttonCrawlerNode = $crawler->selectButton('firm[submit]');
        $form = $buttonCrawlerNode->form();
        $form['firm[ico]'] = '12345678';
        $client->submit($form);


        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('label.required', 'Otestujte společnost');
        $this->assertSelectorTextContains('div.not_found', 'Společnost neexistuje');

    }

    public function testFillInGoodICO(): void
    {
        $client= static::createClient();
        $crawler = $client->request("GET","http://127.0.0.1:8001/");
        $buttonCrawlerNode = $crawler->selectButton('firm[submit]');
        $form = $buttonCrawlerNode->form();
        $form['firm[ico]'] = '03231691';
        $client->submit($form);
       $crawler= $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('div > p > span ', 'Alfa Level s.r.o.');
        $i=0;
        $button =$crawler->selectButton("test[submit]");
        $form= $button->form();
        $form['test[test_jednatelu][0]']->tick();
        ++$i;
        $form['test[test_jednatelu][1]']->tick();
        ++$i;
        $client->submit($form);
        $crawler=$client->followRedirect();
        $this->assertSelectorTextContains('div > h1',"Alfa Level s.r.o.");
        $this->assertCount((9+$i),$crawler->filter("table > tbody > tr"));
        $str=$crawler->getUri();
        $m=array();
        preg_match("/\/result\/(\d+)/",$crawler->getUri(),$m);
    }
    public function testVisitingWhileLoggedIn()
    {
        $client = static::createClient();
        $userRepository = static::$container->get(AccountRepository::class);
        $firmRepository = static::$container->get(FirmRepository::class);
        // retrieve the test user
        $testUser = $userRepository->findOneBy(["username"=>'belyikir']);
        $array = $firmRepository->findBy(["account"=>$testUser->getId()]);

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $crawler=$client->request('GET', '/account/'.$testUser->getId()."/history");
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p > span', $testUser->getUsername());
        $this->assertCount(count($array), $crawler->filter('tbody > tr'));
    }


}
