<?php

namespace Rhubarb\AuthenticationWithTwoFactorAuth\Login;

use Rhubarb\AuthenticationWithTwoFactorAuth\LoginProviders\TwoFactorLoginProvider;
use Rhubarb\Leaf\Controls\Common\Buttons\Button;
use Rhubarb\Leaf\Controls\Common\Text\TextBox;
use Rhubarb\Scaffolds\Authentication\Leaves\LoginView;

class TwoFactorLoginView extends LoginView
{
    /** @var TwoFactorLoginModel */
    protected $model;

    public function createSubLeaves()
    {
        parent::createSubLeaves();

        $this->registerSubLeaf(
            $code = new TextBox('Code'),
            new Button('Confirm', 'Confirm', function () {
                $this->model->verifyCodeEvent->raise();
            })
        );

        $code->addHtmlAttribute('autofocus', 'autofocus');
    }

    public function printViewContent()
    {
        if ($this->model->promptForCode) {
            /** @var TwoFactorLoginProvider $loginProviderClass */
            $loginProviderClass = $this->model->loginProvider;
            $loginProvider = $loginProviderClass::singleton();

            if($this->model->codeNotValid) {
                print '<div class="c-alert c-alert--error">Sorry, this code does not match the one we sent you, please check and try again.</div>';
            }

            ?>
            <div class="c-alert">A text message with a 6-digit verification code was just sent to your phone. Please
                enter
                the code below. <?= $loginProvider->verificationCode ?>
            </div>
            <fieldset class="c-form c-form--inline">
                <div class="c-form__group">
                    <label class="c-form__label">Verification Code</label>
                    <?= $this->leaves['Code']; ?>
                </div>

                <div class="c-form__actions">
                    <?= $this->leaves['Confirm']; ?>
                </div>
            </fieldset>

            <?php
        } else {
            parent::printViewContent();
        }
    }
}