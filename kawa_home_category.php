<?php

class Kawa_Home_Category extends Module
{

    public function __construct()
    {
        $this->name = "kawa_home_category";
        $this->displayName = "Afficher les catégories sur la page d'acceuil";
        $this->version = '1.0.0';
        $this->author = "Lucien";
        $this->description = "Afficher les catégories sur la page d'acceuil";
        $this->bootstrap = true;

        parent::__construct();
    }

    //methode registerHook permet de greffer mon module sur un hook de presta

    public function install()
    {

        if (!parent::install() || !$this->registerHook('displayHome')) {
            return false;
        }

        return true;
    }

    //function permettan d'afficher le contenu sur le front.
    //la function du hook s'appelle toujours hook.nomDuHook
    //autant de funciton hook que de registerhook
    public function hookDisplayHome()
    {

        //déclarer un objet de type catégorie déjà hydraté
        $categorie1 = new Category(Configuration::get('KAWA_INFO_CATETGORY_1'), $this->context->language->id);



        //Récupère las infos de la table ps configuration et envoie à smarty

        $this->context->smarty->assign(array(
            'kawa_text' => Configuration::get('KAWA_INFO_CAT'), //clé : nom de ma rariable smarty /sa valeur
            'categorie1' => $categorie1,
        ));

        //mon fichier tpl doit se trouver dans le dossier views/templates/hook
        return $this->display(__FILE__, "home.tpl");
    }

    public function getContent()
    {
        $output = "";


        if (TOOLS::isSubmit('submit_kawa_home_category')) {


            //variable pour la configuration de message

            $kawa_info = Tools::getValue('KAWA_INFO_CAT');
            $category1 = Tools::getValue('KAWA_HOME_CATEGORY_1');
            $category2 = Tools::getValue('KAWA_HOME_CATEGORY_2');
            $category3 = Tools::getValue('KAWA_HOME_CATEGORY_3');
            $category4 = Tools::getValue('KAWA_HOME_CATEGORY_4');
            $categoryactive = Tools::getValue('KAWA_HOME_CATEGORY_ACTIVE');

            $image1 = Tools::getValue('KAWA_IMAGE_1'); // récupère le nom de l'image
            $image2 = Tools::getValue('KAWA_IMAGE_2');
            $image3 = Tools::getValue('KAWA_IMAGE_3');
            $image4 = Tools::getValue('KAWA_IMAGE_4');

            //enregistrement dans la table  ps-configuration
            Configuration::updateValue('KAWA_INFO_CAT', $kawa_info, true); // 3ème paramiètre me permet d'enregistrer de l'html
            Configuration::updateValue('KAWA_INFO_CATETGORY_1', $category1);
            Configuration::updateValue('KAWA_INFO_CATETGORY_2', $category2);
            Configuration::updateValue('KAWA_INFO_CATETGORY_3', $category3);
            Configuration::updateValue('KAWA_INFO_CATETGORY_4', $category4);
            Configuration::updateValue('KAWA_INFO_CATETGORY_ACTIVE', $categoryactive);

            if ($image1 || !empty($image1)) {
                if (move_uploaded_file($_FILES["KAWA_IMAGE_1"]["tmp_name"], dirname(__FILE__) . "/views/assets/img/" . $image1)) {
                    Configuration::updateValue('KAWA_IMAGE_1', $image1);
                } else {
                    $output = $this->displayError('Erreur lors du transfert');
                }
            } else {
                $output = $this->displayError('Image obligatoire');
            }

            if ($image2 || !empty($image2)) {
                if (move_uploaded_file($_FILES["KAWA_IMAGE_2"]["tmp_name"], dirname(__FILE__) . "/views/assets/img/" . $image2)) {
                    Configuration::updateValue('KAWA_IMAGE_2', $image2);
                } else {
                    $output = $this->displayError('Erreur lors du transfert');
                }
            } else {
                $output = $this->displayError('Image obligatoire');
            }

            if ($image3 || !empty($image3)) {
                if (move_uploaded_file($_FILES["KAWA_IMAGE_3"]["tmp_name"], dirname(__FILE__) . "/views/assets/img/" . $image3)) {
                    Configuration::updateValue('KAWA_IMAGE_3', $image3);
                } else {
                    $output = $this->displayError('Erreur lors du transfert');
                }
            } else {
                $output = $this->displayError('Image obligatoire');
            }

            if ($image4 || !empty($image4)) {
                if (move_uploaded_file($_FILES["KAWA_IMAGE_4"]["tmp_name"], dirname(__FILE__) . "/views/assets/img/" . $image4)) {
                    Configuration::updateValue('KAWA_IMAGE_4', $image4);
                } else {
                    $output = $this->displayError('Erreur lors du transfert');
                }
            } else {
                $output = $this->displayError('Image obligatoire');
            }


            //confirmation que le message à été envoyé
            $output .= $this->displayConfirmation("Informations enregistrées");
        }
        return $output . $this->displayForm();
    }

