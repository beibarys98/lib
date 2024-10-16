<?php

namespace frontend\controllers;

use common\models\Book;
use common\models\Category;
use common\models\Storage;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex($ctg = '')
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        //send categories
        $language = Yii::$app->language;
        $sortColumn = $language === 'ru-RU' ? 'title_ru' : 'title_kz';
        $category = new ActiveDataProvider([
            'query' => Category::find()->orderBy([
                $sortColumn => SORT_ASC
            ]),
        ]);

        //send books
        if($ctg != null){
            $book = new ActiveDataProvider([
                'query' => Book::find()->andWhere(['category_id' => $ctg])->orderBy(['authors' => SORT_ASC]),
                'pagination' => false
            ]);
        }else{
            $book = new ActiveDataProvider([
                'query' => Book::find()->orderBy(['authors' => SORT_ASC]),
                'pagination' => false
            ]);
        }

        return $this->render('index', [
            'category' => $category,
            'book' => $book
        ]);
    }

    public function actionSearch($query = null)
    {
        if ($query === null) {
            $query = Yii::$app->request->get('query');
        }

        $category = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        // Query the Book model for titles containing the search query
        $book = new ActiveDataProvider([
            'query' => Book::find()
                ->andWhere(['or',
                    ['like', 'title', $query],
                    ['like', 'authors', $query]
                ]),
            'pagination' => false
        ]);

        return $this->render('search', [
            'category' => $category,
            'book' => $book
        ]);
    }

    public function actionView($id = ''){
        $book = Book::find()->andWhere(['id' => $id])->one();

        return $this->render('view', [
            'book' => $book,
        ]);
    }

    public function actionRead($id = ''){
        //find the pdf
        $file = Storage::find()->andWhere(['model_id' => $id])
            ->andWhere(['like', 'file_path', '%.pdf', false])
            ->one();

        if (strpos($file->file_path, '/app') === 0) {
            $filePath = Yii::getAlias('@web/uploads/books/') . basename($file->file_path);
        } else {
            $filePath = Yii::getAlias('@web/uploads/books') . $file->file_path;
        }

        return $this->render('read', [
            'file' => $file,
            'filePath' => $filePath
        ]);
    }

    public function actionViewPdf($id = '')
    {
        $file = Storage::findOne($id);

        if (strpos($file->file_path, '/app') === 0) {
            $filePath = Yii::getAlias('@frontend/web/uploads/books/') . basename($file->file_path);
        } else {
            $filePath = Yii::getAlias('@frontend/web/uploads/books/') . $file->file_path;
        }

        if (!file_exists($filePath)) {
            throw new \yii\web\NotFoundHttpException('File not found.');
        }

        return Yii::$app->response->sendContentAsFile(file_get_contents($filePath), 'filename.pdf', [
            'mimeType' => 'application/pdf',
            'inline' => true,
        ]);
    }

    public function actionLanguage($view)
    {
        if(Yii::$app->language == 'kz-KZ'){
            Yii::$app->session->set('language', 'ru-RU');
        }else{
            Yii::$app->session->set('language', 'kz-KZ');
        }
        return $this->redirect([$view]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        }

        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Регистрация прошла успешно!'));
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
}
