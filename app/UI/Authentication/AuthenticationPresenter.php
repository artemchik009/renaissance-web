<?php

declare(strict_types=1);

namespace App\UI\Authentication;

use Nette;

use App\Presenter as CustomPresenter;

final class AuthenticationPresenter extends CustomPresenter
{
    public function renderLogin()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Home:default');
        }

        if ($this->getHttpRequest()->getMethod() === 'POST')
        {
            try {
                $this->getUser()->login($this->getHttpRequest()->getPost('email'), $this->getHttpRequest()->getPost('password'));
                $this->redirect('Home:default');
            } catch (AuthenticationException $e) {
                $this->flashMessage('Ошибка: ' . $e->getMessage(), 'error');
                $this->redirect('this');
            }
        }
    }

    public function renderLogout()
    {
        $this->getUser()->logout();
        $this->redirect('Home:default');
    }

    public function renderRegistration()
    {
        if ($this->getUser()->isLoggedIn()) {
            $this->redirect('Home:default');
        }

    }

    public function renderEditProfile()
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->flashMessage('Ошибка: Вам нужно сначала авторизоваться', 'error');
            $this->redirect('Home:default');
        }

        if ($this->getHttpRequest()->getMethod() === 'POST') {

            $vals = $this->getHttpRequest()->getPost();
            $id = $this->getUser()->getId();
            $user = $this->db->table('user')->get($id);

            // TODO: УБРАТЬ ПОТОМ ЭТО НАХУЙ
            /*if ($vals['password'] !== '' || $vals['password2'] !== '') {
                if ($vals['password'] !== $vals['password2']) {
                    $this->flashMessage('Ошибка: Пароли не совпадают', 'error');
                    $this->redirect('this');
                }
                $pwd = md5($vals['password']);
            }*/

            // обновление строки
            $user->update([
                'f_name'     => $vals['name'],
                'l_name'     => $vals['surname'],
                'sex'        => $vals['sex'] == 2 ? 2 : 1,
                //'birthday'   => $vals['birthday'] ?: null,
                'nick'       => $vals['nickname'],
                'location'   => $vals['place'],
                //'passwd'     => $pwd
            ]);

            $this->flashMessage('Анкета сохранена!', 'success');
            $this->redirect('this');
        }
    }
}