    public function displayForm()
    {

        //récupère les catégories présentes sur le site
        $categories = Category::getAllCategoriesName();
        // Tools::dieObject($categories);

        if (Configuration::get('KAWA_IMAGE_1')) {
            $lienimage1 = _MODULE_DIR_ . $this->name . "/views/assets/img/" . Configuration::get('KAWA_IMAGE_1');
        }

        if (Configuration::get('KAWA_IMAGE_2')) {
            $lienimage2 = _MODULE_DIR_ . $this->name . "/views/assets/img/" . Configuration::get('KAWA_IMAGE_2');
        }

        if (Configuration::get('KAWA_IMAGE_3')) {
            $lienimage3 = _MODULE_DIR_ . $this->name . "/views/assets/img/" . Configuration::get('KAWA_IMAGE_3');
        }

        if (Configuration::get('KAWA_IMAGE_4')) {
            $lienimage4 = _MODULE_DIR_ . $this->name . "/views/assets/img/" . Configuration::get('KAWA_IMAGE_4');
        }

        $form_configuration['0']['form'] = [
            'legend' => [
                'title' => $this->l('Configuration'),
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => $this->l('Information'),
                    'name' => 'KAWA_INFO_CAT',
                    'autoload_rte' => true, //permet d'insérer un éditeur wysiwig
                ],
                [
                    'type' => 'select',
                    'label' => 'Categorie 1',
                    'name' => 'KAWA_HOME_CATEGORY_1',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ],

                [
                    'type' => 'file',
                    'label' => 'Image pour catégorie 1',
                    'name' => 'KAWA_IMAGE_1',
                    'required' => true,
                    'image' => (isset($lienimage1) && $lienimage1 ? '<img src="' . $lienimage1 . '" width="200px" height="auto">' : false)
                ],

                [
                    'type' => 'select',
                    'label' => 'Categorie 2',
                    'name' => 'KAWA_HOME_CATEGORY_2',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ],

                [
                    'type' => 'file',
                    'label' => 'Image pour catégorie 2',
                    'name' => 'KAWA_IMAGE_2',
                    'required' => true,
                    'image' => (isset($lienimage2) && $lienimage2 ? '<img src="' . $lienimage2 . '" width="200px" height="auto">' : false)
                ],

                [
                    'type' => 'select',
                    'label' => 'Categorie 3',
                    'name' => 'KAWA_HOME_CATEGORY_3',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ],

                [
                    'type' => 'file',
                    'label' => 'Image pour catégorie 3',
                    'name' => 'KAWA_IMAGE_3',
                    'required' => true,
                    'image' => (isset($lienimage3) && $lienimage3 ? '<img src="' . $lienimage3 . '" width="200px" height="auto">' : false)
                ],

                [
                    'type' => 'select',
                    'label' => 'Categorie 4',
                    'name' => 'KAWA_HOME_CATEGORY_4',
                    'required' => true,
                    'options' => array(
                        'query' => $categories,
                        'id' => 'id_category',
                        'name' => 'name'
                    )
                ],

                [
                    'type' => 'file',
                    'label' => 'Image pour catégorie 4',
                    'name' => 'KAWA_IMAGE_4',
                    'required' => true,
                    'image' => (isset($lienimage4) && $lienimage4 ? '<img src="' . $lienimage4 . '" width="200px" height="auto">' : false)
                ],

                [

                    'type' => 'switch',
                    'label' => 'Afficher sur home',
                    'name' => 'KAWA_HOME_CATEGORY_ACTIVE',
                    'is_bool' => true,
                    'values' => array(
                        array(
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Enable')
                        ),
                        array(
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Disabled')
                        ),
                    )


                ]

            ],

            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ]
        ];

        $helper = new HelperForm();

        $helper->module = $this; // instance du module
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules'); //récupère le token de la page module

        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name; //configurer l'atttribut action du formulaire
        $helper->default_form_language = (int)configuration::get('PS_LANG_DEFAULT');
        $helper->title = $this->displayName;
        $helper->submit_action = "submit_" . $this->name;  //ajoute un attribut name a mon bouton

        $helper->fields_value['KAWA_HOME_CATEGORY_1'] = Tools::getValue('KAWA_HOME_CATEGORY_1', Configuration::get('KAWA_HOME_CATEGORY_1'));

        $helper->fields_value['KAWA_HOME_CATEGORY_2'] = Tools::getValue('KAWA_HOME_CATEGORY_2', Configuration::get('KAWA_HOME_CATEGORY_2'));

        $helper->fields_value['KAWA_HOME_CATEGORY_3'] = Tools::getValue('KAWA_HOME_CATEGORY_3', Configuration::get('KAWA_HOME_CATEGORY_3'));

        $helper->fields_value['KAWA_HOME_CATEGORY_4'] = Tools::getValue('KAWA_HOME_CATEGORY_4', Configuration::get('KAWA_HOME_CATEGORY_4'));

        $helper->fields_value['KAWA_HOME_CATEGORY_ACTIVE'] = Tools::getValue('KAWA_HOME_CATEGORY_ACTIVE', Configuration::get('KAWA_HOME_CATEGORY_ACTIVE'));

        $helper->fields_value['KAWA_IMAGE_1'] = Tools::getValue('KAWA_IMAGE_1', Configuration::get('KAWA_IMAGE_1'));

        $helper->fields_value['KAWA_IMAGE_2'] = Tools::getValue('KAWA_IMAGE_2', Configuration::get('KAWA_IMAGE_2'));

        $helper->fields_value['KAWA_IMAGE_3'] = Tools::getValue('KAWA_IMAGE_3', Configuration::get('KAWA_IMAGE_3'));

        $helper->fields_value['KAWA_IMAGE_4'] = Tools::getValue('KAWA_IMAGE_4', Configuration::get('KAWA_IMAGE_4'));

        $helper->fields_value['KAWA_INFO_CAT'] = Tools::getValue('KAWA_INFO_CAT', Configuration::get('KAWA_INFO_CAT'));


        return $helper->generateForm($form_configuration);
    }
}
