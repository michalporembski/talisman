<?php

namespace AppBundle\Controller;

use AppBundle\Fixtures\Characters;
use AppBundle\Fixtures\RuneGates;
use AppBundle\Fixtures\Supplement;
use AppBundle\Form\CardGenerateType;
use AppBundle\Services\Generator;
use AppBundle\Services\RandomHero;
use CardMakerBundle\Entity\Dto\GenerateCard;
use CardMakerBundle\Entity\Layer;
use CardMakerBundle\Exceptions\GeneratorException;
use CardMakerBundle\Services\SheetPrinter;
use CardMakerBundle\Services\SheetPrinter2;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $card = null;
        $command = new GenerateCard();
        $form = $this->createForm(CardGenerateType::class, $command);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $cardGenerator = $this->get('cardmaker.handler.card_generate');
            try {
                $command->setSave(true);
                $card = $cardGenerator->handle($command);
            } catch (GeneratorException $e) {
                $message = $this->get('translator')->trans($e->getMessage());
                $this->addFlash('error', $message);
            }
        }

        return $this->render(
            'AppBundle:Default:create_card.form.html.twig',
            [
                'form' => $form->createView(),
                'card' => $card
            ]
        );
    }

    /**
     * @Route("/karaktery", name="karaktery")
     */
    public function karakteryAction(Request $request)
    {
        $this->generateSheet(Characters::CARDS);
    }

    /**
     * @Route("/rune_gates", name="rune_gates")
     */
    public function runeGatesAction(Request $request)
    {
        $this->generateSheet(RuneGates::CARDS);
    }

    /**
     * @Route("/supplement", name="supplement")
     */
    public function supplementAction(Request $request)
    {
        $this->generateSheet(Supplement::CARDS);
    }

    /**
     * @Route("/hero", name="hero")
     */
    public function heroAction(Request $request)
    {
        $randomHero = new RandomHero();
        $randomHero->generateHero();
    }

    /**
     * @Route("/generate", name="generator")
     */
    public function generatorAction(Request $request)
    {
        $service = new Generator();
        $command = $service->generateCard();
        $cardGenerator = $this->get('cardmaker.handler.card_generate');
        $cardGenerator->handle($command);
    }

    /**
     * @Route("/error", name="error")
     */
    public function errorAction(Request $request)
    {
        echo 'TODO: error page';
        die;
    }

    /**
     * @Route("/print_folder", name="print_folder")
     */
    public function printFolder(Request $request)
    {
        try {
            $sheetPrinter = new SheetPrinter();
            $dir = "./print/";
            $dh = opendir($dir);
            while (($file = readdir($dh)) !== false) {
//                echo "filename:" . $file . "<br>";
                if ($file != '.' && $file != '..') {
                    $sheetPrinter->addFile($dir.$file, Layer::CARDS_BACK[Layer::CARD_DENIZEN]);
                }
            }
            closedir($dh);
            $sheetPrinter->printPDF();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            var_dump($e->getFile() . '::' . $e->getLine());
        }
        die;
    }

    /**
     * HERO
     * @Route("/print_folder2", name="print_folder")
     */
    public function printFolder2(Request $request)
    {
        try {
            $sheetPrinter = new SheetPrinter2();
            $dir = "./print_hero/";
            $dh = opendir($dir);
            while (($file = readdir($dh)) !== false) {
                //                echo "filename:" . $file . "<br>";
                if ($file != '.' && $file != '..') {
                    $sheetPrinter->addFile($dir.$file, Layer::CARDS_BACK[Layer::CARD_DENIZEN]);
                }
            }
            closedir($dh);
            $sheetPrinter->printPDF();
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            var_dump($e->getFile() . '::' . $e->getLine());
        }
        die;
    }

    /**
     * @param array $cards
     */
    private function generateSheet(array $cards)
    {
        $cardGenerator = $this->get('cardmaker.handler.card_generate');
        $command = new GenerateCard();
        $command->setSave(true);

        $sheetPrinter = new SheetPrinter();
        foreach ($cards as $data) {
            $command->setText($data['desc']);
            $command->setTitle($data['name']);
            $command->setStory($data['story'] ?? '');
            $command->setCaption($data['caption'] ?? null);
            // TODO: add support for other caption
            $command->setCaptionType(isset($data['caption']) ? 1 : 0);
            $command->setLevel($data['level'] ?? '');
            $command->setPlaces($data['places'] ?? []);
            $command->setTag($data['tag'] ?? '');
            $command->setLayer($data['card']);
            $img = $cardGenerator->handle($command);
            $sheetPrinter->addFile($img, Layer::CARDS_BACK[$data['card']]);
        }
        $sheetPrinter->printPDF();
        die;
    }
}
