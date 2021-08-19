<?php
/* Smarty version 3.1.30, created on 2021-04-13 09:06:07
  from "C:\wamp\www\orga\admin\templates\index.tpl" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_60755effbf1ea8_50561670',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8de37fce7865e77cb6b3b70efe24cee7a1a5a975' => 
    array (
      0 => 'C:\\wamp\\www\\orga\\admin\\templates\\index.tpl',
      1 => 1618304503,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_60755effbf1ea8_50561670 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/general.css">
    <link rel="stylesheet" href="../styles/gestion.css">
    <title>Accueil</title>
</head>

<body>
    <header>
        <div class="main_conteneur">
            <h1>Webzine</h1>
            <div class="user"><?php echo $_smarty_tpl->tpl_vars['PrenomLogin']->value;?>
</div>
            <h2>Accueil Administration globale</h2>
            <div class="main_conteneur">
                <nav>
                    <ul>
                        <li><a href="../index.php">Accueil</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <div class="main_conteneur">
            <p>Historique du système</p>
            <div class="histo_tab">
                <table>
                    <thead>
                        <tr>
                            <td>Date</td>
                            <td>Utilisateur</td>
                            <td>Action</td>
                            <td>Quoi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($_smarty_tpl->tpl_vars['Table_Histo']->value)) {?> <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['Table_Histo']->value, 'infos');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['infos']->value) {
?>
                        <tr>
                            <td><?php echo $_smarty_tpl->tpl_vars['infos']->value['date_histo'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['infos']->value['prenom'];?>
 <?php echo $_smarty_tpl->tpl_vars['infos']->value['nom'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['infos']->value['nom_action'];?>
</td>
                            <td><?php echo $_smarty_tpl->tpl_vars['infos']->value['quoi'];?>
</td>
                        </tr>
                        <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>
 <?php }?>
                    </tbody>
                </table>
            </div>
            <p><a href="https://phpmyadmin.cluster003.hosting.ovh.net/index.php" target="_blank">Base de données</a></p>
        </div>
    </main>
    <footer>
        <div class="main_conteneur">
            <p>(c)2021 Editiel98 - G. Crégut Pour PlastiDream V0.2</p>
        </div>
    </footer>

</body>

</html><?php }
}
