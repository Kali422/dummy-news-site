<?php

namespace DummyNewsSite\Controller;

use DummyNewsSite\Repository\NewsRepository;
use Throwable;

class NewsController extends AbstractController
{
    private NewsRepository $newsRepository;

    public function __construct(string $templatesLocalization, NewsRepository $newsRepository)
    {
        parent::__construct($templatesLocalization);
        $this->newsRepository = $newsRepository;
    }

    public function list(): string
    {
        try {
            $user = $this->verifyLogin();

            $news = $this->newsRepository->getUserNews($user->getId());

        } catch (Throwable) {
            $_SESSION['newsError'] = 'Something went wrong, can\'t get any news, try again';
        }
        return $this->twig->render('news.html.twig', ['news' => $news, 'messages' => $this->getMessages()]);
    }

    private function getMessages(): array
    {
        $newsSuccess = $_SESSION['newsSuccess'];
        unset($_SESSION['newsSuccess']);

        $newsError = $_SESSION['newsError'];
        unset($_SESSION['newsError']);

        return ['success' => $newsSuccess, 'error' => $newsError];
    }

    public function create(): void
    {
        try {
            $user = $this->verifyLogin();

            $this->newsRepository->create(
                $user->getId(),
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['description'])
            );
            $_SESSION['newsSuccess'] = "Created news";
        } catch (Throwable) {
            $_SESSION['newsError'] = "Something went wrong, try again";
        }

        header('Location: /news');
        die();
    }

    public function edit(): void
    {
        try {
            $this->verifyLogin();

            $this->newsRepository->edit(
                htmlspecialchars($_POST['newsId']),
                htmlspecialchars($_POST['title']),
                htmlspecialchars($_POST['description'])
            );

            $_SESSION['newsSuccess'] = "Edited news";
        } catch (Throwable) {
            $_SESSION['newsError'] = "Something went wrong, try again";
        }

        header('Location: /news');
        die();
    }

    public function delete(): void
    {
        try {
            $this->verifyLogin();

            $this->newsRepository->delete(htmlspecialchars($_POST['newsId']));

            $_SESSION['newsSuccess'] = "Deleted news";
        } catch (Throwable) {
            $_SESSION['newsError'] = "Something went wrong, try again";
        }

        header('Location: /news');
        die();
    }

}