<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = 'Contatos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <table class="table">
        <thead>
            <th></th>
            <th></th>
        </thead>
        <tbody>
            <tr>
                <td>Telefone</td>
                <td><a href="whatsapp:+5513997458619">(13) 99745-8619</a></td>
            </tr>
            <tr>
                <td>E-mail</td>
                <td><a href="mailto:wildemar.barbosa@outlook.com">wildemar.barbosa@outlook.com</a></td>
            </tr>
            <tr>
                <td>Linkedin</td>
                <td><a href="https://www.linkedin.com/in/wildemar-barbosa/" target="_blank" rel="noopener noreferrer">wildemar-barbosa</a></td>
            </tr>
            <tr>
                <td>Github</td>
                <td><a href="https://github.com/demaroto" target="_blank" rel="noopener noreferrer">Github</a></td>
            </tr>
        </tbody>
    </table>
</div>
